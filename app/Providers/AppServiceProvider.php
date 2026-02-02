<?php

namespace App\Providers;

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('components.user-nav', function ($view) {
            $kotas = HomeController::getNavKotas();
            $view->with('kotas', $kotas);
        });
    }
}
