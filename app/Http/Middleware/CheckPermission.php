<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $permission  The permission required to access the route
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $user = $request->user();

        // If user is not authenticated, redirect to login
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter.'
                ], 401);
            }
            return redirect()->route('admin.login')->with('error', 'Veuillez vous connecter.');
        }

        // Check if user is admin or recruiter
        if (!$user->isAdmin() && !$user->isRecruiter()) {
            auth()->logout();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect()->route('admin.login')->with('error', 'Accès non autorisé.');
        }

        // Super admin has access to everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // If a specific permission is required, check it
        if ($permission && !$user->hasPermission($permission)) {
            // For web requests, return 403 page
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Vous n\'avez pas la permission d\'accéder à cette ressource.'
                ], 403);
            }

            abort(403, 'Vous n\'avez pas la permission d\'accéder à cette ressource.');
        }

        return $next($request);
    }
}
