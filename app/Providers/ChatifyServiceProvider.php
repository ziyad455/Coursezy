<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ChatifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind ChatifyMessenger as singleton
        $this->app->singleton('ChatifyMessenger', function ($app) {
            return new \App\Helpers\ChatifyMessenger();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerViews();
    }

    /**
     * Register Chatify routes
     */
    protected function registerRoutes(): void
    {
        // Load Chatify web routes
        Route::middleware(['web', 'auth'])
            ->prefix('chatify')
            ->namespace('App\Http\Controllers\vendor\Chatify')
            ->group(base_path('routes/chatify/web.php'));

        // Load Chatify API routes
        Route::middleware(['api'])
            ->prefix('chatify/api')
            ->namespace('App\Http\Controllers\vendor\Chatify\Api')
            ->group(base_path('routes/chatify/api.php'));
    }

    /**
     * Register Chatify views
     */
    protected function registerViews(): void
    {
        // Register view namespace for Chatify
        $this->loadViewsFrom(resource_path('views/vendor/Chatify'), 'Chatify');
    }
}
