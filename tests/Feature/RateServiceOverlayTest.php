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

    private function fakeForex(array $rates): void
    {
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);
        Http::fake([
            '*/exchange-rates/primary/' => Http::response($rates, 200),
        ]);
    }

    /** @test */
    public function overlay_divides_by_scale_factor_for_batch_quoted_currencies(): void
    {
        config(['services.exchange_api.min_spread_pct' => 0]);
        Cash::factory()->create(['status' => 1, 'name' => 'ars']);

        // forex cotiza ARS por lote de 1000 → la UI usa la tasa por unidad.
        $this->fakeForex([
            'ARS' => ['scale_factor' => 1000, 'buy_rate' => '6.80', 'sell_rate' => '6.82', 'official_rate' => '6.81'],
        ]);

        // OJO: el accessor `name` de Cash capitaliza (ars→Ars), así que
        // firstWhere('name','ars') devolvería null. Se compara normalizado.
        $ars = app(RateService::class)->getActiveRates()->first(fn (Cash $c) => strtolower($c->name) === 'ars');

        $this->assertEqualsWithDelta(0.00680, (float) $ars->buy, 1e-6);
        $this->assertEqualsWithDelta(0.00682, (float) $ars->sell, 1e-6);
        $this->assertEqualsWithDelta(0.00681, (float) $ars->oficial, 1e-6);
    }

    /** @test */
    public function overlay_widens_thin_spread_to_the_configured_minimum(): void
    {
        config(['services.exchange_api.min_spread_pct' => 2.0]);
        Cash::factory()->create(['status' => 1, 'name' => 'eur']);

        // spread crudo ~0.17% < 2% → se ensancha a 2% alrededor del mid (11.61).
        $this->fakeForex([
            'EUR' => ['scale_factor' => 1, 'buy_rate' => '11.60', 'sell_rate' => '11.62', 'official_rate' => '11.61'],
        ]);

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
        $this->fakeForex([
            'USD' => ['scale_factor' => 1, 'buy_rate' => '10.00', 'sell_rate' => '11.00', 'official_rate' => '10.50'],
        ]);

        $usd = app(RateService::class)->getActiveRates()->first(fn (Cash $c) => strtolower($c->name) === 'usd');

        $this->assertEqualsWithDelta(10.00, (float) $usd->buy, 1e-6);
        $this->assertEqualsWithDelta(11.00, (float) $usd->sell, 1e-6);
    }
}
