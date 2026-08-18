<?php

namespace App\Providers;

use App\Models\Cash;
use App\Observers\CashObserver;
use App\Services\RateService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
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

        $this->shareRatesWithLayout();
    }

    /**
     * Garantiza que `layout.master` siempre tenga `$cashes`.
     *
     * El pie del layout recorre `$cashes` (master.blade.php:375), pero solo los
     * controladores del sitio lo pasaban. Las páginas de error extienden el mismo
     * layout SIN pasarlo, así que al renderizar un 404/429/500/503 el layout
     * lanzaba "Undefined variable $cashes" y Laravel caía a su página genérica en
     * inglés: las plantillas de error del proyecto no se veían nunca.
     *
     * El composer solo rellena el hueco (no pisa lo que ya envió el controlador)
     * y NUNCA propaga una excepción: si la fuente de tasas falla —que es
     * justamente el escenario de un 500— el pie se dibuja vacío en vez de
     * convertir el error en otro error.
     */
    private function shareRatesWithLayout(): void
    {
        View::composer('layout.master', function ($view) {
            if (array_key_exists('cashes', $view->getData())) {
                return;
            }

            $view->with('cashes', $this->safeRates());
        });
    }

    private function safeRates(): Collection
    {
        try {
            return app(RateService::class)->getActiveRatesWithAll();
        } catch (\Throwable $e) {
            Log::warning('AppServiceProvider: no se pudieron cargar las tasas para el pie del layout', [
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }
}
