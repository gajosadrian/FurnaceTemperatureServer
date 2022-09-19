<?php

namespace App\Http\Controllers;

use App\Services\Furnace\FurnaceService;
use Exception;
use Illuminate\Http\Request;

class FurnaceTemperatureController extends Controller
{
    public function __construct(protected FurnaceService $furnaceService)
    {
        $this->middleware('auth:api', ['only' => 'show']);
        $this->middleware('api_key', ['only' => 'store']);
    }

    public function show()
    {
        try {
            $temperature = $this->furnaceService->getTemperature();
        } catch (Exception $e) {
            abort(503, $e->getMessage());
            exit;
        }

        return response()->json($temperature);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'temperature' => 'required|numeric',
        ]);

        $this->furnaceService->registerTemperature($data['temperature']);

        return response()->json('success');
    }
}
