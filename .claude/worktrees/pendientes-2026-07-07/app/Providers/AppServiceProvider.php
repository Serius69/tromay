<?php

namespace App\Providers;

use App\Models\Cash;
use App\Observers\CashObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Cash::observe(CashObserver::class);
    }
}
