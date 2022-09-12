<?php

namespace App\Http\Controllers;

use App\Services\Weather\WeatherInterface;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function latLng(Request $request, WeatherInterface $weather)
    {
        $data = $this->validate($request, [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $weatherData = $weather->getWeather($data['lat'], $data['lng']);

        return response()->json($weatherData);
    }
}
