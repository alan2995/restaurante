<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\DetalleVenta;
use App\Observers\DetalleVentaObserver;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         DetalleVenta::observe(DetalleVentaObserver::class);
    }
}
