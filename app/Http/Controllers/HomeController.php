<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Latest;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    const ALLOWED_CURRENCIES = ['usd', 'eur', 'clp', 'pen', 'brl', 'ars'];

    public function __invoke()
    {
        return view('index', [
            'cashes'  => Cash::where('status', 1)
                             ->whereIn('name', self::ALLOWED_CURRENCIES)
                             ->orderBy('id')
                             ->get(),
            'latests' => Latest::where('status', 1)->orderByDesc('date_publication')->limit(3)->get(),
            'dollar'  => Cash::where('name', 'usd')->where('status', 1)->first(),
        ]);
    }

    public function admin()
    {
        return view('admin.home', [
            'cashes'       => Cash::orderBy('id')->paginate(10),
            'latests'      => Latest::orderByDesc('date_publication')->paginate(3),
            'total_tx'     => Transaction::count(),
            'today_tx'     => Transaction::whereDate('created_at', today())->count(),
            'month_tx'     => Transaction::whereMonth('created_at', now()->month)->count(),
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
        return view('quote', [
            'cashes' => Cash::where('status', 1)
                             ->whereIn('name', self::ALLOWED_CURRENCIES)
                             ->orderBy('id')
                             ->get(),
            'dollar' => Cash::where('name', 'usd')->where('status', 1)->first(),
        ]);
    }

    private function sharedData(): array
    {
        return [
            'cashes' => Cash::where('status', 1)
                             ->whereIn('name', self::ALLOWED_CURRENCIES)
                             ->orderBy('id')
                             ->get(),
            'dollar' => Cash::where('name', 'usd')->where('status', 1)->first(),
        ];
    }
}
