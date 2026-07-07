<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cash;
use App\Services\RateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatesController extends Controller
{
    public function __construct(private RateService $rates) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data'        => $this->rates->getActiveRates(),
            'disclaimer'  => 'Tasas referenciales. No representan cotizaciones oficiales de operación.',
            'updated_at'  => now()->toIso8601String(),
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
          ->header('Pragma', 'no-cache');
    }

    public function show(Cash $cash): JsonResponse
    {
        abort_if($cash->status !== 1, 404);
        abort_if(! in_array($cash->getRawOriginal('name'), RateService::ALLOWED), 404);

        return response()->json([
            'data'       => $cash->only(['id', 'name', 'buy', 'sell', 'oficial']),
            'disclaimer' => 'Tasa referencial. No representa cotización oficial de operación.',
        ]);
    }

    public function history(Cash $cash): JsonResponse
    {
        abort_if($cash->status !== 1, 404);
        abort_if(! in_array($cash->getRawOriginal('name'), RateService::ALLOWED), 404);

        $history = $cash->rateHistory()
            ->orderByDesc('created_at')
            ->limit(60)
            ->get(['buy', 'sell', 'oficial', 'created_at']);

        return response()->json([
            'data'       => $history,
            'currency'   => $cash->only(['id', 'name']),
            'disclaimer' => 'Historial de tasas referenciales.',
        ]);
    }

    public function calculate(Request $request): JsonResponse
    {
        $currency = strtolower(trim($request->input('currency', '')));
        $amount   = (float) $request->input('amount', 0);
        $type     = $request->input('type', 'buy');

        if ($amount <= 0) {
            return response()->json(['error' => 'El monto debe ser mayor a cero.'], 422);
        }

        if (! in_array($type, ['buy', 'sell'])) {
            return response()->json(['error' => 'El tipo debe ser "buy" o "sell".'], 422);
        }

        $result = $this->rates->calculate($amount, $currency, $type);

        if (! $result) {
            return response()->json(['error' => 'Divisa no encontrada o no disponible.'], 404);
        }

        return response()->json([
            'data'       => $result,
            'disclaimer' => 'Tasa referencial. No representa cotización oficial de operación.',
            'updated_at' => now()->toIso8601String(),
        ]);
    }
}
