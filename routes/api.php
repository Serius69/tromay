<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\RatesController;
use App\Http\Controllers\ExchangeRateController;

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

/*
|--------------------------------------------------------------------------
| PUBLIC API — Exchange Rates (rate-limited, no auth required)
|--------------------------------------------------------------------------
| Movidas desde routes/web.php: al vivir bajo el grupo `api` (RouteServiceProvider
| aplica prefijo `api` + middleware `api` = throttle + SubstituteBindings, SIN
| sesión ni CSRF), el POST /api/ext-rates/calculate ya no devuelve 419 y los GET
| no crean sesión Redis por consumidor. Se conserva el throttle 60/min por IP y
| los nombres de ruta `api.*`. El prefijo `api/` lo añade RouteServiceProvider.
*/
Route::middleware('throttle:60,1')->name('api.')->group(function () {
    Route::get('rates',                [RatesController::class, 'index'])->name('rates.index');
    Route::get('rates/{cash}',         [RatesController::class, 'show'])->name('rates.show');
    Route::get('rates/{cash}/history', [RatesController::class, 'history'])->name('rates.history');
    Route::get('calculator',           [RatesController::class, 'calculate'])->name('calculator');
    Route::get('ext-rates',    [ExchangeRateController::class, 'getRates'])->name('ext-rates.index');
    Route::get('ext-rates/sources/{currency}', [ExchangeRateController::class, 'getSources'])->name('ext-rates.sources');
    Route::post('ext-rates/calculate', [ExchangeRateController::class, 'calculate'])->name('ext-rates.calculate');
});

