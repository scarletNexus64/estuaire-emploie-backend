<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceMode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaintenanceModeController extends Controller
{
    /**
     * Get maintenance mode status
     *
     * @return JsonResponse
     */
    public function status(): JsonResponse
    {
        $maintenance = MaintenanceMode::latest()->first();

        $isActive = $maintenance ? $maintenance->is_active : false;
        $message = $maintenance && $maintenance->is_active ? $maintenance->message : null;

        return response()->json([
            'success' => true,
            'data' => [
                'is_active' => $isActive,
                'message' => $message,
                'activated_at' => $maintenance && $maintenance->is_active ? $maintenance->activated_at : null,
            ],
        ]);
    }
}
