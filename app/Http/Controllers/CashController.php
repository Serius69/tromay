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
        // La divisa mostrada debe traer el overlay de forex (misma tasa que
        // /api/rates), no el $cash crudo del route-binding.
        $cash = $cashes->firstWhere('id', $cash->id) ?? $cash;

        return view('cash.show', [
            'cash'   => $cash,
            'cashes' => $cashes,
            'dollar' => $cashes->firstWhere('name', 'usd'),
        ]);
    }
}
