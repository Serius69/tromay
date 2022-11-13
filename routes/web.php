<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/contact', function () {
    return view('contact');
});
Route::get('/quote', function () {
    return view('quote');
});
Route::get('/privacy', function () {
    return view('privacy');
});
Route::get('/terms', function () {
    return view('terms');
});

    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');

     Route::controller(CashController::class)->group(function(){
         Route::get('cash', 'crud');
     });


//Para vista del usuario
Route::resource('noticia', LatestController::class);
Route::resource('proyecto', CashController::class);
Route::resource('evento', EventController::class);
//CRUD
Route::resource('latests', LatestCRUDController::class);
Route::resource('events', EventCRUDController::class);
