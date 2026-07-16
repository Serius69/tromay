<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Latest;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Sitemap XML dinámico: páginas públicas + divisas + noticias.
     * Ayuda al posicionamiento SEO de Tromay como casa de cambio líder.
     */
    public function index(): Response
    {
        $urls = [];

        // Páginas estáticas públicas (prioridad + frecuencia de cambio)
        $static = [
            ['home',      '1.0', 'hourly'],   // tasas en vivo
            ['dolar-hoy', '0.9', 'hourly'],   // landing SEO "dólar hoy Bolivia"
            ['about',   '0.7', 'monthly'],
            ['quote',   '0.8', 'daily'],
            ['contact', '0.6', 'monthly'],
            ['privacy', '0.3', 'yearly'],
            ['terms',   '0.3', 'yearly'],
        ];
        foreach ($static as [$route, $priority, $freq]) {
            $urls[] = [
                'loc'        => route($route),
                'changefreq' => $freq,
                'priority'   => $priority,
            ];
        }

        // Detalle por divisa — lastmod = hoy: las tasas cambian a diario, así
        // Google recrawlea estas páginas con más frecuencia.
        $today = \Carbon\Carbon::now()->toDateString();
        foreach (Cash::where('status', 1)->orderBy('id')->get() as $cash) {
            $urls[] = [
                'loc'        => route('dinero.show', $cash->id),
                'lastmod'    => $today,
                'changefreq' => 'daily',
                'priority'   => '0.7',
            ];
            $urls[] = [
                'loc'        => route('seo.convertidor', ['par' => strtolower($cash->name) . '-bob']),
                'lastmod'    => $today,
                'changefreq' => 'daily',
                'priority'   => '0.7',
            ];
        }

        // Noticias publicadas
        foreach (Latest::published()->orderByDesc('date_publication')->limit(100)->get() as $latest) {
            $urls[] = [
                'loc'        => route('noticia.show', $latest->id),
                'lastmod'    => $latest->date_publication
                                    ? \Carbon\Carbon::parse($latest->date_publication)->toDateString()
                                    : null,
                'changefreq' => 'monthly',
                'priority'   => '0.5',
            ];
        }

        return response($this->render($urls), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * Construye el XML del sitemap en PHP (no Blade): evita el hazard de
     * `short_open_tag` que reinterpreta la declaración `<?xml ...?>`.
     */
    private function render(array $urls): string
    {
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($urls as $url) {
            $lines[] = '  <url>';
            $lines[] = '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . '</loc>';
            if (! empty($url['lastmod'])) {
                $lines[] = '    <lastmod>' . htmlspecialchars($url['lastmod'], ENT_XML1) . '</lastmod>';
            }
            $lines[] = '    <changefreq>' . $url['changefreq'] . '</changefreq>';
            $lines[] = '    <priority>' . $url['priority'] . '</priority>';
            $lines[] = '  </url>';
        }

        $lines[] = '</urlset>';

        return implode("\n", $lines) . "\n";
    }
}
