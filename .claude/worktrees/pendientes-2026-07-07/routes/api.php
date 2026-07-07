<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ── Health checks (sin auth — para K8s probes y nginx upstream checks) ───────
Route::get('/health/live', function () {
    return response()->json(['status' => 'ok', 'service' => 'tromay']);
})->withoutMiddleware(['auth:sanctum']);

Route::get('/health/ready', function () {
    $checks = [];
    $ok     = true;

    try {
        DB::connection()->getPdo();
        $checks['db'] = 'ok';
    } catch (\Exception $e) {
        $checks['db'] = 'error';
        $ok = false;
    }

    try {
        Cache::put('_health_probe', '1', 5);
        $checks['cache'] = Cache::get('_health_probe') === '1' ? 'ok' : 'mismatch';
    } catch (\Exception $e) {
        $checks['cache'] = 'degraded';
    }

    return response()->json(
        ['status' => $ok ? 'ok' : 'degraded', 'checks' => $checks],
        $ok ? 200 : 503
    );
})->withoutMiddleware(['auth:sanctum']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ── DataTables server-side para 100k+ registros ──────────────────────────────
Route::middleware('auth:sanctum')
    ->get('/transactions/datatable', \App\Http\Controllers\Api\TransactionDatatableController::class)
    ->name('api.transactions.datatable');

// ── Calculadora de tasas externas (API pública: sin CSRF, con throttle) ──────
Route::middleware('throttle:60,1')
    ->post('/ext-rates/calculate', [\App\Http\Controllers\ExchangeRateController::class, 'calculate'])
    ->name('api.ext-rates.calculate');
