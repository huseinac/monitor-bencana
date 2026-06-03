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
        require_once app_path('Helpers/IoHelper.php');
        if (config('app.env') === 'development' || config('app.env') === 'staging') {
            URL::forceScheme('https');
            set_time_limit(120);
        }
    }
}
