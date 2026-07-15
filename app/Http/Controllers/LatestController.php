<?php

namespace App\Http\Controllers;

use App\Models\Latest;
use App\Services\RateService;

class LatestController extends Controller
{
    public function __construct(private RateService $rates) {}

    public function show(Latest $latest)
    {
        abort_if($latest->status !== 1, 404);

        // Tasas CON overlay de forex (mismo dato que /api/rates y el resto del
        // sitio), no `Cash` crudo — antes esta vista mostraba tasas sembradas.
        $cashes = $this->rates->getActiveRatesWithAll();

        return view('latest.show', [
            'latest'  => $latest,
            'related' => Latest::where('status', 1)
                ->where('id', '!=', $latest->id)
                ->orderByDesc('date_publication')
                ->limit(3)
                ->get(),
            'cashes'  => $cashes,
            'dollar'  => $cashes->firstWhere('name', 'usd'),
        ]);
    }
}
