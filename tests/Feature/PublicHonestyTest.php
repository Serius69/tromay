<?php

namespace Tests\Feature;

use App\Models\Cash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Guardas de honestidad y de higiene de la superficie pública.
 *
 * Existen porque son regresiones que ya ocurrieron: una antigüedad concreta
 * afirmada en datos estructurados sin poder respaldarla, y ruido binario de
 * punto flotante servido como si fuera una cotización.
 */
class PublicHonestyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function no_public_page_claims_a_specific_number_of_years_in_business(): void
    {
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        // La antigüedad exacta no está respaldada por ninguna fuente del repo;
        // el copy habla de "décadas" y los datos estructurados no la afirman.
        $forbidden = ['30 años', 'tres décadas', 'Tres décadas', 'años 90', '30 years'];

        foreach (['/', '/about', '/quote', '/dolar-hoy-bolivia'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            foreach ($forbidden as $claim) {
                $this->assertStringNotContainsString(
                    $claim,
                    $html,
                    "La página {$url} afirma una antigüedad concreta ({$claim}) que no se puede respaldar."
                );
            }
        }
    }

    /** @test */
    public function published_rates_are_rounded_to_the_precision_of_the_column(): void
    {
        config([
            'services.exchange_api.url'            => 'http://forex.test/api/rates',
            'services.exchange_api.min_spread_pct' => 2.5,
        ]);

        // Spread plano: obliga al ensanchado, que es donde nacía el ruido binario
        // (p.ej. sell = 0.012103999999999998 servido en /api/rates).
        Cash::factory()->create(['status' => 1, 'name' => 'clp', 'buy' => 0.011, 'sell' => 0.0111]);

        Http::fake([
            '*/exchange-rates/primary/' => Http::response(
                ['CLP' => ['scale_factor' => 1000, 'buy_rate' => '11.1', 'sell_rate' => '11.2']],
                200
            ),
            '*/official/*' => Http::response('', 500),
        ]);

        $data = $this->getJson('/api/rates')->assertOk()->json('data');

        foreach ($data as $rate) {
            foreach (['buy', 'sell', 'oficial'] as $field) {
                if ($rate[$field] === null) {
                    continue;
                }

                $decimals = strlen(substr(strrchr((string) $rate[$field], '.') ?: '', 1));

                $this->assertLessThanOrEqual(
                    6,
                    $decimals,
                    "El campo {$field} salió con ruido de punto flotante: {$rate[$field]}"
                );
            }
        }
    }

    /**
     * El 404 real era la página genérica de Laravel, en inglés y sin salida:
     * `resources/views/404.blade.php` nunca estuvo cableado (Laravel resuelve
     * `errors/404`), así que "traducirlo" no cambiaba nada.
     *
     * @test
     */
    public function the_404_page_is_branded_spanish_and_offers_a_way_back(): void
    {
        $response = $this->get('/una-ruta-que-no-existe')->assertNotFound();

        $response->assertSee('No encontramos esta página');
        $response->assertSee('Volver al inicio');
        $response->assertDontSee('Page Not Found');

        // Debe ser la plantilla del sitio, no el fallback genérico del framework.
        $response->assertSee('Tromay', false);
    }

    /**
     * Regresión: el pie de `layout.master` recorre $cashes, que solo pasaban los
     * controladores del sitio. Cualquier página de error reventaba al renderizar
     * ("Undefined variable $cashes") y Laravel caía a su plantilla genérica.
     *
     * @test
     */
    public function error_pages_render_with_the_site_layout_even_without_controller_data(): void
    {
        Cash::factory()->create(['status' => 1, 'name' => 'usd', 'buy' => 6.90, 'sell' => 7.10]);

        // Si el layout volviera a romperse, esto devolvería 500 en vez de 404.
        $this->get('/otra-ruta-inexistente')
            ->assertNotFound()
            ->assertSee('No encontramos esta página');
    }

    /**
     * Los assets propios se sirven con `max-age=604800` y nombre fijo: sin huella,
     * Cloudflare seguía entregando el CSS/JS anterior después de un despliegue
     * (se verificó en producción). La huella es lo que invalida el borde.
     *
     * @test
     */
    public function own_assets_are_fingerprinted_so_a_deploy_invalidates_the_edge(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        foreach (['assets/css/kapitalya.css', 'assets/js/kapitalya.js'] as $asset) {
            $this->assertMatchesRegularExpression(
                '#' . preg_quote($asset, '#') . '\?v=\d+#',
                $html,
                "El asset {$asset} se enlaza sin huella: un despliegue no invalidaría el borde."
            );
        }
    }

    /** @test */
    public function the_public_api_carries_the_same_security_headers_as_the_site(): void
    {
        $response = $this->getJson('/api/rates')->assertOk();

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Sin huella del servidor en la API pública.
        $this->assertNull($response->headers->get('X-Powered-By'));
    }
}
