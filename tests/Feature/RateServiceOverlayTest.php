<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Services\RateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RateServiceOverlayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, float>  $official  oficial REAL por divisa (endpoint
     *                                          dedicado /official/), a propósito
     *                                          DISTINTO del `official_rate` de
     *                                          /exchange-rates/primary/ en $rates
     *                                          — así cualquier test que confunda
     *                                          las dos fuentes falla de forma obvia.
     */
    private function fakeForex(array $rates, array $official = []): void
    {
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);

        $fakes = ['*/exchange-rates/primary/*' => Http::response($rates, 200)];

        foreach ($official as $currency => $rate) {
            $fakes["*/official/*currency={$currency}*"] = Http::response(
                ['currency' => $currency, 'official_rate' => $rate], 200
            );
        }

        Http::fake($fakes);
    }

    /** @test */
    public function overlay_divides_by_scale_factor_for_batch_quoted_currencies(): void
    {
        config(['services.exchange_api.min_spread_pct' => 0]);
        Cash::factory()->create(['status' => 1, 'name' => 'ars']);

        // forex cotiza ARS por lote de 1000 → la UI usa la tasa por unidad.
        // `official_rate` (6.81) es el mid del paralelo mal etiquetado: el oficial
        // real (0.0071) viene del endpoint /official/, deliberadamente distinto.
        $this->fakeForex(
            ['ARS' => ['scale_factor' => 1000, 'buy_rate' => '6.80', 'sell_rate' => '6.82', 'official_rate' => '6.81']],
            ['ARS' => 0.0071],
        );

        // OJO: el accessor `name` de Cash capitaliza (ars→Ars), así que
        // firstWhere('name','ars') devolvería null. Se compara normalizado.
        $ars = app(RateService::class)->getActiveRates()->first(fn (Cash $c) => strtolower($c->name) === 'ars');

        $this->assertEqualsWithDelta(0.00680, (float) $ars->buy, 1e-6);
        $this->assertEqualsWithDelta(0.00682, (float) $ars->sell, 1e-6);
        // oficial viene del endpoint /official/ real, NO de official_rate/scale (0.00681).
        $this->assertEqualsWithDelta(0.0071, (float) $ars->oficial, 1e-6);
        $this->assertNotEqualsWithDelta(0.00681, (float) $ars->oficial, 1e-6);
    }

    /** @test */
    public function overlay_widens_thin_spread_to_the_configured_minimum(): void
    {
        config(['services.exchange_api.min_spread_pct' => 2.0]);
        Cash::factory()->create(['status' => 1, 'name' => 'eur']);

        // spread crudo ~0.17% < 2% → se ensancha a 2% alrededor del mid (11.61).
        $this->fakeForex(
            ['EUR' => ['scale_factor' => 1, 'buy_rate' => '11.60', 'sell_rate' => '11.62', 'official_rate' => '11.61']],
            ['EUR' => 7.55],
        );

        $eur = app(RateService::class)->getActiveRates()->first(fn (Cash $c) => strtolower($c->name) === 'eur');

        $mid       = ((float) $eur->buy + (float) $eur->sell) / 2;
        $spreadPct = ((float) $eur->sell - (float) $eur->buy) / $mid * 100;

        $this->assertEqualsWithDelta(11.61, $mid, 1e-6);
        $this->assertEqualsWithDelta(2.0, $spreadPct, 1e-6);
    }

    /** @test */
    public function overlay_preserves_a_healthy_forex_spread(): void
    {
        config(['services.exchange_api.min_spread_pct' => 2.0]);
        Cash::factory()->create(['status' => 1, 'name' => 'usd']);

        // USD ya trae ~9.5% de spread (> 2%) → se respeta sin modificar.
        $this->fakeForex(
            ['USD' => ['scale_factor' => 1, 'buy_rate' => '10.00', 'sell_rate' => '11.00', 'official_rate' => '10.50']],
            ['USD' => 11.00],
        );

        $usd = app(RateService::class)->getActiveRates()->first(fn (Cash $c) => strtolower($c->name) === 'usd');

        $this->assertEqualsWithDelta(10.00, (float) $usd->buy, 1e-6);
        $this->assertEqualsWithDelta(11.00, (float) $usd->sell, 1e-6);
    }

    /** @test */
    public function official_comes_from_dedicated_endpoint_not_primary_mid(): void
    {
        config(['services.exchange_api.min_spread_pct' => 0]);
        Cash::factory()->create(['status' => 1, 'name' => 'usd']);

        // official_rate de /primary/ (6.96) es el mid del paralelo mal etiquetado;
        // el endpoint /official/ real devuelve 11.00 — deben verse valores distintos.
        $this->fakeForex(
            ['USD' => ['scale_factor' => 1, 'buy_rate' => '13.35', 'sell_rate' => '13.55', 'official_rate' => '6.96']],
            ['USD' => 11.00],
        );

        $usd = app(RateService::class)->getActiveRates()->first(fn (Cash $c) => strtolower($c->name) === 'usd');

        $this->assertEqualsWithDelta(11.00, (float) $usd->oficial, 1e-6);
        $this->assertNotEqualsWithDelta(6.96, (float) $usd->oficial, 1e-6);
    }

    /** @test */
    public function non_usd_official_is_null_not_frozen_seed_when_forex_has_no_real_official(): void
    {
        config(['services.exchange_api.min_spread_pct' => 0]);
        Cash::factory()->create(['status' => 1, 'name' => 'eur', 'oficial' => 8.09]);
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'oficial' => 6.96]);

        // forex-erp solo refresca el oficial de USD/BOB: /official/ cae (500)
        // para todas. EUR NO debe mostrar el seed congelado 8.09 como oficial
        // vigente (→ null; las vistas pintan '—'); USD conserva su fallback.
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);
        Http::fake([
            '*/exchange-rates/primary/*' => Http::response([
                'EUR' => ['scale_factor' => 1, 'buy_rate' => '15.50', 'sell_rate' => '15.90', 'official_rate' => '15.70'],
                'USD' => ['scale_factor' => 1, 'buy_rate' => '13.35', 'sell_rate' => '13.55', 'official_rate' => '13.45'],
            ], 200),
            '*/official/*' => Http::response('', 500),
        ]);

        $rates = app(RateService::class)->getActiveRates();
        $eur   = $rates->first(fn (Cash $c) => strtolower($c->name) === 'eur');
        $usd   = $rates->first(fn (Cash $c) => strtolower($c->name) === 'usd');

        $this->assertNull($eur->oficial);
        $this->assertEqualsWithDelta(6.96, (float) $usd->oficial, 1e-6);
        // buy/sell del overlay siguen vivos aunque el oficial no exista.
        $this->assertEqualsWithDelta(15.50, (float) $eur->buy, 1e-6);
        $this->assertSame('forex', $eur->getAttribute('rate_source'));
    }

    /** @test */
    public function official_falls_back_to_last_cached_value_when_endpoint_fails_not_hardcode(): void
    {
        config(['services.exchange_api.min_spread_pct' => 0]);
        $cash = Cash::factory()->create(['status' => 1, 'name' => 'usd', 'oficial' => 6.96]);

        // Primera lectura: /official/ responde 11.00 y queda cacheado.
        $this->fakeForex(
            ['USD' => ['scale_factor' => 1, 'buy_rate' => '13.35', 'sell_rate' => '13.55', 'official_rate' => '6.96']],
            ['USD' => 11.00],
        );
        app(RateService::class)->invalidate();
        $usd = app(RateService::class)->getActiveRates()->firstWhere('id', $cash->id);
        $this->assertEqualsWithDelta(11.00, (float) $usd->oficial, 1e-6);

        // Segunda lectura: /official/ cae (500), pero /primary/ sigue viva. Debe
        // servir el ÚLTIMO oficial cacheado (11.00), nunca el sembrado (6.96).
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);
        Http::fake([
            '*/exchange-rates/primary/*' => Http::response(
                ['USD' => ['scale_factor' => 1, 'buy_rate' => '13.36', 'sell_rate' => '13.56', 'official_rate' => '6.96']],
                200
            ),
            '*/official/*' => Http::response('', 500),
        ]);
        app(RateService::class)->invalidate();
        $usd2 = app(RateService::class)->getActiveRates()->firstWhere('id', $cash->id);
        $this->assertEqualsWithDelta(11.00, (float) $usd2->oficial, 1e-6);
    }
}
