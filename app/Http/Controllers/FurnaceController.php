<?php

namespace App\Http\Controllers;

use App\Services\Furnace\FurnaceService;
use Exception;
use Illuminate\Http\Request;

class FurnaceController extends Controller
{
    public function __construct(protected FurnaceService $furnaceService)
    {
        $this->middleware('auth:api');
    }

    public function mode()
    {
        $mode = $this->furnaceService->getMode();

        return response()->json($mode);
    }

    public function startTime()
    {
        try {
            $startAt = $this->furnaceService->getStartTime();
        } catch (Exception $e) {
            abort(500, $e->getMessage());
            exit;
        }

        return response()->json($startAt);
    }
}
