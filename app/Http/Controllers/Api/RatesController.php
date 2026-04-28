<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cash;
use Illuminate\Http\JsonResponse;

class RatesController extends Controller
{
    public function index(): JsonResponse
    {
        $rates = Cash::where('status', 1)
            ->select('id', 'name', 'buy', 'sell', 'oficial')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($rates)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache');
    }

    public function show(Cash $cash): JsonResponse
    {
        abort_if($cash->status !== 1, 404);

        return response()->json($cash->only(['id', 'name', 'buy', 'sell', 'oficial']));
    }
}
