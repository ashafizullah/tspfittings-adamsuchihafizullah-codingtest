<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DingtalkService;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->app->singleton(DingtalkService::class, function ($app) {
			return new DingtalkService();
		});
	}
}
