<?php

namespace App\Services\Furnace;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class FurnaceService
{
    private const REMEMBER_MINUTES = 5;

    protected float $maxTemperature;
    protected float $minTemperature;

    public function __construct(protected readonly string $temperatureCacheKey, protected readonly string $modeCacheKey)
    {
        $this->maxTemperature = config('furnace.temperature.max');
        $this->minTemperature = config('furnace.temperature.min');
    }

    /**
     * @param float $temperature
     * @return void
     */
    public function registerTemperature(float $temperature): void
    {
        $this->registerMode($temperature);
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

        if (is_null($lastTemperature) or $temperature < $this->minTemperature) {
            $mode = FurnaceMode::INACTIVE;
        } elseif ($temperature > $lastTemperature) {
            $mode = FurnaceMode::HEATING;
        } elseif ($temperature < $lastTemperature) {
            $mode = FurnaceMode::COOLING;
        } else {
            $mode = $this->getMode();
        }

        Cache::put($this->modeCacheKey, $mode->value, Carbon::now()->addMinutes(static::REMEMBER_MINUTES));
    }

    /**
     * @return FurnaceMode
     */
    public function getMode(): FurnaceMode
    {
        $modeValue = Cache::get($this->modeCacheKey);

        $mode = FurnaceMode::tryFrom($modeValue);
        $mode ??= FurnaceMode::INACTIVE;

        return $mode;
    }
}
