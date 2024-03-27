<?php

namespace App\Providers;

use App\Services\BarbershopService;
use Illuminate\Support\ServiceProvider;

class BarbershopServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BarbershopService::class, function ($app) {
            return new BarbershopService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
