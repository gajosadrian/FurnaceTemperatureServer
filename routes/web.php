<?php

use Laravel\Lumen\Routing\Router;

/** @var Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::group(['prefix' => 'api'], function ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->get('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');

    $router->get('furnace/temperature', 'FurnaceTemperatureController@show');
    $router->post('furnace/temperature', 'FurnaceTemperatureController@store');
});
