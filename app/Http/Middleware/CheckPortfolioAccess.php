<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPortfolioAccess
{
    /**
     * Handle an incoming request.
     * Check if user has OR or DIAMANT subscription to access portfolio feature
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié',
            ], 401);
        }

        // Check if user has an active subscription
        $activeSubscription = $user->activeSubscription();

        if (!$activeSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez avoir un abonnement actif pour accéder à cette fonctionnalité',
                'required_subscription' => ['PACK C2 (OR)', 'PACK C3 (DIAMANT)', 'PACK R2 (OR)', 'PACK R3 (DIAMANT)'],
            ], 403);
        }

        // Check if subscription plan includes portfolio feature
        // Plans autorisés : OR (Pack C2/R2) et DIAMANT (Pack C3/R3)
        // Aussi GOLD et PLATINUM pour compatibilité
        $planName = strtoupper($activeSubscription->subscriptionPlan->name ?? '');

        // Vérifier si le nom du plan contient l'un des mots-clés autorisés
        $allowedKeywords = ['OR', 'DIAMANT', 'GOLD', 'PLATINUM'];
        $hasAccess = false;

        foreach ($allowedKeywords as $keyword) {
            if (str_contains($planName, $keyword)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Cette fonctionnalité est réservée aux abonnés OR et DIAMANT',
                'current_subscription' => $planName,
                'required_subscription' => ['PACK C2 (OR)', 'PACK C3 (DIAMANT)', 'PACK R2 (OR)', 'PACK R3 (DIAMANT)'],
            ], 403);
        }

        return $next($request);
    }
}
