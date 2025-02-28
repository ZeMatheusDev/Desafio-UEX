<?php

namespace App\Providers;

use App\Services\ViaCepService;
use App\Services\ViaCepServiceInterface;
use Illuminate\Support\ServiceProvider;

class ViaCepServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(ViaCepServiceInterface::class, function ($app) {
            return new ViaCepService();
        });
        $this->app->bind(
            \App\Services\ViaCepServiceInterface::class,
            \App\Services\ViaCepService::class
        );
    }


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
