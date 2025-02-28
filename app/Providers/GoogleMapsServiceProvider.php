<?php

namespace App\Providers;

use App\Services\GoogleMapsService;
use App\Services\GoogleMapsServiceInterface;
use Illuminate\Support\ServiceProvider;

class GoogleMapsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            GoogleMapsServiceInterface::class, 
            GoogleMapsService::class 
        );
    }


    public function boot(): void
    {
        //
    }
}
