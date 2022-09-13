<?php

namespace App\Http\Controllers;

use App\Services\Furnace\FurnaceService;
use Illuminate\Http\Request;

class FurnaceModeController extends Controller
{
    public function __construct(protected FurnaceService $furnaceService)
    {
        $this->middleware('auth:api');
    }

    public function show()
    {
        $mode = $this->furnaceService->getMode();

        return response()->json($mode);
    }
}
