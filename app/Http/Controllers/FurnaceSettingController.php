<?php

namespace App\Http\Controllers;

use App\Facades\Setting\FurnaceSetting;
use App\Services\Furnace\Enums\FurnaceHeatingMode;
use App\Services\Furnace\Enums\FurnaceHeatingType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class FurnaceSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function setHeatingMode(Request $request)
    {
        $data = $this->validate($request, [
            'mode' => [new Enum(FurnaceHeatingMode::class)],
        ]);

        $mode = FurnaceHeatingMode::from($data['mode']);

        FurnaceSetting::setHeatingMode($mode);

        return response()->json('success');
    }

    public function getHeatingMode()
    {
        $mode = FurnaceSetting::getHeatingMode();

        return response()->json($mode);
    }

    public function setTemperature(Request $request)
    {
        $data = $this->validate($request, [
            'mode' => [new Enum(FurnaceHeatingMode::class)],
            'type' => [new Enum(FurnaceHeatingType::class)],
            'temperature' => 'required|numeric',
        ]);

        $mode = FurnaceHeatingMode::from($data['mode']);
        $type = FurnaceHeatingType::from($data['type']);
        $temperature = (float)$data['temperature'];

        FurnaceSetting::setTemperature($mode, $type, $temperature);

        return response()->json('success');
    }

    public function getTemperature(Request $request)
    {
        $data = $this->validate($request, [
            'mode' => [new Enum(FurnaceHeatingMode::class)],
            'type' => [new Enum(FurnaceHeatingType::class)],
        ]);

        $mode = FurnaceHeatingMode::from($data['mode']);
        $type = FurnaceHeatingType::from($data['type']);

        $temperature = FurnaceSetting::getTemperature($mode, $type);

        return response()->json($temperature);
    }
}
