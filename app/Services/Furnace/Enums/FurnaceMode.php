<?php

namespace App\Services\Furnace\Enums;

enum FurnaceMode: string
{
    case INACTIVE = 'inactive';
    case HEATING = 'heating';
    case COOLING = 'cooling';
}
