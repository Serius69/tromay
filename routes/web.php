<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\LatestController;
use App\Http\Controllers\TransactionController;
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
    Route::get('admin',  'admin');
    Route::get('about',  'about');
    Route::get('contact',  'contact');
    Route::get('privacy',  'privacy');
    Route::get('terms',  'terms');

});

Route::controller(CashController::class)->group(function(){
    Route::get('/quote',  'quote');
});

    // Route::get('admin/home', [HomeController::class, 'adminHome'])->name('admin.home');


    Route::controller(DashboardController::class)->group(function(){
        Route::get('admin/analytics',  'analytics');
    });

     Route::controller(CashCRUDController::class)->group(function(){
        Route::get('admin/cash', 'index');
    });
    Route::controller(TransactionCRUDController::class)->group(function(){
        Route::get('admin/transaction', 'index');
    });
    Route::controller(TransactionController::class)->group(function(){
        Route::get('admin/buy', 'buy');
        Route::get('admin/sell', 'sell');
    });
    Route::controller(LatestCRUDController::class)->group(function(){
        Route::get('admin/latest', 'index');
    });
    Route::controller(LatestCRUDController::class)->group(function(){
        Route::get('admin/quotation', 'index');
    });


//Para vista del usuario
Route::resource('noticia', LatestController::class);
Route::resource('dinero', CashController::class);
//CRUD
Route::resource('latests', LatestCRUDController::class);
Route::resource('cashes', CashCRUDController::class);
Route::resource('transactions', LatestCRUDController::class);
