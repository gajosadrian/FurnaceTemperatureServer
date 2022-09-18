<?php

namespace App\Services\Furnace\Enums;

enum FurnaceHeatingMode: string
{
    case CENTRAL_HEATING = 'central_heating';
    case WATER_HEATING = 'water_heating';
}
