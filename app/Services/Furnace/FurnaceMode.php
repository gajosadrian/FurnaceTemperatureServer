<?php

namespace App\Services\Furnace;

enum FurnaceMode: string
{
    case INACTIVE = 'inactive';
    case HEATING = 'heating';
    case COOLING = 'cooling';
}
