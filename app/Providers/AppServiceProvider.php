<?php

namespace App\Providers;

use App\Models\Antrian;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

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
        // Force HTTPS di production untuk mengatasi mixed content
        if (App::environment('production')) {
            URL::forceScheme('https');
        }
        
        // Set trusted proxies untuk reverse proxy (Koyeb)
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $_SERVER['HTTPS'] = $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ? 'on' : 'off';
        }
        
        // Share data ke semua view
        // if (Schema::hasTable('antrians')) {
        //     $antrian = Antrian::first();
        //     View::share('antrian', $antrian);
        // }
    }
}
