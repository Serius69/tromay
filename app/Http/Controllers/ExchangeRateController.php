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
        $rates = Cache::remember('kap_ext_rates', self::CACHE_TTL, function () {
            if (! $this->baseUrl) {
                return null;
            }

            try {
                $response = Http::timeout(10)
                    ->get("{$this->baseUrl}/exchange-rates/primary/");

                if ($response->successful()) {
                    $data = $response->json();

                    return is_array($data) ? $this->normalizeScale($data) : $data;
                }
            } catch (\Throwable $e) {
                Log::warning('ExchangeRateController: API unavailable', ['error' => $e->getMessage()]);
            }

            return null;
        });

        // Fallback to internal Cash model data
        if (! $rates) {
            $rates = Cash::where('status', 1)
                ->whereIn('name', self::ALLOWED)
                ->select('id', 'name', 'buy', 'sell', 'oficial')
                ->orderBy('id')
                ->get()
                ->toArray();
        }

        return response()->json([
            'data'       => $rates,
            'source'     => $this->baseUrl ? 'external' : 'internal',
            'disclaimer' => self::DISCLAIMER,
            'cached_at'  => now()->toIso8601String(),
        ]);
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

        $data = Cache::remember("kap_ext_sources_{$currency}", self::CACHE_TTL, function () use ($currency) {
            if (! $this->baseUrl) {
                return null;
            }

            try {
                return Http::timeout(15)
                    ->get("{$this->baseUrl}/exchange-rates/sources-live/", [
                        'currency' => strtoupper($currency),
                    ])
                    ->json();
            } catch (\Throwable $e) {
                Log::warning('ExchangeRateController: sources API unavailable', ['error' => $e->getMessage()]);
                return null;
            }
        });

        return response()->json([
            'data'       => $data,
            'currency'   => strtoupper($currency),
            'disclaimer' => self::DISCLAIMER,
        ]);
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
                $response = Http::timeout(10)
                    ->post("{$this->baseUrl}/exchange-rates/calculate/", $validated);

                if ($response->successful()) {
                    $result = $response->json();
                    $result['disclaimer'] = self::DISCLAIMER;
                    return response()->json($result);
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
