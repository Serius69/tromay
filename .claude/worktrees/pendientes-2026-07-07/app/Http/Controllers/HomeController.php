<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Latest;
use App\Models\Transaction;
use App\Services\DashboardService;
use App\Services\RateService;

class HomeController extends Controller
{
    public function __construct(
        private RateService $rates,
        private DashboardService $dashboard,
    ) {}

    public function __invoke()
    {
        return view('index', array_merge($this->sharedData(), [
            'latests' => Latest::published()->orderByDesc('date_publication')->limit(3)->get(),
        ]));
    }

    public function admin()
    {
        $summary = $this->dashboard->summary();

        return view('admin.home', [
            'cashes'       => Cash::orderBy('id')->paginate(10),
            'latests'      => Latest::orderByDesc('date_publication')->paginate(3),
            'total_tx'     => $summary['total_tx'],
            'today_tx'     => $summary['today_tx'],
            'month_tx'     => $summary['month_tx'],
            'transactions' => Transaction::with(['client', 'cash1', 'cash2'])->orderByDesc('id')->paginate(10),
        ]);
    }

    public function about()
    {
        return view('about', $this->sharedData());
    }

    public function privacy()
    {
        return view('privacy', $this->sharedData());
    }

    public function terms()
    {
        return view('terms', $this->sharedData());
    }

    public function contact()
    {
        return view('contact', $this->sharedData());
    }

    public function quote()
    {
        return view('quote', $this->sharedData());
    }

    private function sharedData(): array
    {
        $cashes = $this->rates->getActiveRatesWithAll();

        return [
            'cashes' => $cashes,
            // Se busca sobre la misma colección ya cacheada (getRateByName usaría
            // una segunda entrada de caché). getRawOriginal: el accessor ucwords
            // de Cash::name rompe firstWhere('name', 'usd').
            'dollar' => $cashes->first(fn (Cash $c) => $c->getRawOriginal('name') === 'usd'),
        ];
    }
}
