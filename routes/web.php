<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\LatestController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\SeoLandingController;

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

// SEO programático — landings de alto tráfico (dólar hoy / convertidor).
Route::get('dolar-hoy-bolivia', [SeoLandingController::class, 'dolarHoy'])->name('dolar-hoy');
Route::redirect('tipo-de-cambio-hoy', '/dolar-hoy-bolivia', 301); // alias → canónico
Route::get('convertidor/{par}', [SeoLandingController::class, 'convertidor'])
    ->where('par', '[A-Za-z\-]+')
    ->name('seo.convertidor');

// Captura de leads de email para alertas de tasas (venta cruzada a alertas.kapitalya.com.bo).
// Grupo `web` (CSRF activo); el partial manda el token por header y por campo @csrf.
Route::post('leads', [LeadController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('leads.store');

// NOTA: la API pública de tasas (/api/*) vive ahora en routes/api.php — grupo
// middleware `api` (sin sesión/CSRF). Se movió para que POST /api/ext-rates/calculate
// deje de morir por 419 CSRF y para no crear una sesión Redis por cada GET.
