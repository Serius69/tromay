<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\LatestController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES — Tromay es un sitio 100% público (vitrina + tasas forex)
|--------------------------------------------------------------------------
*/
Route::get('/health', function () {
    return response()->json([
        'status'    => 'ok',
        'app'       => config('app.name'),
        'env'       => config('app.env'),
        'timestamp' => now()->toIso8601String(),
    ]);
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/',        '__invoke')->name('home');
    Route::get('about',    'about')->name('about');
    Route::get('contact',  'contact')->name('contact');
    Route::get('privacy',  'privacy')->name('privacy');
    Route::get('terms',    'terms')->name('terms');
    Route::get('quote',    'quote')->name('quote');
});

// Public detail pages
Route::get('dinero/{cash}',    [CashController::class,  'show'])->name('dinero.show');
Route::get('noticia/{latest}', [LatestController::class, 'show'])->name('noticia.show');
Route::get('feed.xml',         [FeedController::class,  'rss'])->name('feed.rss');
Route::get('sitemap.xml',      [SitemapController::class, 'index'])->name('sitemap');

// NOTA: la API pública de tasas (/api/*) vive ahora en routes/api.php — grupo
// middleware `api` (sin sesión/CSRF). Se movió para que POST /api/ext-rates/calculate
// deje de morir por 419 CSRF y para no crear una sesión Redis por cada GET.
