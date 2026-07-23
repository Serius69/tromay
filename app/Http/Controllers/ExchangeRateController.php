<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateController extends Controller
{
    private const ALLOWED     = ['usd', 'eur', 'clp', 'pen', 'brl', 'ars'];
    private const CACHE_TTL   = 300; // 5 minutes
    private const DISCLAIMER  = 'Tasas referenciales y estimadas. No representan cotizaciones oficiales de operación en Kapitalya.';

    // Centinela de caída de forex. OJO: cachear `null` NO funciona — Cache::get()
    // devuelve null tanto para "cacheado null" como para "no existe", así que el
    // próximo request lo trata como miss y re-pega a forex con timeout de 10-15s;
    // durante una caída eso cuelga CADA request y agota los workers de PHP-FPM.
    // El centinela se "recuerda" con TTL corto (se reintenta pronto cuando forex
    // vuelva) y el consumidor lo detecta para servir last-known-good o fallback
    // DB sin volver a pegar.
    private const UNAVAILABLE = ['unavailable' => true];
    private const FAILURE_TTL = 45; // seconds — reintento rápido tras una caída

    // Last-known-good del feed externo: última respuesta REAL de forex, para
    // servir durante caídas en vez del seed congelado de `cashes`. Retención
    // larga (7 días, mismo criterio que el oficial de RateService).
    private const LKG_CACHE_KEY = 'kap_ext_rates_lkg';
    private const LKG_CACHE_TTL = 604800; // 7 días

    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.exchange_api.url', ''), '/');
    }

    /**
     * Primary rates — fetched from external API, falls back to internal DB.
     */
    public function getRates(): JsonResponse
    {
        // No usar Cache::remember: si el closure devuelve null, cada request
        // posterior lo trata como cache-miss y re-pega a forex (ver UNAVAILABLE).
        $rates = Cache::get('kap_ext_rates');

        if ($rates === null) {
            $rates = $this->fetchExternalRates();

            if ($rates === null) {
                $rates = self::UNAVAILABLE;
                Cache::put('kap_ext_rates', $rates, self::FAILURE_TTL);
            } else {
                Cache::put('kap_ext_rates', $rates, self::CACHE_TTL);
                Cache::put(self::LKG_CACHE_KEY, $rates, self::LKG_CACHE_TTL);
            }
        }

        // `source` refleja lo que REALMENTE se sirve (antes reportaba 'external'
        // por el solo hecho de tener baseUrl, aunque sirviera el fallback DB):
        //   external       → respuesta viva de forex (o su cache de 300s)
        //   external_cache → forex caído, última respuesta real conocida
        //   internal       → fallback tabla `cashes` (seed)
        $source = 'external';

        if ($this->isUnavailable($rates)) {
            $rates  = Cache::get(self::LKG_CACHE_KEY);
            $source = 'external_cache';
        }

        // Fallback to internal Cash model data
        if (! $rates) {
            $rates = Cash::where('status', 1)
                ->whereIn('name', self::ALLOWED)
                ->select('id', 'name', 'buy', 'sell', 'oficial')
                ->orderBy('id')
                ->get()
                ->toArray();

            $source = 'internal';
        }

        return response()->json([
            'data'       => $rates,
            'source'     => $source,
            'disclaimer' => self::DISCLAIMER,
            'cached_at'  => now()->toIso8601String(),
        ]);
    }

    /**
     * Respuesta viva de forex normalizada, o null si forex no está disponible
     * (sin baseUrl, timeout, 5xx o shape inesperado). El caller decide qué
     * cachear — este método nunca toca el cache.
     */
    private function fetchExternalRates(): ?array
    {
        if (! $this->baseUrl) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->get("{$this->baseUrl}/exchange-rates/primary/");

            if ($response->successful()) {
                $data = $response->json();

                if (is_array($data) && $data !== []) {
                    return $this->normalizeScale($data);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('ExchangeRateController: API unavailable', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * ¿El valor cacheado es el centinela de "forex caído"?
     */
    private function isUnavailable(mixed $value): bool
    {
        return is_array($value) && ($value['unavailable'] ?? false) === true;
    }

    /**
     * Normaliza el feed crudo de forex-erp para que /api/ext-rates sea internamente
     * consistente con /api/rates (RateService): forex cotiza algunas divisas por lote
     * (ARS/CLP con scale_factor 1000 = "Bs por 1000 unidades"), mientras el resto del
     * ecosistema espera la tasa POR UNIDAD. Aquí se dividen buy/sell/official_rate entre
     * el scale_factor y se deja scale_factor=1, de modo que:
     *   - un consumidor que ignora scale_factor lee ya la tasa por unidad (antes leía ~1000x);
     *   - un consumidor que dividía por scale_factor sigue obteniendo el mismo valor
     *     (dividir por 1 es idempotente) → no se rompe a nadie por doble división.
     * SHAPE PRESERVADO: no se agregan ni quitan claves; solo cambian valores numéricos.
     *
     * NO se aplica el margen de spread comercial (EXCHANGE_MIN_SPREAD_PCT) que sí usa
     * RateService para la vitrina: /api/ext-rates es un feed de referencia que otros
     * consumidores (serguicv, exchange-alert / exchange-rate-alert-bolivia) esperan lo
     * más crudo posible (mid/mercado). Ensancharlo aquí distorsionaría esas lecturas de
     * "tasa de mercado"; el spread comercial es una decisión de presentación de Tromay,
     * no del feed. El scale_factor sí es una corrección de UNIDAD (no de margen), por eso
     * ese sí se aplica.
     */
    private function normalizeScale(array $rates): array
    {
        foreach ($rates as $code => $rate) {
            if (! is_array($rate)) {
                continue; // metadatos u otras claves no-divisa: intactas
            }

            $scale = (float) ($rate['scale_factor'] ?? 1);
            if ($scale <= 0 || $scale == 1.0) {
                continue; // sin factor de escala real: se deja crudo (incl. tipos/strings)
            }

            foreach (['buy_rate', 'sell_rate', 'official_rate'] as $field) {
                if (isset($rate[$field]) && is_numeric($rate[$field])) {
                    $rate[$field] = (float) $rate[$field] / $scale;
                }
            }

            // Ya aplicado: normalizar a 1 para que sea idempotente aguas abajo.
            $rate['scale_factor'] = 1;

            $rates[$code] = $rate;
        }

        return $rates;
    }

    /**
     * Multi-source comparative rates for a single currency.
     */
    public function getSources(string $currency = 'USD'): JsonResponse
    {
        $currency = strtolower($currency);

        if (! in_array($currency, self::ALLOWED)) {
            return response()->json(['error' => 'Currency not supported.'], 422);
        }

        // Mismo patrón anti-estampida que getRates(): un null cacheado se ve
        // igual que un miss, así que la caída se recuerda con el centinela
        // (TTL corto) en vez de re-pegar a forex (timeout 15s) en cada request.
        $data = Cache::get("kap_ext_sources_{$currency}");

        if ($data === null) {
            $data = $this->fetchExternalSources($currency);

            if ($data === null) {
                $data = self::UNAVAILABLE;
                Cache::put("kap_ext_sources_{$currency}", $data, self::FAILURE_TTL);
            } else {
                Cache::put("kap_ext_sources_{$currency}", $data, self::CACHE_TTL);
            }
        }

        if ($this->isUnavailable($data)) {
            $data = null; // sin fallback multi-fuente: se reporta vacío, sin re-pegar
        }

        return response()->json([
            'data'       => $data,
            'currency'   => strtoupper($currency),
            'disclaimer' => self::DISCLAIMER,
        ]);
    }

    /**
     * Comparativa multi-fuente viva de forex, o null si no está disponible.
     * Nunca toca el cache (el caller decide).
     */
    private function fetchExternalSources(string $currency): ?array
    {
        if (! $this->baseUrl) {
            return null;
        }

        try {
            $response = Http::timeout(15)
                ->get("{$this->baseUrl}/exchange-rates/sources-live/", [
                    'currency' => strtoupper($currency),
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return is_array($data) && $data !== [] ? $data : null;
            }
        } catch (\Throwable $e) {
            Log::warning('ExchangeRateController: sources API unavailable', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Calculate conversion using external API.
     */
    public function calculate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount'           => ['required', 'numeric', 'min:0.01', 'max:10000000'],
            'currency_from'    => ['required', 'string', 'size:3'],
            'currency_to'      => ['nullable', 'string', 'size:3'],
            'transaction_type' => ['required', 'in:buy,sell'],
        ]);

        $validated['currency_to'] ??= 'BOB';

        if ($this->baseUrl) {
            try {
                // forex-erp compara transaction_type contra 'BUY'/'SELL' EN
                // MAYÚSCULA (rates/exchange_rate_service.py): proxear el 'buy'
                // minúscula que valida Tromay hacía caer TODO al sell_rate.
                $payload = $validated;
                $payload['transaction_type'] = strtoupper($validated['transaction_type']);

                $response = Http::timeout(10)
                    ->post("{$this->baseUrl}/exchange-rates/calculate/", $payload);

                if ($response->successful()) {
                    $result = $response->json();

                    if (is_array($result) && isset($result['amount_to'], $result['rate'])) {
                        // forex cotiza ARS/CLP por lote (scale_factor 1000): su
                        // rate/amount_to vienen en "Bs por lote" → dividir a la
                        // tasa POR UNIDAD, mismo criterio que normalizeScale().
                        // Sin esto, ARS 100 "buy" devolvía un monto ~1000x.
                        $scale = (float) ($result['scale_factor'] ?? 1);
                        if ($scale <= 0) {
                            $scale = 1.0;
                        }

                        $rate = (float) $result['rate'] / $scale;

                        // Shape unificado con el fallback local de abajo
                        // (`result`, no `amount_to`) para que el consumidor no
                        // dependa de qué rama respondió.
                        return response()->json([
                            'amount'           => (float) $validated['amount'],
                            'currency_from'    => strtoupper($validated['currency_from']),
                            'currency_to'      => $validated['currency_to'],
                            'rate'             => $rate,
                            'result'           => round((float) $result['amount_to'] / $scale, 4),
                            'transaction_type' => $validated['transaction_type'],
                            'source'           => 'external',
                            'disclaimer'       => self::DISCLAIMER,
                        ]);
                    }

                    Log::warning('ExchangeRateController: calculate devolvió shape inesperado, usando fallback local');
                }
            } catch (\Throwable $e) {
                Log::warning('ExchangeRateController: calculate API unavailable', ['error' => $e->getMessage()]);
            }
        }

        // Fallback: calculate locally from internal DB rates
        $cash = Cash::where('name', strtolower($validated['currency_from']))
                    ->where('status', 1)
                    ->first();

        if (! $cash) {
            return response()->json(['error' => 'Divisa no disponible.'], 404);
        }

        $rate   = $validated['transaction_type'] === 'buy' ? $cash->buy : $cash->sell;
        $result = round($validated['amount'] * $rate, 4);

        return response()->json([
            'amount'           => $validated['amount'],
            'currency_from'    => strtoupper($validated['currency_from']),
            'currency_to'      => $validated['currency_to'],
            'rate'             => $rate,
            'result'           => $result,
            'transaction_type' => $validated['transaction_type'],
            'source'           => 'internal_fallback',
            'disclaimer'       => self::DISCLAIMER,
        ]);
    }
}
