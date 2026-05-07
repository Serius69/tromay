<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cash;
use Illuminate\Http\JsonResponse;

class RatesController extends Controller
{
    private const ALLOWED = ['usd', 'eur', 'clp', 'pen', 'brl', 'ars'];

    public function index(): JsonResponse
    {
        $rates = Cash::where('status', 1)
            ->whereIn('name', self::ALLOWED)
            ->select('id', 'name', 'buy', 'sell', 'oficial')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'data'        => $rates,
            'disclaimer'  => 'Tasas referenciales. No representan cotizaciones oficiales de operación.',
            'updated_at'  => now()->toIso8601String(),
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
          ->header('Pragma', 'no-cache');
    }

    public function show(Cash $cash): JsonResponse
    {
        abort_if($cash->status !== 1, 404);
        abort_if(! in_array($cash->getRawOriginal('name'), self::ALLOWED), 404);

        return response()->json([
            'data'       => $cash->only(['id', 'name', 'buy', 'sell', 'oficial']),
            'disclaimer' => 'Tasa referencial. No representa cotización oficial de operación.',
        ]);
    }
}
