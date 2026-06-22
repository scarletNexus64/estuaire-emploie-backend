<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceConfiguration;
use Illuminate\Http\JsonResponse;

class AppConfigController extends Controller
{
    /**
     * Configuration publique consommée par l'application mobile au démarrage.
     * Expose les feature flags globaux (OTP, PayPal).
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'use_otp' => ServiceConfiguration::isOtpEnabled(),
                'use_paypal' => ServiceConfiguration::isPaypalEnabled(),
            ],
        ]);
    }
}
