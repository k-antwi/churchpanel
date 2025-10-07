<?php

namespace ChurchPanel\CpCore\Providers;

use Illuminate\Support\ServiceProvider;

class CpCoreServiceProvider extends ServiceProvider
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
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'cp-core-migrations');

        // Publish config if needed in the future
        // $this->publishes([
        //     __DIR__ . '/../../config/cp-core.php' => config_path('cp-core.php'),
        // ], 'cp-core-config');
    }
}
