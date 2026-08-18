<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Services\RateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
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

    /**
     * Afirma el estado del badge sin depender del markup exacto.
     *
     * El badge lleva `data-kap-badge` (id de la divisa) para que el polling de
     * 60s pueda re-sincronizarlo en cliente; encadenar el HTML literal hacía que
     * cualquier atributo nuevo rompiera el test sin que la honestidad cambiara.
     * Lo que importa es: el badge "En vivo" NO lleva el modificador --cache, y
     * los degradados SÍ lo llevan junto a su etiqueta.
     */
    private function assertBadge(TestResponse $response, string $expected): void
    {
        $html = $response->getContent();

        preg_match_all('/<span class="kap-live-badge([^"]*)"[^>]*>([^<]+)<\/span>/', $html, $m, PREG_SET_ORDER);

        $badges = array_map(
            fn (array $hit) => ['cache' => str_contains($hit[1], '--cache'), 'text' => trim($hit[2])],
            $m
        );

        $this->assertNotEmpty($badges, 'No se encontró ningún badge de frescura en la página.');

        $match = array_values(array_filter($badges, fn ($b) => $b['text'] === $expected));
        $this->assertNotEmpty($match, sprintf(
            'Se esperaba un badge "%s"; se encontraron: %s',
            $expected,
            json_encode($badges, JSON_UNESCAPED_UNICODE)
        ));

        // "En vivo" es la única afirmación de tasa viva: no puede venir marcada
        // como degradada, y ningún otro badge puede decir "En vivo" a la vez.
        $this->assertSame(
            $expected !== 'En vivo',
            $match[0]['cache'],
            sprintf('El modificador --cache no corresponde al estado "%s".', $expected)
        );

        if ($expected !== 'En vivo') {
            $this->assertEmpty(
                array_filter($badges, fn ($b) => $b['text'] === 'En vivo'),
                'Se está anunciando "En vivo" mientras se sirve una tasa degradada.'
            );
        }
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

        $this->assertBadge($this->get('/dolar-hoy-bolivia')->assertOk(), 'En vivo');
    }

    /** @test */
    public function dolar_hoy_shows_referential_badge_when_serving_the_seed(): void
    {
        // Sin forex configurado → el fallback sembrado NUNCA se anuncia "En vivo".
        config(['services.exchange_api.url' => '']);
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        $this->assertBadge($this->get('/dolar-hoy-bolivia')->assertOk(), 'Referencial');
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

        $response = $this->get('/dolar-hoy-bolivia')->assertOk();

        $this->assertBadge($response, 'Caché');
        $response->assertSee('13.35'); // última tasa REAL conocida, no el seed 6.90
    }

    /** @test */
    public function convertidor_shows_honest_badge_for_the_seed_too(): void
    {
        config(['services.exchange_api.url' => '']);
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        $this->assertBadge($this->get('/convertidor/usd-bob')->assertOk(), 'Referencial');
    }
}
