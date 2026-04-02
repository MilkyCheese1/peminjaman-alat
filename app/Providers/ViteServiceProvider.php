<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configure Vite for development and production
        if (app()->environment('local')) {
            // In development, use Vite dev server
            $this->app['config']['app.asset_url'] = 'http://127.0.0.1:5174';
        }
    }
}
