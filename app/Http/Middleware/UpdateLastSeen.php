<?php

namespace App\Http\Middleware;

use App\Models\UserPresence;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     * Met à jour le last_seen de l'utilisateur authentifié à chaque requête
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            UserPresence::updateLastSeen(Auth::id());
        }

        return $next($request);
    }
}
