<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class FurnaceService
{
    public function __construct(protected string $temperatureCacheKey)
    {
    }

    /**
     * @param float $temperature
     * @return void
     */
    public function rememberTemperature(float $temperature): void
    {
        Cache::put($this->temperatureCacheKey, $temperature, Carbon::now()->addMinutes(10));
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
}
