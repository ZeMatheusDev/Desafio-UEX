<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        Inertia::share([
            'mensagem' => function () {
                return session('mensagem');
            },
            'errors' => function () {
                return session()->get('errors') ? session()->get('errors')->getBag('default')->toArray() : (object)[];
            },
        ]);
    }
}
