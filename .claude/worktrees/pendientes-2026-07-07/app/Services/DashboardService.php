<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private const CACHE_TTL = 300; // 5 minutes

    public function summary(): array
    {
        // Cacheado: se sirve en cada carga de /admin (página de aterrizaje
        // post-login); invalidate() ya olvidaba esta clave.
        return Cache::remember('kap_dashboard_summary', self::CACHE_TTL, function () {
            return [
                'total_tx'         => Transaction::count(),
                'today_tx'         => Transaction::today()->count(),
                'month_tx'         => Transaction::thisMonth()->count(),
                'month_volume_bob' => $this->monthVolumeBoB(),
                'new_clients'      => $this->newClientsThisMonth(),
                'top_currency'     => $this->topCurrencyThisMonth(),
            ];
        });
    }

    public function apiData(): array
    {
        return Cache::remember('kap_analytics_api', self::CACHE_TTL, function () {
            return [
                'kpis' => [
                    'today_tx'         => Transaction::today()->count(),
                    'month_tx'         => Transaction::thisMonth()->count(),
                    'month_volume_bob' => $this->monthVolumeBoB(),
                    'new_clients'      => $this->newClientsThisMonth(),
                    'top_currency'     => $this->topCurrencyThisMonth() ?? '—',
                ],
                'chart_by_currency' => $this->chartByCurrency(),
                'chart_volume'      => $this->chartVolumeByDay(),
                'top_clients'       => $this->topClients(),
            ];
        });
    }

    public function invalidate(): void
    {
        Cache::forget('kap_dashboard_summary');
        Cache::forget('kap_analytics_api');
    }

    private function monthVolumeBoB(): float
    {
        // El lado BOB depende del tipo: TYPE_BUY (1) los BOB entran como amount1;
        // TYPE_SELL (2) los BOB salen como amount2. Sumar amount2 de type 1
        // mezclaba divisa extranjera en un KPI "BOB".
        return (float) Transaction::thisMonth()
            ->sum(DB::raw('CASE WHEN type = 1 THEN amount1 ELSE amount2 END'));
    }

    private function newClientsThisMonth(): int
    {
        return Client::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    private function topCurrencyThisMonth(): ?string
    {
        $result = DB::table('transactions')
            ->join('cashes', 'transactions.cash1_id', '=', 'cashes.id')
            ->selectRaw('cashes.name, COUNT(*) as total')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->groupBy('cashes.name')
            ->orderByDesc('total')
            ->first();

        return $result?->name;
    }

    private function chartByCurrency(int $days = 7): array
    {
        $rows = DB::table('transactions')
            ->join('cashes', 'transactions.cash1_id', '=', 'cashes.id')
            ->selectRaw('UPPER(cashes.name) as currency, COUNT(*) as total')
            ->where('transactions.created_at', '>=', now()->subDays($days)->startOfDay())
            ->groupBy('cashes.name')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $rows->pluck('currency')->values()->all(),
            'data'   => $rows->pluck('total')->values()->all(),
        ];
    }

    private function chartVolumeByDay(int $days = 30): array
    {
        // Volumen diario en BOB: amount1 para TYPE_BUY, amount2 para TYPE_SELL.
        $rows = DB::table('transactions')
            ->selectRaw('DATE(created_at) as day, ROUND(SUM(CASE WHEN type = 1 THEN amount1 ELSE amount2 END), 2) as volume')
            ->where('created_at', '>=', now()->subDays($days)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return [
            'labels' => $rows->pluck('day')->values()->all(),
            'data'   => $rows->pluck('volume')->map(fn($v) => (float) $v)->values()->all(),
        ];
    }

    private function topClients(int $limit = 5): array
    {
        return DB::table('transactions')
            ->join('clients', 'transactions.client_id', '=', 'clients.id')
            ->selectRaw('clients.ci, clients.name, clients.lastname,
                         COUNT(*) as tx_count,
                         ROUND(SUM(CASE WHEN transactions.type = 1 THEN transactions.amount1 ELSE transactions.amount2 END), 2) as volume')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->groupBy('clients.ci', 'clients.name', 'clients.lastname')
            ->orderByDesc('tx_count')
            ->limit($limit)
            ->get()
            ->map(fn($r) => (array) $r)
            ->values()
            ->all();
    }
}
