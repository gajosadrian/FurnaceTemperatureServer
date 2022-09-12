<?php

namespace App\Services\Weather;

interface WeatherInterface
{
    public function getWeather(float $lat, float $lng): WeatherData;
}
