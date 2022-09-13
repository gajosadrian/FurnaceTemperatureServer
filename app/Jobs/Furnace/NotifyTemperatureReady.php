<?php

namespace App\Jobs\Furnace;

use App\Jobs\Job;
use App\Services\Furnace\FurnaceService;
use App\Services\SlackService;
use Illuminate\Support\Facades\Cache;

class NotifyTemperatureReady extends Job
{
    protected float $minTemperature;
    protected float $optimalTemperature;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected FurnaceService $furnaceService)
    {
        $this->minTemperature = config('furnace.temperature.min');
        $this->optimalTemperature = config('furnace.temperature.ready');
    }

    /**
     * Execute the job.
     *
     * @param SlackService $slackService
     * @return void
     */
    public function handle(SlackService $slackService): void
    {
        try {
            $currentTemperature = $this->furnaceService->getTemperature();
        } catch (\Exception $e) {
            return;
        }

        if ($currentTemperature <= $this->minTemperature) {
            $this->unlockJob();
            return;
        }

        if ($this->isJobLocked()) {
            return;
        }

        if ($currentTemperature >= $this->optimalTemperature) {
            try {
                $slackService->sendMessage("Piec rozgrzał się: {$currentTemperature} °C");
                $this->lockJob();
            } catch (\Exception $e) {
            }
        }
    }

    protected function lockJob(): void
    {
        Cache::forever(static::class . '_locked', true);
    }

    protected function unlockJob(): void
    {
        Cache::forget(static::class . '_locked');
    }

    protected function isJobLocked(): bool
    {
        return Cache::get(static::class . '_locked', false);
    }
}
