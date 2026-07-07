<?php

namespace App\Http\Controllers;

use App\Models\Latest;
use App\Models\Transaction;
use App\Services\RateService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(private RateService $rates) {}

    public function __invoke()
    {
        $cashes = $this->rates->getActiveRatesWithAll();

        return view('index', [
            'cashes'  => $cashes,
            'latests' => Latest::published()->orderByDesc('date_publication')->limit(3)->get(),
            'dollar'  => $cashes->firstWhere('name', 'usd'),
        ]);
    }

    public function admin()
    {
        return view('admin.home', [
            'cashes'       => \App\Models\Cash::orderBy('id')->paginate(10),
            'latests'      => Latest::orderByDesc('date_publication')->paginate(3),
            'total_tx'     => Transaction::count(),
            'today_tx'     => Transaction::today()->count(),
            'month_tx'     => Transaction::thisMonth()->count(),
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
        $cashes = $this->rates->getActiveRatesWithAll();

        return view('quote', [
            'cashes' => $cashes,
            'dollar' => $cashes->firstWhere('name', 'usd'),
        ]);
    }

    private function sharedData(): array
    {
        $cashes = $this->rates->getActiveRatesWithAll();

        return [
            'cashes' => $cashes,
            'dollar' => $cashes->firstWhere('name', 'usd'),
        ];
    }
}
