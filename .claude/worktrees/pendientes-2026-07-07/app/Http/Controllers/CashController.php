<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Services\RateService;

class CashController extends Controller
{
    public function __construct(private RateService $rates) {}

    public function show(Cash $cash)
    {
        abort_if($cash->status !== 1, 404);

        $cashes = $this->rates->getActiveRatesWithAll();

        return view('cash.show', [
            'cash'   => $cash,
            'cashes' => $cashes,
            // getRawOriginal: el accessor de Cash::name aplica ucwords y rompe firstWhere.
            'dollar' => $cashes->first(fn (Cash $c) => $c->getRawOriginal('name') === 'usd'),
        ]);
    }
}
