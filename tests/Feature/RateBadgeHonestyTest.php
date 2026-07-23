<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Services\RateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * El badge "En vivo" de las vistas públicas era incondicional: anunciaba tasa
 * viva aunque se estuviera sirviendo el seed de la DB o un caché — dinero
 * visible al público con una promesa falsa. Debe decir la verdad según
 * rate_source: forex → "En vivo", cache → "Caché" (última tasa real conocida),
 * seed → "Referencial".
 */
class RateBadgeHonestyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.exchange_api.min_spread_pct' => 0]);
    }

    /** @test */
    public function dolar_hoy_shows_live_badge_only_when_forex_responded(): void
    {
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        Http::fake([
            '*/exchange-rates/primary/' => Http::response(
                ['USD' => ['scale_factor' => 1, 'buy_rate' => '13.35', 'sell_rate' => '13.55', 'official_rate' => '13.45']],
                200
            ),
            '*/official/*' => Http::response(['currency' => 'USD', 'official_rate' => 6.96], 200),
        ]);

        $this->get('/dolar-hoy-bolivia')
            ->assertOk()
            ->assertSee('<span class="kap-live-badge">En vivo</span>', false)
            ->assertDontSee('kap-live-badge--cache');
    }

    /** @test */
    public function dolar_hoy_shows_referential_badge_when_serving_the_seed(): void
    {
        // Sin forex configurado → el fallback sembrado NUNCA se anuncia "En vivo".
        config(['services.exchange_api.url' => '']);
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        $this->get('/dolar-hoy-bolivia')
            ->assertOk()
            ->assertSee('kap-live-badge--cache">Referencial<', false)
            ->assertDontSee('<span class="kap-live-badge">En vivo</span>', false);
    }

    /** @test */
    public function dolar_hoy_shows_cache_badge_with_last_known_good_after_forex_falls(): void
    {
        config(['services.exchange_api.url' => 'http://forex.test/api/rates']);
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        // Secuencia: 1ª lectura viva (prime del last-known-good), después cae.
        Http::fake([
            '*/exchange-rates/primary/' => Http::sequence()
                ->push(['USD' => ['scale_factor' => 1, 'buy_rate' => '13.35', 'sell_rate' => '13.55', 'official_rate' => '13.45']], 200)
                ->whenEmpty(Http::response('', 500)),
            '*/official/*' => Http::response(['currency' => 'USD', 'official_rate' => 6.96], 200),
        ]);

        app(RateService::class)->getActiveRatesWithAll();
        app(RateService::class)->invalidate();

        $this->get('/dolar-hoy-bolivia')
            ->assertOk()
            ->assertSee('kap-live-badge--cache">Caché<', false)
            ->assertSee('13.35') // última tasa REAL conocida, no el seed 6.90
            ->assertDontSee('<span class="kap-live-badge">En vivo</span>', false);
    }

    /** @test */
    public function convertidor_shows_honest_badge_for_the_seed_too(): void
    {
        config(['services.exchange_api.url' => '']);
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        $this->get('/convertidor/usd-bob')
            ->assertOk()
            ->assertSee('kap-live-badge--cache">Referencial<', false)
            ->assertDontSee('<span class="kap-live-badge">En vivo</span>', false);
    }
}
