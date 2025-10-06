<?php

namespace ChurchPanel\People\Providers;

use Illuminate\Support\ServiceProvider;

class PeopleServiceProvider extends ServiceProvider
{
	public function register(): void
	{
	}

	public function boot(): void
	{
		$this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
	}
}
