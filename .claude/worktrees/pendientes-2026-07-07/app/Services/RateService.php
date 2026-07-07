<?php

namespace App\Services;

use App\Models\Cash;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RateService
{
    public const ALLOWED = ['usd', 'eur', 'clp', 'pen', 'brl', 'ars'];

    private const CACHE_KEY = 'kap_active_rates';
    private const CACHE_TTL = 300; // seconds — tasas cambian cada hora, 5min es suficiente

    public function getActiveRates(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Cash::active()
                ->allowed(self::ALLOWED)
                ->select('id', 'name', 'buy', 'sell', 'oficial')
                ->orderBy('id')
                ->get();
        });
    }

    public function getActiveRatesWithAll(): Collection
    {
        return Cache::remember('kap_active_rates_full', self::CACHE_TTL, function () {
            return Cash::active()
                ->allowed(self::ALLOWED)
                ->orderBy('id')
                ->get();
        });
    }

    public function getRateById(int $id): ?Cash
    {
        return $this->getActiveRates()->firstWhere('id', $id);
    }

    public function getRateByName(string $name): ?Cash
    {
        // Comparar contra el valor crudo: el accessor de Cash::name aplica
        // ucwords(), por lo que firstWhere('name', 'usd') nunca coincide.
        $name = strtolower($name);

        return $this->getActiveRates()->first(
            fn (Cash $cash) => $cash->getRawOriginal('name') === $name
        );
    }

    public function invalidate(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('kap_active_rates_full');
        Cache::forget('kap_ext_rates');
    }

    /**
     * Calculadora pública. `$amount` es SIEMPRE el monto en divisa extranjera y
     * `$type` es perspectiva de la casa (igual que Quotation::type): 'buy' = la
     * casa compra la divisa (aplica `cashes.buy`), 'sell' = la casa la vende
     * (aplica `cashes.sell`). El resultado es el contravalor en BOB.
     */
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
}
