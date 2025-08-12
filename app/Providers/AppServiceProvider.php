<?php

namespace App\Providers;

use App\Models\Antrian;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // Share data ke semua view
        // if (Schema::hasTable('antrians')) {
        //     $antrian = Antrian::first();
        //     View::share('antrian', $antrian);
        // }
    }
}
