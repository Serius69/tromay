<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Models\CashRate;
use App\Services\RateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Cubre la resiliencia del overlay de forex: cuando el feed cae (HTTP 5xx /
 * timeout) o devuelve basura (0/negativo), Tromay debe conservar los valores
 * sembrados en `cashes` como fallback y NUNCA mostrar 0 al público.
 */
class RateServiceFallbackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // El overlay solo se dispara si hay una URL de forex configurada.
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);
        config(['services.exchange_api.min_spread_pct' => 0]);
    }

    /** @test */
    public function it_keeps_seeded_rates_when_forex_returns_5xx(): void
    {
        $cash = Cash::factory()->create([
            'status' => 1, 'name' => 'usd',
            'buy' => 6.90, 'sell' => 7.10, 'oficial' => 7.00,
        ]);

        // El CashObserver ya graba un snapshot al sembrar el factory; lo que
        // importa es que el FALLBACK no agregue ninguno más.
        $seedSnapshots = CashRate::count();

        Http::fake(['*/exchange-rates/primary/' => Http::response('', 500)]);

        $rate = app(RateService::class)->getActiveRatesWithAll()->firstWhere('id', $cash->id);

        $this->assertNotNull($rate);
        $this->assertEqualsWithDelta(6.90, (float) $rate->buy, 1e-6);
        $this->assertEqualsWithDelta(7.10, (float) $rate->sell, 1e-6);
        // fallback => NO se marca como fuente forex ni se persiste snapshot.
        $this->assertNull($rate->getAttribute('rate_source'));
        $this->assertSame($seedSnapshots, CashRate::count());
    }

    /** @test */
    public function it_keeps_seeded_rates_when_forex_connection_times_out(): void
    {
        $cash = Cash::factory()->create([
            'status' => 1, 'name' => 'eur',
            'buy' => 11.60, 'sell' => 11.90, 'oficial' => 11.75,
        ]);

        Http::fake([
            '*/exchange-rates/primary/' => fn () => throw new ConnectionException('timeout'),
        ]);

        $rate = app(RateService::class)->getActiveRatesWithAll()->firstWhere('id', $cash->id);

        $this->assertNotNull($rate);
        $this->assertEqualsWithDelta(11.60, (float) $rate->buy, 1e-6);
        $this->assertEqualsWithDelta(11.90, (float) $rate->sell, 1e-6);
    }

    /** @test */
    public function it_does_not_show_zero_when_forex_returns_zero_or_negative(): void
    {
        $cash = Cash::factory()->create([
            'status' => 1, 'name' => 'usd',
            'buy' => 6.90, 'sell' => 7.10, 'oficial' => 7.00,
        ]);

        // Glitch de forex: buy_rate=0 (y sell negativo). El guard debe descartarlo
        // y conservar el fallback sembrado en vez de sobreponer 0/negativo.
        Http::fake([
            '*/exchange-rates/primary/' => Http::response([
                'USD' => ['scale_factor' => 1, 'buy_rate' => '0', 'sell_rate' => '-1', 'official_rate' => '0'],
            ], 200),
        ]);

        $rate = app(RateService::class)->getActiveRatesWithAll()->firstWhere('id', $cash->id);

        $this->assertNotNull($rate);
        $this->assertGreaterThan(0, (float) $rate->buy);
        $this->assertEqualsWithDelta(6.90, (float) $rate->buy, 1e-6);
        $this->assertEqualsWithDelta(7.10, (float) $rate->sell, 1e-6);
        $this->assertNull($rate->getAttribute('rate_source'));
    }

    /** @test */
    public function it_serves_last_known_good_when_forex_falls_after_a_successful_overlay(): void
    {
        $cash = Cash::factory()->create([
            'status' => 1, 'name' => 'usd',
            'buy' => 6.90, 'sell' => 7.10, 'oficial' => 7.00,
        ]);

        // Secuencia: 1ª lectura con forex vivo, después cae (500 permanente).
        Http::fake([
            '*/exchange-rates/primary/' => Http::sequence()
                ->push(['USD' => ['scale_factor' => 1, 'buy_rate' => '13.35', 'sell_rate' => '13.55', 'official_rate' => '13.45']], 200)
                ->whenEmpty(Http::response('', 500)),
            '*/official/*' => Http::response(['currency' => 'USD', 'official_rate' => 6.96], 200),
        ]);

        // 1) Overlay real → queda cacheado como last-known-good.
        $live = app(RateService::class)->getActiveRatesWithAll()->firstWhere('id', $cash->id);
        $this->assertSame('forex', $live->getAttribute('rate_source'));
        // Conteo RELATIVO: el CashObserver ya graba un snapshot al crear el
        // factory, así que solo interesa que la fase 'cache' no agregue más.
        $snapshotsAfterLive = CashRate::count();

        // 2) forex cae: debe servir la ÚLTIMA tasa REAL (13.35/13.55), no el
        //    seed congelado (6.90/7.10), marcada 'cache' (badge honesto) y SIN
        //    persistir un snapshot nuevo (solo rate_source='forex' persiste).
        app(RateService::class)->invalidate();
        $cached = app(RateService::class)->getActiveRatesWithAll()->firstWhere('id', $cash->id);

        $this->assertEqualsWithDelta(13.35, (float) $cached->buy, 1e-6);
        $this->assertEqualsWithDelta(13.55, (float) $cached->sell, 1e-6);
        $this->assertEqualsWithDelta(6.96, (float) $cached->oficial, 1e-6);
        $this->assertSame('cache', $cached->getAttribute('rate_source'));
        $this->assertSame($snapshotsAfterLive, CashRate::count());
    }
}
