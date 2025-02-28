<?php

namespace App\Providers;

use App\Services\NominatimService;
use App\Services\NominatimServiceInterface;
use Illuminate\Support\ServiceProvider;

class NominatimServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            NominatimServiceInterface::class, 
            NominatimService::class 
        );
    }


    public function boot(): void
    {
        //
    }
}
