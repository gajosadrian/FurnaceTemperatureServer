<?php

namespace App\Providers;

use App\Services\FurnaceService;
use App\Services\SlackService;
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
            return new FurnaceService(config('furnace.temperature_cache_key'));
        });

        $this->app->singleton(SlackService::class, function () {
            return new SlackService(new Slack(config('slack.webhook_url')));
        });
    }
}
