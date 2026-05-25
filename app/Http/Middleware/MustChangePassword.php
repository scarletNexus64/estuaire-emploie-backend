<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si l'utilisateur est authentifié et doit changer son mot de passe
        if ($user && $user->must_change_password) {
            $currentPath = $request->path();

            // Autoriser uniquement les routes essentielles
            $allowedRoutes = [
                'api/password/force-change',  // Route pour changer le mot de passe
                'api/user',                    // Récupérer infos utilisateur
                'api/logout',                  // Se déconnecter
            ];

            // Vérifier si la route est autorisée
            $isAllowed = false;
            foreach ($allowedRoutes as $route) {
                if (str_contains($currentPath, $route)) {
                    $isAllowed = true;
                    break;
                }
            }

            // Bloquer toutes les autres routes
            if (!$isAllowed) {
                return response()->json([
                    'success' => false,
                    'must_change_password' => true,
                    'message' => 'Vous devez changer votre mot de passe avant de continuer.',
                ], 403);
            }
        }

        return $next($request);
    }
}
