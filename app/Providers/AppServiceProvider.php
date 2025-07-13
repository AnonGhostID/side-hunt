<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS for dev.sidehunt.web.id domain
        if (str_contains(config('app.url'), 'https://') || 
            str_contains(request()->getHost(), 'sidehunt.web.id')) {
            URL::forceScheme('https');
        }
    }
}
