<?php

namespace App\Services;

use App\Models\Cash;
use App\Models\CashRate;
use Illuminate\Contracts\Cache\Lock;
use Illuminate\Contracts\Cache\LockProvider;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RateService
{
    public const ALLOWED = ['usd', 'eur', 'clp', 'pen', 'brl', 'ars'];

    private const CACHE_KEY = 'kap_active_rates';
    private const CACHE_TTL = 300; // seconds — tasas cambian cada hora, 5min es suficiente

    // Fallback del oficial REAL cuando forex-erp /official/ no responde: se sirve
    // el ÚLTIMO valor conocido (nunca un hardcode). Retención larga (7 días) para
    // aguantar una caída extendida sin perder el dato; el fetch en sí sigue
    // corriendo cada CACHE_TTL (300s) como el resto del overlay.
    private const OFFICIAL_CACHE_PREFIX = 'kap_official_live_';
    private const OFFICIAL_CACHE_TTL = 604800; // 7 días

    // Last-known-good de buy/sell del overlay (mismo criterio que el oficial):
    // ante una caída de forex se sirve la ÚLTIMA tasa REAL conocida en vez del
    // seed congelado de `cashes`, marcada rate_source='cache' — así las vistas
    // pueden mostrar un badge honesto ("Caché", no "En vivo") y persistHistory
    // (que solo graba rate_source='forex') no la re-persiste.
    private const LKG_CACHE_PREFIX = 'kap_rates_live_';
    private const LKG_CACHE_TTL = 604800; // 7 días

    public function getActiveRates(): Collection
    {
        // Deriva el subconjunto de columnas públicas (id/name/buy/sell/oficial) desde
        // la ÚNICA fuente overlaid: getActiveRatesWithAll(). Antes cada método corría
        // su propio overlayForex() → un round-trip HTTP a forex por cada cache key
        // (kap_active_rates y kap_active_rates_full). Ahora hay UN solo fetch + UN solo
        // persistHistory (los hace getActiveRatesWithAll); esto solo proyecta columnas,
        // preservando el shape público exacto de /api/rates.
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->getActiveRatesWithAll()->map(function (Cash $cash) {
                $subset = new Cash();
                $subset->setRawAttributes([
                    'id'      => $cash->id,
                    'name'    => $cash->getRawOriginal('name'), // crudo minúscula; el accessor lo capitaliza al serializar (igual que antes)
                    'buy'     => $cash->buy,
                    'sell'    => $cash->sell,
                    'oficial' => $cash->oficial,
                ], true);

                // Conservar la marca rate_source cuando el overlay real de forex la
                // puso (se serializaba antes en /api/rates si forex respondía).
                $source = $cash->getAttribute('rate_source');
                if ($source !== null) {
                    $subset->setAttribute('rate_source', $source);
                }

                return $subset;
            });
        });
    }

    public function getActiveRatesWithAll(): Collection
    {
        $cached = Cache::get('kap_active_rates_full');
        if ($cached !== null) {
            return $cached;
        }

        // Anti-estampida: bajo N cache-miss concurrentes (workers PHP-FPM), solo
        // UN worker refresca contra forex; el resto espera brevemente (block 3s)
        // a que el resultado quede cacheado. Si el refresco tarda más que la
        // espera, se degrada a last-known-good/seed SIN pegar a forex — nunca
        // N× (1 primary + pool de oficiales) en paralelo contra forex-erp.
        $lock = $this->refreshLock();

        if ($lock === null) {
            // Store sin soporte de locks: comportamiento previo (sin candado).
            return Cache::remember('kap_active_rates_full', self::CACHE_TTL, fn () => $this->buildRates());
        }

        try {
            return $lock->block(3, function () {
                // Double-check bajo el candado: otro worker pudo haber poblado
                // el cache mientras esperábamos — remember lo detecta sin HTTP.
                return Cache::remember('kap_active_rates_full', self::CACHE_TTL, fn () => $this->buildRates());
            });
        } catch (LockTimeoutException) {
            return $this->applyLastKnownGood($this->activeCashes());
        }
    }

    /**
     * Candado del refresco de tasas, o null si el cache store activo no
     * implementa locks (p.ej. `file` en Laravel 9) — en ese caso se degrada
     * al comportamiento sin candado en vez de reventar el request.
     */
    private function refreshLock(): ?Lock
    {
        return Cache::getStore() instanceof LockProvider
            ? Cache::lock('kap_rates_full_refresh_lock', 15)
            : null;
    }

    private function buildRates(): Collection
    {
        $overlaid = $this->overlayForex($this->activeCashes());
        $this->persistHistory($overlaid);

        return $overlaid;
    }

    private function activeCashes(): Collection
    {
        return Cash::active()
            ->allowed(self::ALLOWED)
            ->orderBy('id')
            ->get();
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
        // OJO: el accessor `name` de Cash capitaliza el valor (ars → "Ars"), así que
        // un firstWhere('name', 'ars') NUNCA calzaría (compararía "Ars" === "ars") y
        // la resolución por nombre siempre devolvía null → /api/calculator daba 404
        // para toda divisa. Se normalizan ambos lados a minúscula para calzar de verdad.
        $needle = strtolower(trim($name));

        return $this->getActiveRates()->first(
            fn (Cash $cash) => strtolower((string) $cash->name) === $needle
        );
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
        // Cache::add es atómico (add/SETNX): inserta y devuelve true SOLO si la clave
        // no existía; si ya existe devuelve false sin escribir. Reemplaza al
        // get()+put() no atómico, que bajo dos cache-miss concurrentes dejaba pasar a
        // ambos y duplicaba snapshots en cash_rates. Misma semántica de candado y TTL.
        if (! Cache::add('kap_rates_snapshot_lock', 1, self::CACHE_TTL - 5)) {
            return;
        }

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
    //
    // buy/sell salen de /exchange-rates/primary/ (mid del paralelo); oficial
    // sale de /official/ (TCO/BCB real) — son DOS endpoints distintos de
    // forex-erp, nunca el mismo campo.
    // ------------------------------------------------------------------

    private function overlayForex(Collection $cashes): Collection
    {
        $forex = $this->fetchForexPrimary();

        if (! $forex) {
            // forex caído: última tasa REAL conocida (rate_source='cache') antes
            // que el seed congelado; si tampoco hay caché, queda la tabla `cashes`.
            return $this->applyLastKnownGood($cashes);
        }

        // Oficiales EN PARALELO (Http::pool) solo para las divisas con fila
        // válida en forex. Antes eran hasta 6 GETs secuenciales (uno por divisa)
        // tras el GET primario: hasta 7 round-trips en SERIE por cache-miss.
        $codes = [];
        foreach ($cashes as $cash) {
            $code = strtoupper($cash->name);
            if (is_array($forex[$code] ?? null)) {
                $codes[] = $code;
            }
        }

        $officials = $this->fetchForexOfficialBatch($codes);

        foreach ($cashes as $cash) {
            $code = strtoupper($cash->name);
            $rate = $forex[$code] ?? null;

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

            $buy  = isset($rate['buy_rate'])  ? (float) $rate['buy_rate']  / $scale : 0.0;
            $sell = isset($rate['sell_rate']) ? (float) $rate['sell_rate'] / $scale : 0.0;

            // Guard: si forex devuelve basura (0/negativo/faltante) por un glitch,
            // NO se sobrepone — se conserva el fallback sembrado en vez de mostrar
            // 0 al público y grabar un snapshot inválido en `cash_rates`.
            if ($buy <= 0 || $sell <= 0) {
                continue;
            }

            $cash->buy  = $buy;
            $cash->sell = $sell;

            // FIX oficial mal etiquetado: `$rate['official_rate']` aquí es el MID
            // del paralelo (misma fila /exchange-rates/primary/ que buy/sell), no
            // el TCO/BCB, a pesar del nombre. El oficial REAL se pide al endpoint
            // dedicado /api/rates/official/ de forex-erp (pool de arriba).
            $off = $officials[$code] ?? null;
            if ($off !== null && $off > 0) {
                $cash->oficial = $off;
            } elseif ($code !== 'USD') {
                // forex-erp solo refresca el oficial de USD/BOB: para el resto de
                // divisas el "oficial" sembrado en `cashes` es un número congelado
                // que NO debe mostrarse como oficial vigente. Sin dato real →
                // null (las vistas pintan '—' y la API emite null; nunca un
                // valor inventado). USD conserva su fallback intacto.
                $cash->oficial = null;
            }

            $this->applyMinSpread($cash);

            $cash->setAttribute('rate_source', 'forex');

            // Snapshot last-known-good con los valores finales mostrados
            // (post-spread), para servirlos durante una futura caída de forex.
            Cache::put(self::LKG_CACHE_PREFIX.$code, [
                'buy'     => (float) $cash->buy,
                'sell'    => (float) $cash->sell,
                'oficial' => $cash->oficial !== null ? (float) $cash->oficial : null,
            ], self::LKG_CACHE_TTL);
        }

        return $cashes;
    }

    /**
     * Sobrepone el last-known-good cacheado (última respuesta REAL de forex)
     * sobre los `Cash` sembrados. Divisas sin caché quedan con el seed y sin
     * rate_source (las vistas las tratan como "Referencial").
     */
    private function applyLastKnownGood(Collection $cashes): Collection
    {
        foreach ($cashes as $cash) {
            $lkg = Cache::get(self::LKG_CACHE_PREFIX.strtoupper($cash->name));

            if (! is_array($lkg) || (float) ($lkg['buy'] ?? 0) <= 0 || (float) ($lkg['sell'] ?? 0) <= 0) {
                continue; // sin last-known-good válido → fallback sembrado
            }

            $cash->buy  = (float) $lkg['buy'];
            $cash->sell = (float) $lkg['sell'];

            if ((float) ($lkg['oficial'] ?? 0) > 0) {
                $cash->oficial = (float) $lkg['oficial'];
            } elseif (strtoupper($cash->name) !== 'USD') {
                // Mismo criterio que el overlay: sin oficial REAL conocido para
                // una divisa no-USD, no mostrar el seed congelado como oficial.
                $cash->oficial = null;
            }

            // 'cache': tasa real pero NO en vivo — nunca 'forex' (badge honesto
            // y persistHistory no graba snapshots repetidos de la misma tasa).
            $cash->setAttribute('rate_source', 'cache');
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
     *
     * OJO: `official_rate` en esta respuesta es el MID del paralelo (misma
     * fila primaria que buy/sell), NO el TCO/BCB — para el oficial real usar
     * `fetchForexOfficial()`.
     */
    private function fetchForexPrimary(): ?array
    {
        $baseUrl = rtrim((string) config('services.exchange_api.url', ''), '/');

        if ($baseUrl === '') {
            return null;
        }

        try {
            // Timeout corto: este fetch corre dentro del request de página (en el
            // cache-miss); 3s acota el peor caso sin agotar los workers de FPM.
            $response = Http::timeout(3)->get("{$baseUrl}/exchange-rates/primary/");

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

    /**
     * Oficial REAL (BCB/TCO) por divisa, EN PARALELO (Http::pool) contra el
     * endpoint dedicado y estable de forex-erp `/api/rates/official/?currency=...`
     * — distinto de `/exchange-rates/primary/`, cuyo campo homónimo es el mid
     * del paralelo. Un solo round-trip de latencia para todas las divisas
     * (antes: un GET secuencial por divisa), timeout corto por request.
     *
     * Semántica por divisa idéntica al fetch individual previo: si forex-erp no
     * responde (timeout/5xx) o devuelve un shape inesperado o una divisa
     * distinta a la pedida, degrada al ÚLTIMO valor cacheado (`cachedOfficial()`)
     * — NUNCA a un hardcode viejo. Si tampoco hay caché, null.
     *
     * @param  array<int, string>  $codes
     * @return array<string, float|null>
     */
    private function fetchForexOfficialBatch(array $codes): array
    {
        if ($codes === []) {
            return [];
        }

        $baseUrl = rtrim((string) config('services.exchange_api.url', ''), '/');

        if ($baseUrl === '') {
            return array_combine($codes, array_map(fn (string $c) => $this->cachedOfficial($c), $codes));
        }

        try {
            $responses = Http::pool(fn (Pool $pool) => array_map(
                fn (string $code) => $pool->as($code)
                    ->timeout(3)
                    ->get("{$baseUrl}/official/", ['currency' => $code]),
                $codes
            ));
        } catch (\Throwable $e) {
            Log::warning('RateService: pool de oficiales de forex falló, usando últimos valores cacheados', [
                'error' => $e->getMessage(),
            ]);
            $responses = [];
        }

        $officials = [];
        foreach ($codes as $code) {
            $officials[$code] = $this->parseOfficial($code, $responses[$code] ?? null);
        }

        return $officials;
    }

    /**
     * Interpreta la respuesta del pool para una divisa (Response, excepción de
     * conexión, o null si el pool entero falló) con la misma degradación que
     * el fetch individual histórico: éxito → cachea 7 días y devuelve; fallo →
     * último valor cacheado o null.
     */
    private function parseOfficial(string $currency, mixed $response): ?float
    {
        if ($response instanceof Response && $response->successful()) {
            $data = $response->json();

            $currencyMatches = ! isset($data['currency']) || $data['currency'] === $currency;

            if (is_array($data) && isset($data['official_rate']) && $currencyMatches) {
                $rate = (float) $data['official_rate'];

                if ($rate > 0) {
                    Cache::put(self::OFFICIAL_CACHE_PREFIX.$currency, $rate, self::OFFICIAL_CACHE_TTL);

                    return $rate;
                }
            }

            Log::warning('RateService: forex official devolvió shape inesperado, usando último valor cacheado', [
                'currency' => $currency,
            ]);
        } elseif ($response instanceof \Throwable) {
            Log::warning('RateService: forex official no disponible, usando último valor cacheado', [
                'currency' => $currency,
                'error'    => $response->getMessage(),
            ]);
        }

        return $this->cachedOfficial($currency);
    }

    /**
     * Último oficial REAL conocido para `$currency` (cache de larga duración,
     * ver OFFICIAL_CACHE_TTL). Nunca el histórico sembrado de `cashes`.
     */
    private function cachedOfficial(string $currency): ?float
    {
        return Cache::get(self::OFFICIAL_CACHE_PREFIX.$currency);
    }
}
