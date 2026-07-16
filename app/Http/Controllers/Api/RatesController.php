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
        // Las tasas se refrescan cada ~300s (RateService cache): permitir que
        // Cloudflare/edge cacheen 60s absorbe casi todo el tráfico sin servir
        // datos rancios. Ver auditoría 07, ítem 36.
        return response()->json([
            'data'        => $this->rates->getActiveRates(),
            'disclaimer'  => 'Tasas referenciales. No representan cotizaciones oficiales de operación.',
            'updated_at'  => now()->toIso8601String(),
        ])->header('Cache-Control', 'public, max-age=60');
    }

    public function show(Cash $cash): JsonResponse
    {
        abort_if($cash->status !== 1, 404);
        abort_if(! in_array($cash->getRawOriginal('name'), RateService::ALLOWED), 404);

        // Servir la tasa CON overlay de forex (la misma que /api/rates), no el
        // valor crudo/sembrado del route-binding. Fallback al crudo si forex cae.
        $rate = $this->rates->getFullRateById($cash->id) ?? $cash;

        return response()->json([
            'data'       => $rate->only(['id', 'name', 'buy', 'sell', 'oficial']),
            'disclaimer' => 'Tasa referencial. No representa cotización oficial de operación.',
        ])->header('Cache-Control', 'public, max-age=60');
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
        ])->header('Cache-Control', 'public, max-age=60');
    }

    public function calculate(Request $request): JsonResponse
    {
        // Validación alineada con ExchangeRateController::calculate: `numeric` + cota
        // superior evitan overflow/entradas absurdas (antes un simple (float) sin tope
        // aceptaba cualquier cosa y "abc" → 0.0). El default de `type` sigue siendo buy.
        $validated = $request->validate([
            'amount'   => ['required', 'numeric', 'min:0.01', 'max:10000000'],
            'currency' => ['required', 'string'],
            'type'     => ['nullable', 'in:buy,sell'],
        ]);

        $currency = strtolower(trim($validated['currency']));
        $amount   = (float) $validated['amount'];
        $type     = $validated['type'] ?? 'buy';

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
