<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Resolution order:
     *   1. ?lang=xx query parameter
     *   2. Accept-Language header (first matching locale)
     *   3. Authenticated user's `locale` column
     *   4. config('app.fallback_locale')
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('translations.locales', ['fr', 'en', 'es', 'ar']);
        $fallback = config('app.fallback_locale', 'fr');

        $locale = $this->resolveLocale($request, $supported) ?? $fallback;

        App::setLocale($locale);

        // Active l'auto-localisation des modèles HasTranslations pour cette
        // requête. Les controllers API peuvent retourner les modèles
        // directement (ou leurs relations) sans appeler `t()` manuellement —
        // les champs traduisibles sont remplacés à la volée lors du toArray().
        // Désactivé par défaut hors requête (jobs, console, admin web).
        App::instance('translations.auto_localize', true);

        return $next($request);
    }

    /**
     * @param  array<int, string>  $supported
     */
    protected function resolveLocale(Request $request, array $supported): ?string
    {
        $param = config('translations.query_param', 'lang');
        if ($request->filled($param)) {
            $candidate = strtolower((string) $request->query($param));
            if (in_array($candidate, $supported, true)) {
                return $candidate;
            }
        }

        $header = $request->header(config('translations.request_header', 'Accept-Language'));
        if ($header) {
            foreach (explode(',', $header) as $entry) {
                $tag = strtolower(trim(explode(';', $entry)[0]));
                $primary = explode('-', $tag)[0];
                if (in_array($primary, $supported, true)) {
                    return $primary;
                }
            }
        }

        $user = $request->user();
        if ($user && isset($user->locale) && in_array($user->locale, $supported, true)) {
            return $user->locale;
        }

        return null;
    }
}
