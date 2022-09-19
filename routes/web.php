<?php

use Laravel\Lumen\Routing\Router;

/** @var Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::group(['prefix' => 'api'], function ($router) {
    /**
     * Auth
     */
    $router->post('login', 'AuthController@login');
    $router->post('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');
    $router->post('logout', 'AuthController@logout');

    /**
     * Furnace
     */
    $router->get('furnace/temperature', 'FurnaceTemperatureController@show');
    $router->post('furnace/temperature', 'FurnaceTemperatureController@store');
    $router->get('furnace/mode', 'FurnaceController@mode');
    $router->get('furnace/start-time', 'FurnaceController@startTime');

    /**
     * Furnace Settings
     */
    $router->get('settings/furnace/heating-mode', 'FurnaceSettingController@getHeatingMode');
    $router->post('settings/furnace/heating-mode', 'FurnaceSettingController@setHeatingMode');
    $router->get('settings/furnace/temperature', 'FurnaceSettingController@getTemperature');
    $router->post('settings/furnace/temperature', 'FurnaceSettingController@setTemperature');

    /**
     * Weather
     */
    $router->get('weather/lat-lng', 'WeatherController@latLng');
});
