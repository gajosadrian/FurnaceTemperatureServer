<?php

use Laravel\Lumen\Routing\Router;

/** @var Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::group(['prefix' => 'api'], function ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');
    $router->post('logout', 'AuthController@logout');

    $router->get('furnace/temperature', 'FurnaceTemperatureController@show');
    $router->post('furnace/temperature', 'FurnaceTemperatureController@store');
    $router->get('furnace/mode', 'FurnaceModeController@show');

    $router->get('weather/lat-lng', 'WeatherController@latLng');
});
