<?php

namespace App\Services\Furnace;

use App\Facades\Setting\FurnaceSetting;
use App\Services\Furnace\Enums\FurnaceHeatingType;
use App\Services\Furnace\Enums\FurnaceMode;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class FurnaceService
{
    private const REMEMBER_MINUTES = 5;

    protected float $startTemperature;

    public function __construct(protected readonly string $temperatureCacheKey, protected readonly string $modeCacheKey, protected readonly string $startAtCacheKey)
    {
        $this->startTemperature = config('furnace.temperature.start');
    }

    /**
     * @param float $temperature
     * @return void
     */
    public function registerTemperature(float $temperature): void
    {
        $this->registerMode($temperature);
        $this->registerStartTime($temperature);

        Cache::put($this->temperatureCacheKey, $temperature, Carbon::now()->addMinutes(static::REMEMBER_MINUTES));
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getTemperature(): float
    {
        $temperature = Cache::get($this->temperatureCacheKey);

        if (is_null($temperature)) {
            throw new Exception('Furnace temperature is not registered');
        }

        return $temperature;
    }

    /**
     * @param float $temperature
     * @return void
     */
    protected function registerMode(float $temperature): void
    {
        try {
            $lastTemperature = $this->getTemperature();
        } catch (Exception $e) {
            $lastTemperature = null;
        }

        if (is_null($lastTemperature) or $temperature < $this->startTemperature) {
            $mode = FurnaceMode::INACTIVE;
        } elseif ($temperature > $lastTemperature) {
            $mode = FurnaceMode::HEATING;
        } elseif ($temperature < $lastTemperature) {
            $mode = FurnaceMode::COOLING;
        } else {
            $mode = $this->getMode();
        }

        Cache::put($this->modeCacheKey, $mode, Carbon::now()->addMinutes(static::REMEMBER_MINUTES));
    }

    /**
     * @return FurnaceMode
     */
    public function getMode(): FurnaceMode
    {
        return Cache::get($this->modeCacheKey, FurnaceMode::INACTIVE);
    }

    /**
     * @param float $temperature
     * @return void
     */
    public function registerStartTime(float $temperature): void
    {
        if ($temperature < $this->startTemperature) {
            Cache::forget($this->startAtCacheKey);
            return;
        }

        try {
            $lastStartAt = $this->getStartTime();
        } catch (\Exception $e) {
            $lastStartAt = null;
        }

        if ($lastStartAt) return;

        Cache::forever($this->startAtCacheKey, Carbon::now());
    }

    /**
     * @return Carbon
     * @throws Exception
     */
    public function getStartTime(): Carbon
    {
        $startAt = Cache::get($this->startAtCacheKey);

        if (is_null($startAt)) {
            throw new Exception('Furnace is inactive');
        }

        return $startAt;
    }
}
