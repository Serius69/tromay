<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\LatestController;
/*
|--------------------------------------------------------------------------
| CRUD
|
*/
use App\Http\Controllers\Crud\LatestCRUDController;
use App\Http\Controllers\Crud\CashCRUDController;
use App\Http\Controllers\Crud\TransactionCRUDController;


Route::controller(HomeController::class)->group(function(){
    Route::get('/',  '__invoke');
    Route::get('/admin',  'admin');
});

Route::controller(CashController::class)->group(function(){
    Route::get('/quote',  'quote');
});

Route::get('/about', function () {
    return view('about');
});
Route::get('/contact', function () {
    return view('contact');
});
Route::get('/privacy', function () {
    return view('privacy');
});
Route::get('/terms', function () {
    return view('terms');
});

    // Route::get('admin/home', [HomeController::class, 'adminHome'])->name('admin.home');

     Route::controller(CashCRUDController::class)->group(function(){
        Route::get('admin/cash', 'index');
    });
    Route::controller(TransactionCRUDController::class)->group(function(){
        Route::get('admin/transaction', 'index');
    });
    Route::controller(LatestCRUDController::class)->group(function(){
        Route::get('admin/latest', 'index');
    });


//Para vista del usuario
Route::resource('noticia', LatestController::class);
Route::resource('dinero', CashController::class);
//CRUD
Route::resource('latests', LatestCRUDController::class);
Route::resource('cashs', CashCRUDController::class);
