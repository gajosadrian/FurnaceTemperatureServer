<?php

namespace App\Services\Weather;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OpenWeatherMap implements WeatherInterface
{
    public const BASE_URL = 'https://api.openweathermap.org/data/2.5/';

    public function __construct(private readonly string $apiKey, protected string $locale)
    {
    }

    /**
     * @param float $lat
     * @param float $lng
     * @return WeatherData
     * @throws Exception
     */
    public function getWeather(float $lat, float $lng): WeatherData
    {
        $cacheKey = static::class . "_getWeather_{$lat}_{$lng}";
        $rememberTo = Carbon::now()->addMinutes(5);

        $data = Cache::remember($cacheKey, $rememberTo, function () use ($lat, $lng) {
            $res = Http::get(static::BASE_URL . '/weather', [
                'lat' => $lat,
                'lon' => $lng,
                'units' => 'metric',
                'lang' => $this->locale,
                'appid' => $this->apiKey,
            ]);

            if ($res->failed()) {
                throw new Exception('Failed to connect to the ' . static::BASE_URL);
            }

            return $res->json();
        });

        $weatherData = new WeatherData;
        $weatherData->temperature = $data['main']['temp'];
        $weatherData->city = $data['name'];
        $weatherData->lat = $data['coord']['lat'];
        $weatherData->lng = $data['coord']['lon'];

        return $weatherData;
    }
}
