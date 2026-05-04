<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \App\Observers\WebhookObserver::bootProjectEvents();
        \App\Observers\WebhookObserver::bootActivityEvents();

        // Boot active plugins (safe: no-op if table doesn't exist yet)
        if (config('gpro.plugins.enabled', true)) {
            \App\Services\PluginManager::boot();
        }
    }
}
