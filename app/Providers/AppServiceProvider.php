<?php

namespace App\Providers;

use App\Services\Furnace\FurnaceService;
use App\Services\SlackService;
use App\Services\Weather\OpenWeatherMap;
use App\Services\Weather\WeatherInterface;
use Illuminate\Support\ServiceProvider;
use Slack;

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
            return new FurnaceService('furnace.temperature', 'furnace.mode', 'furnace.start_at');
        });

        $this->app->singleton(SlackService::class, function () {
            $slack = new Slack(config('slack.webhook_url'));
            return new SlackService($slack);
        });

        $this->app->singleton(WeatherInterface::class, function () {
            return new OpenWeatherMap(config('openweathermap.api_key'), config('app.locale'));
        });
    }
}
