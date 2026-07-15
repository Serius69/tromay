<?php

namespace App\Services;

use App\Models\Cash;
use App\Models\CashRate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RateService
{
    public const ALLOWED = ['usd', 'eur', 'clp', 'pen', 'brl', 'ars'];

    private const CACHE_KEY = 'kap_active_rates';
    private const CACHE_TTL = 300; // seconds — tasas cambian cada hora, 5min es suficiente

    public function getActiveRates(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $cashes = Cash::active()
                ->allowed(self::ALLOWED)
                ->select('id', 'name', 'buy', 'sell', 'oficial')
                ->orderBy('id')
                ->get();

            $overlaid = $this->overlayForex($cashes);
            $this->persistHistory($overlaid);

            return $overlaid;
        });
    }

    public function getActiveRatesWithAll(): Collection
    {
        return Cache::remember('kap_active_rates_full', self::CACHE_TTL, function () {
            $cashes = Cash::active()
                ->allowed(self::ALLOWED)
                ->orderBy('id')
                ->get();

            $overlaid = $this->overlayForex($cashes);
            $this->persistHistory($overlaid);

            return $overlaid;
        });
    }

    /**
     * Devuelve la divisa por id CON el overlay de forex aplicado (todas las
     * columnas). Los endpoints de detalle (`dinero.show`, API `rates/{cash}`)
     * deben usar esto para mostrar la MISMA tasa que `/api/rates`, no el `Cash`
     * crudo del route-binding (que trae los valores sembrados/rancios).
     */
    public function getFullRateById(int $id): ?Cash
    {
        return $this->getActiveRatesWithAll()->firstWhere('id', $id);
    }

    public function getRateById(int $id): ?Cash
    {
        return $this->getActiveRates()->firstWhere('id', $id);
    }

    public function getRateByName(string $name): ?Cash
    {
        return $this->getActiveRates()->firstWhere('name', strtolower($name));
    }

    public function invalidate(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('kap_active_rates_full');
        Cache::forget('kap_ext_rates');
    }

    /**
     * Persiste un snapshot en `cash_rates` cuando el overlay de forex produjo
     * valores nuevos (distintos al último snapshot). Reactiva el historial que
     * estaba congelado: el overlay in-memory nunca tocaba la tabla, así que el
     * CashObserver (que dispara con save()) nunca se ejecutaba.
     *
     * Solo corre en el cache-miss (~cada CACHE_TTL) y con un candado de cache
     * para no duplicar entre los dos cache keys (rates / rates_full) ni bajo
     * estampida. No persiste el fallback sembrado (solo divisas con overlay real).
     */
    private function persistHistory(Collection $cashes): void
    {
        if (Cache::get('kap_rates_snapshot_lock')) {
            return;
        }
        Cache::put('kap_rates_snapshot_lock', 1, self::CACHE_TTL - 5);

        foreach ($cashes as $cash) {
            if ($cash->getAttribute('rate_source') !== 'forex') {
                continue; // fallback sembrado: no ensuciar el historial
            }

            $last = CashRate::where('cash_id', $cash->id)
                ->orderByDesc('created_at')
                ->first();

            $unchanged = $last
                && abs((float) $last->buy - (float) $cash->buy) < 1e-6
                && abs((float) $last->sell - (float) $cash->sell) < 1e-6
                && abs((float) $last->oficial - (float) $cash->oficial) < 1e-6;

            if ($unchanged) {
                continue;
            }

            CashRate::create([
                'cash_id' => $cash->id,
                'buy'     => $cash->buy,
                'sell'    => $cash->sell,
                'oficial' => $cash->oficial,
            ]);
        }
    }

    public function calculate(float $amount, string $currency, string $type): ?array
    {
        $cash = $this->getRateByName($currency);

        if (! $cash) {
            return null;
        }

        $rate   = $type === 'buy' ? (float) $cash->buy : (float) $cash->sell;
        $result = round($amount * $rate, 4);

        return [
            'amount'           => $amount,
            'currency'         => strtoupper($currency),
            'transaction_type' => $type,
            'rate'             => $rate,
            'result'           => $result,
        ];
    }

    // ------------------------------------------------------------------
    // Forex-ERP integration — las tasas mostradas al público provienen de
    // forex-erp (EXCHANGE_API_URL). La tabla `cashes` sembrada actúa solo
    // como fallback offline cuando forex no responde. Se conserva el id/ruta
    // de cada divisa (dinero.show) y solo se sobreponen buy/sell/oficial.
    // ------------------------------------------------------------------

    private function overlayForex(Collection $cashes): Collection
    {
        $forex = $this->fetchForexPrimary();

        if (! $forex) {
            return $cashes; // fallback: valores de la tabla `cashes`
        }

        foreach ($cashes as $cash) {
            $rate = $forex[strtoupper($cash->name)] ?? null;

            if (! is_array($rate)) {
                continue;
            }

            // forex-erp cotiza algunas divisas por lote (ARS, CLP → scale_factor 1000):
            // sus buy_rate/sell_rate son "Bs por 1000 unidades". La tabla `cashes` y la
            // UI de Tromay usan la tasa POR UNIDAD (p.ej. ARS ≈ 0.0068), así que hay que
            // dividir entre el factor de escala. Sin esto, ARS/CLP se mostrarían ~1000x.
            $scale = (float) ($rate['scale_factor'] ?? 1);
            if ($scale <= 0) {
                $scale = 1.0;
            }

            if (isset($rate['buy_rate']))       $cash->buy     = (float) $rate['buy_rate'] / $scale;
            if (isset($rate['sell_rate']))      $cash->sell    = (float) $rate['sell_rate'] / $scale;
            if (isset($rate['official_rate']))  $cash->oficial = (float) $rate['official_rate'] / $scale;

            $this->applyMinSpread($cash);

            $cash->setAttribute('rate_source', 'forex');
        }

        return $cashes;
    }

    /**
     * Garantiza un margen comercial mínimo entre compra y venta. forex-erp
     * expone algunas divisas (EUR/BRL/PEN/…) con feed de mercado casi sin
     * spread (~0.2%), lo que en una casa de cambio se ve como "sin ganancia".
     * Si el spread real de forex ya supera el mínimo (p.ej. USD ~8%) se respeta;
     * si no, se ensancha simétricamente alrededor del punto medio. Configurable
     * vía EXCHANGE_MIN_SPREAD_PCT (0 = desactivado, respeta forex tal cual).
     */
    private function applyMinSpread(Cash $cash): void
    {
        $minPct = (float) config('services.exchange_api.min_spread_pct', 0);

        $buy  = (float) $cash->buy;
        $sell = (float) $cash->sell;

        if ($minPct <= 0 || $buy <= 0 || $sell <= 0) {
            return;
        }

        $mid = ($buy + $sell) / 2;
        if ($mid <= 0) {
            return;
        }

        $currentPct = ($sell - $buy) / $mid * 100;
        if ($currentPct >= $minPct) {
            return; // forex ya trae un spread comercial suficiente
        }

        $half = ($minPct / 100) / 2;
        $cash->buy  = $mid * (1 - $half);
        $cash->sell = $mid * (1 + $half);
    }

    /**
     * Tasas primarias de forex-erp: dict por código de divisa
     * (USD/EUR/…) con buy_rate/sell_rate/official_rate. Base BOB.
     */
    private function fetchForexPrimary(): ?array
    {
        $baseUrl = rtrim((string) config('services.exchange_api.url', ''), '/');

        if ($baseUrl === '') {
            return null;
        }

        try {
            $response = Http::timeout(8)->get("{$baseUrl}/exchange-rates/primary/");

            if ($response->successful()) {
                $data = $response->json();
                return is_array($data) && $data !== [] ? $data : null;
            }
        } catch (\Throwable $e) {
            Log::warning('RateService: forex primary no disponible, usando fallback DB', [
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }
}
