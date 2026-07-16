<?php

namespace App\Http\Controllers;

use App\Services\RateService;
use Illuminate\Support\Collection;

/**
 * Landings de SEO programático — capturan búsquedas de alto volumen del rubro
 * ("dólar hoy Bolivia", "tipo de cambio hoy", "convertidor de divisas") y las
 * canalizan hacia la sucursal (CTA WhatsApp). Todas las tasas provienen de
 * RateService (forex-erp con fallback DB); no se fabrica ningún dato.
 */
class SeoLandingController extends Controller
{
    /**
     * Metadatos editoriales por divisa (nombre en español, forma corta y país).
     * Se usa para titulares/descripciones y para el mapa de banderas de la vista.
     */
    public const CURRENCY_META = [
        'usd' => ['label' => 'Dólar Estadounidense', 'short' => 'dólar',          'country' => 'Estados Unidos', 'flag' => 'estados unidos.png'],
        'eur' => ['label' => 'Euro',                 'short' => 'euro',           'country' => 'la Zona Euro',   'flag' => 'union europea.png'],
        'brl' => ['label' => 'Real Brasileño',       'short' => 'real brasileño', 'country' => 'Brasil',         'flag' => 'brazil.png'],
        'ars' => ['label' => 'Peso Argentino',       'short' => 'peso argentino', 'country' => 'Argentina',      'flag' => 'argentina.png'],
        'pen' => ['label' => 'Sol Peruano',          'short' => 'sol peruano',    'country' => 'Perú',           'flag' => 'peru.png'],
        'clp' => ['label' => 'Peso Chileno',         'short' => 'peso chileno',   'country' => 'Chile',          'flag' => 'chile.png'],
    ];

    public function __construct(private RateService $rates) {}

    /**
     * /dolar-hoy-bolivia — landing del dólar hoy con la tasa USD/BOB en vivo,
     * comparación con las demás divisas y contenido de apoyo.
     */
    public function dolarHoy()
    {
        $cashes = $this->rates->getActiveRatesWithAll();
        $dollar = $this->find($cashes, 'usd');

        abort_if(! $dollar, 404);

        return view('seo.dolar-hoy', [
            'cashes' => $cashes,   // requerido por el footer del layout master
            'dollar' => $dollar,   // requerido por el pill del header
            'cash'   => $dollar,
            'meta'   => self::CURRENCY_META,
        ]);
    }

    /**
     * /convertidor/{par} — conversor BOB↔divisa. Acepta "usd", "usd-bob" y
     * "bob-usd"; redirige (301) a la forma canónica "usd-bob" y responde 404
     * ante pares inválidos.
     */
    public function convertidor(string $par)
    {
        $slug   = strtolower(trim($par));
        $tokens = array_values(array_filter(explode('-', $slug), fn ($t) => $t !== ''));
        $codes  = array_values(array_filter($tokens, fn ($t) => $t !== 'bob'));

        // Debe quedar exactamente una divisa extranjera (el otro lado es BOB).
        abort_if(count($codes) !== 1, 404);

        $code = $codes[0];
        abort_unless(in_array($code, RateService::ALLOWED, true), 404);

        $cashes = $this->rates->getActiveRatesWithAll();
        $cash   = $this->find($cashes, $code);
        abort_if(! $cash, 404);

        // Slug canónico `usd-bob`: consolida el SEO y evita contenido duplicado
        // entre /convertidor/usd, /convertidor/usd-bob y /convertidor/bob-usd.
        $canonical = $code . '-bob';
        if ($slug !== $canonical) {
            return redirect()->route('seo.convertidor', ['par' => $canonical], 301);
        }

        // Pestaña por defecto del simulador: si el usuario parte de BOB, "compra"
        // divisa; si parte de la divisa, "vende". El canónico usd-bob => vender.
        $direction = ($tokens[0] ?? '') === 'bob' ? 'buy' : 'sell';

        return view('seo.convertidor', [
            'cashes'    => $cashes,   // footer del layout
            'dollar'    => $this->find($cashes, 'usd'), // pill del header
            'cash'      => $cash,
            'code'      => $code,
            'meta'      => self::CURRENCY_META,
            'direction' => $direction,
        ]);
    }

    /**
     * Busca una divisa por código con matching robusto: el accessor `name` del
     * modelo Cash devuelve ucwords(), así que un firstWhere('name','usd') fallaría.
     */
    private function find(Collection $cashes, string $code)
    {
        $code = strtolower($code);

        return $cashes->first(fn ($c) => strtolower($c->name) === $code);
    }
}
