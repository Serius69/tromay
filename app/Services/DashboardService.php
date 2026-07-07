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
        return [
            'total_tx'         => Transaction::count(),
            'today_tx'         => Transaction::today()->count(),
            'month_tx'         => Transaction::thisMonth()->count(),
            'month_volume_bob' => $this->monthVolumeBoB(),
            'new_clients'      => $this->newClientsThisMonth(),
            'top_currency'     => $this->topCurrencyThisMonth(),
        ];
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
        return (float) Transaction::thisMonth()
            ->where('type', 1)
            ->sum('amount2');
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
        $rows = DB::table('transactions')
            ->selectRaw('DATE(created_at) as day, ROUND(SUM(amount2), 2) as volume')
            ->where('type', 1)
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
                         COUNT(*) as tx_count, ROUND(SUM(transactions.amount2), 2) as volume')
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
