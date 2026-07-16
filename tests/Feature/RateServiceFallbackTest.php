<?php

namespace Tests\Feature;

use App\Models\Cash;
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

        Http::fake(['*/exchange-rates/primary/' => Http::response('', 500)]);

        $rate = app(RateService::class)->getActiveRatesWithAll()->firstWhere('id', $cash->id);

        $this->assertNotNull($rate);
        $this->assertEqualsWithDelta(6.90, (float) $rate->buy, 1e-6);
        $this->assertEqualsWithDelta(7.10, (float) $rate->sell, 1e-6);
        // fallback => NO se marca como fuente forex ni se persiste snapshot.
        $this->assertNull($rate->getAttribute('rate_source'));
        $this->assertDatabaseCount('cash_rates', 0);
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
}
