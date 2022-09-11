<?php

namespace App\Providers;

use App\Services\FurnaceService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(FurnaceService::class, function () {
            return new FurnaceService(config('furnace.temperature_cache_key'));
        });
    }
}
