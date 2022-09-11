<?php

namespace App\Http\Controllers;

use App\Services\FurnaceService;
use Exception;
use Illuminate\Http\Request;

class FurnaceTemperatureController extends Controller
{
    public function __construct(protected FurnaceService $furnaceService)
    {
    }

    public function show()
    {
        $this->middleware('auth:api');

        try {
            $temperature = $this->furnaceService->getTemperature();
        } catch (Exception $e) {
            abort(500, $e->getMessage());
            exit;
        }

        return response()->json($temperature);
    }

    public function store(Request $request)
    {
        $this->middleware('api_key');

        $data = $this->validate($request, [
            'temperature' => 'required|numeric',
        ]);

        $this->furnaceService->rememberTemperature($data['temperature']);

        return response()->json('success');
    }
}
