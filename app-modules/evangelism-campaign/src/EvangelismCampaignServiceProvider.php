<?php

namespace ChurchPanel\EvangelismCampaign;

use Illuminate\Support\ServiceProvider;

class EvangelismCampaignServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
