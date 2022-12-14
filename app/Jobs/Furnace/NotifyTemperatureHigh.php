<?php

namespace App\Jobs\Furnace;

use App\Facades\Setting\FurnaceSetting;
use App\Jobs\Job;
use App\Services\Furnace\Enums\FurnaceHeatingType;
use App\Services\Furnace\FurnaceService;
use App\Services\SlackService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class NotifyTemperatureHigh extends Job
{
    protected ?float $maxTemperature;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected FurnaceService $furnaceService)
    {
        $mode = FurnaceSetting::getHeatingMode();
        $this->maxTemperature = FurnaceSetting::getTemperature($mode, FurnaceHeatingType::MAX);
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

        if (is_null($this->maxTemperature) or $currentTemperature < $this->maxTemperature) {
            $this->unlockJob();
            return;
        }

        if ($this->isJobLocked()) {
            return;
        }

        if ($currentTemperature >= $this->maxTemperature) {
            try {
                $slackService->sendMessage("⚠️ Temperatura pieca za duża: {$currentTemperature} °C");
                $this->lockJob();
            } catch (\Exception $e) {
            }
        }
    }

    protected function lockJob(): void
    {
        Cache::put(static::class . '_locked', true, Carbon::now()->addMinutes(5));
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
