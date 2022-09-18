<?php

namespace App\Facades\Setting;

use App\Models\Setting as Model;
use App\Services\Furnace\Enums\FurnaceHeatingMode;
use App\Services\Furnace\Enums\FurnaceHeatingType;
use Illuminate\Support\Facades\Facade;

class FurnaceSetting extends Facade
{
    public static function setHeatingMode(FurnaceHeatingMode $mode): void
    {
        Model::updateOrInsert(
            ['key' => 'furnace.heating_mode'],
            ['value' => $mode->value]
        );
    }

    public static function getHeatingMode(): FurnaceHeatingMode
    {
        $model = Model::where('key', 'furnace.heating_mode')->firstOrFail();

        $heatingMode = FurnaceHeatingMode::tryFrom($model->value);
        $heatingMode ??= FurnaceHeatingMode::CENTRAL_HEATING;

        return $heatingMode;
    }

    public static function setTemperature(FurnaceHeatingMode $mode, FurnaceHeatingType $type, float $val): void
    {
        $model = Model::where('key', "furnace.{$mode->value}.temperature.{$type->value}")->firstOrFail();
        $model->value = $val;
    }

    public static function getTemperature(FurnaceHeatingMode $mode, FurnaceHeatingType $type): ?float
    {
        $model = Model::where('key', "furnace.{$mode->value}.temperature.{$type->value}")->firstOrFail();
        return $model->value;
    }
}
