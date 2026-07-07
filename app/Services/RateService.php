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
        return $this->getActiveRates()->firstWhere('name', strtolower($name));
    }

    public function invalidate(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('kap_active_rates_full');
        Cache::forget('kap_ext_rates');
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
}
