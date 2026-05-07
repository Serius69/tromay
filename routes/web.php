<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LatestController;
use App\Http\Controllers\Api\RatesController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\Crud\LatestCRUDController;
use App\Http\Controllers\Crud\CashCRUDController;
use App\Http\Controllers\Crud\TransactionCRUDController;
use App\Http\Controllers\Crud\ClientCRUDController;
use App\Http\Controllers\Crud\QuotationCRUDController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::controller(HomeController::class)->group(function () {
    Route::get('/',        '__invoke')->name('home');
    Route::get('about',    'about')->name('about');
    Route::get('contact',  'contact')->name('contact');
    Route::get('privacy',  'privacy')->name('privacy');
    Route::get('terms',    'terms')->name('terms');
    Route::get('quote',    'quote')->name('quote');
});

// Public detail pages
Route::get('dinero/{cash}',   [CashController::class,  'show'])->name('dinero.show');
Route::get('noticia/{latest}', [LatestController::class, 'show'])->name('noticia.show');

/*
|--------------------------------------------------------------------------
| PUBLIC API — Exchange Rates (rate-limited, no auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('throttle:60,1')->prefix('api')->name('api.')->group(function () {
    Route::get('rates',        [RatesController::class,      'index'])->name('rates.index');
    Route::get('rates/{cash}', [RatesController::class,      'show'])->name('rates.show');
    Route::get('ext-rates',    [ExchangeRateController::class,'getRates'])->name('ext-rates.index');
    Route::get('ext-rates/sources/{currency}', [ExchangeRateController::class,'getSources'])->name('ext-rates.sources');
    Route::post('ext-rates/calculate', [ExchangeRateController::class,'calculate'])->name('ext-rates.calculate');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES — authentication required
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/',           [HomeController::class,       'admin'])->name('home');
    Route::get('analytics',   [DashboardController::class,  'analytics'])->name('analytics');

    // Buy / Sell transaction forms
    Route::get('buy',  [TransactionController::class, 'buy'])->name('buy');
    Route::get('sell', [TransactionController::class, 'sell'])->name('sell');
    Route::post('transaction/store', [TransactionController::class, 'store'])->name('transaction.store');

    // Resource CRUD
    Route::resource('cash',        CashCRUDController::class);
    Route::resource('client',      ClientCRUDController::class);
    Route::resource('transaction', TransactionCRUDController::class);
    Route::resource('latest',      LatestCRUDController::class);
    Route::resource('quotation',   QuotationCRUDController::class);
});
