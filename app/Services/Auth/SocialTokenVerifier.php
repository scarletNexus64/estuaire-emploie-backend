<?php

namespace App\Services\Auth;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

/**
 * Vérifie les jetons d'identité (id_token) de Google et d'Apple.
 *
 * Le jeton est un JWT signé par le fournisseur : on vérifie la signature avec
 * ses clés publiques (JWKS, mises en cache), l'émetteur, l'audience (nos
 * identifiants d'application) et l'expiration. Rien n'est cru sans cela.
 */
class SocialTokenVerifier
{
    private const PROVIDERS = [
        'google' => [
            'jwks' => 'https://www.googleapis.com/oauth2/v3/certs',
            'issuers' => ['accounts.google.com', 'https://accounts.google.com'],
            'audiences_config' => 'services.google.client_ids',
        ],
        'apple' => [
            'jwks' => 'https://appleid.apple.com/auth/keys',
            'issuers' => ['https://appleid.apple.com'],
            'audiences_config' => 'services.apple.client_ids',
        ],
    ];

    /**
     * @return array{sub: string, email: ?string, email_verified: bool, name: ?string}
     *
     * @throws RuntimeException si le jeton est invalide ou le fournisseur non configuré.
     */
    public function verify(string $provider, string $idToken): array
    {
        $settings = self::PROVIDERS[$provider] ?? null;
        if ($settings === null) {
            throw new RuntimeException("Fournisseur inconnu : {$provider}");
        }

        $audiences = array_filter(config($settings['audiences_config'], []));
        if (empty($audiences)) {
            throw new RuntimeException("Connexion {$provider} non configurée (identifiants client manquants).");
        }

        // Petite tolérance d'horloge entre nos serveurs et le fournisseur.
        JWT::$leeway = 60;

        try {
            $claims = (array) JWT::decode($idToken, $this->keys($provider, $settings['jwks']));
        } catch (Throwable $e) {
            // Clé tournée chez le fournisseur : on recharge une fois le JWKS.
            Cache::forget($this->cacheKey($provider));
            try {
                $claims = (array) JWT::decode($idToken, $this->keys($provider, $settings['jwks']));
            } catch (Throwable $retry) {
                throw new RuntimeException('Jeton invalide : ' . $retry->getMessage(), 0, $retry);
            }
        }

        if (!in_array($claims['iss'] ?? null, $settings['issuers'], true)) {
            throw new RuntimeException('Émetteur du jeton invalide.');
        }

        $tokenAudiences = (array) ($claims['aud'] ?? []);
        if (empty(array_intersect($tokenAudiences, $audiences))) {
            throw new RuntimeException('Jeton émis pour une autre application.');
        }

        if (empty($claims['sub'])) {
            throw new RuntimeException('Identifiant du compte absent du jeton.');
        }

        // Google et Apple envoient `email_verified` en booléen ou en chaîne.
        $verified = $claims['email_verified'] ?? false;
        $verified = $verified === true || $verified === 'true' || $verified === 1;

        return [
            'sub' => (string) $claims['sub'],
            'email' => isset($claims['email']) ? strtolower((string) $claims['email']) : null,
            'email_verified' => $verified,
            'name' => isset($claims['name']) ? (string) $claims['name'] : null,
        ];
    }

    private function keys(string $provider, string $url): array
    {
        $jwks = Cache::remember($this->cacheKey($provider), now()->addHours(6), function () use ($url) {
            $response = Http::timeout(10)->get($url);
            if (!$response->successful()) {
                throw new RuntimeException('Clés publiques du fournisseur indisponibles.');
            }

            return $response->json();
        });

        // Les JWKS de Google n'indiquent pas toujours l'algorithme : RS256 par défaut.
        return JWK::parseKeySet($jwks, 'RS256');
    }

    private function cacheKey(string $provider): string
    {
        return "social_auth_jwks_{$provider}";
    }
}
