<?php

namespace App\Services\InsamIa;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Client HTTP bas niveau pour INSAM-IA (insam-ia.com).
 *
 * Deux mécanismes d'authentification coexistent côté INSAM-IA :
 *  - un en-tête `X-API-Key` pour les routes `/api/external/*` ;
 *  - un token Sanctum, obtenu par `/api/login`, pour les routes applicatives
 *    (épreuves, fiches de révision, profil).
 *
 * Le token est mis en cache et renouvelé automatiquement : un 401 déclenche
 * une nouvelle authentification puis un unique réessai. Toute défaillance du
 * service tiers remonte en [InsamIaException] afin que l'API Estuaire réponde
 * « service indisponible » au lieu de propager une erreur serveur.
 */
class InsamIaClient
{
    private const TOKEN_CACHE_KEY = 'insam_ia:token';

    /**
     * Le token Sanctum d'INSAM-IA n'expose pas sa date d'expiration : on le
     * garde un temps raisonnable, le renouvellement sur 401 servant de filet.
     */
    private const TOKEN_CACHE_TTL = 43200; // 12 h

    private string $baseUrl;
    private ?string $apiKey;
    private ?string $email;
    private ?string $password;
    private int $timeout;
    private int $generationTimeout;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.insam_ia.base_url'), '/');
        $this->apiKey = config('services.insam_ia.api_key');
        $this->email = config('services.insam_ia.email');
        $this->password = config('services.insam_ia.password');
        $this->timeout = (int) config('services.insam_ia.timeout', 30);
        $this->generationTimeout = (int) config('services.insam_ia.generation_timeout', 180);
    }

    /**
     * L'intégration est-elle exploitable ?
     *
     * Sans identifiants, tout l'espace INSAM-IA se désactive proprement au
     * lieu d'échouer appel après appel.
     */
    public function isConfigured(): bool
    {
        return $this->baseUrl !== ''
            && !empty($this->email)
            && !empty($this->password);
    }

    /**
     * Requête publique : ni token, ni clé API (routes `/api/public/*`).
     *
     * @throws InsamIaException
     */
    public function getPublic(string $path, array $query = []): array
    {
        return $this->send(
            fn (PendingRequest $request) => $request->get($this->url($path), $query),
            $path
        );
    }

    /**
     * Requête authentifiée par token Sanctum, avec renouvellement sur 401.
     *
     * @throws InsamIaException
     */
    public function get(string $path, array $query = [], ?int $timeout = null): array
    {
        return $this->sendAuthenticated(
            fn (PendingRequest $request) => $request->get($this->url($path), $query),
            $path,
            $timeout
        );
    }

    /**
     * @throws InsamIaException
     */
    public function post(string $path, array $payload = [], ?int $timeout = null): array
    {
        return $this->sendAuthenticated(
            fn (PendingRequest $request) => $request->post($this->url($path), $payload),
            $path,
            $timeout
        );
    }

    /**
     * Requête non authentifiée en POST (sessions d'évaluation publiques).
     *
     * @throws InsamIaException
     */
    public function postPublic(string $path, array $payload = [], ?int $timeout = null): array
    {
        return $this->send(
            fn (PendingRequest $request) => $request->post($this->url($path), $payload),
            $path,
            $timeout
        );
    }

    /**
     * Requête sur `/api/external/*`, authentifiée par clé API.
     *
     * @throws InsamIaException
     */
    public function postExternal(string $path, array $payload = []): array
    {
        if (empty($this->apiKey)) {
            throw InsamIaException::notConfigured();
        }

        return $this->send(
            fn (PendingRequest $request) => $request
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->post($this->url($path), $payload),
            $path
        );
    }

    /**
     * Télécharge un fichier binaire (sujet d'épreuve, correction).
     *
     * @return array{body: string, content_type: string, filename: ?string}
     *
     * @throws InsamIaException
     */
    public function download(string $path): array
    {
        $this->assertConfigured();

        $perform = fn (string $token) => $this->request($this->timeout)
            ->withToken($token)
            ->get($this->url($path));

        try {
            $response = $perform($this->token());

            // Token périmé : on le renouvelle puis on réessaie une seule fois.
            if ($response->status() === 401) {
                $response = $perform($this->token(forceRefresh: true));
            }
        } catch (ConnectionException $e) {
            throw $this->connectionFailure($path, $e);
        } catch (Throwable $e) {
            throw $this->unexpectedFailure($path, $e);
        }

        $this->assertSuccessful($response, $path);

        return [
            'body' => $response->body(),
            'content_type' => $response->header('Content-Type') ?: 'application/octet-stream',
            'filename' => $this->filenameFromDisposition($response->header('Content-Disposition')),
        ];
    }

    /**
     * Délai à utiliser pour les appels de génération de contenu par IA, qui
     * durent couramment plus d'une minute.
     */
    public function generationTimeout(): int
    {
        return $this->generationTimeout;
    }

    /**
     * Invalide le token mis en cache (utile après un échec d'authentification).
     */
    public function forgetToken(): void
    {
        Cache::forget(self::TOKEN_CACHE_KEY);
    }

    /**
     * Token Sanctum courant, authentifiant si besoin.
     *
     * @throws InsamIaException
     */
    private function token(bool $forceRefresh = false): string
    {
        if ($forceRefresh) {
            $this->forgetToken();
        }

        $cached = Cache::get(self::TOKEN_CACHE_KEY);

        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $token = $this->authenticate();
        Cache::put(self::TOKEN_CACHE_KEY, $token, self::TOKEN_CACHE_TTL);

        return $token;
    }

    /**
     * @throws InsamIaException
     */
    private function authenticate(): string
    {
        $this->assertConfigured();

        try {
            $response = $this->request($this->timeout)->post($this->url('/api/login'), [
                'email' => $this->email,
                'password' => $this->password,
            ]);
        } catch (ConnectionException $e) {
            throw $this->connectionFailure('/api/login', $e);
        } catch (Throwable $e) {
            throw $this->unexpectedFailure('/api/login', $e);
        }

        $token = $response->json('token');

        if (!$response->successful() || !is_string($token) || $token === '') {
            Log::warning('INSAM-IA : authentification refusée', [
                'status' => $response->status(),
            ]);

            throw InsamIaException::unavailable(
                "Authentification INSAM-IA impossible.",
                $response->status()
            );
        }

        return $token;
    }

    /**
     * Exécute une requête authentifiée, en renouvelant le token sur 401.
     *
     * @param  callable(PendingRequest): Response  $perform
     *
     * @throws InsamIaException
     */
    private function sendAuthenticated(callable $perform, string $path, ?int $timeout = null): array
    {
        $this->assertConfigured();

        try {
            $response = $perform(
                $this->request($timeout)->withToken($this->token())
            );

            if ($response->status() === 401) {
                $response = $perform(
                    $this->request($timeout)->withToken($this->token(forceRefresh: true))
                );
            }
        } catch (InsamIaException $e) {
            throw $e;
        } catch (ConnectionException $e) {
            throw $this->connectionFailure($path, $e);
        } catch (Throwable $e) {
            throw $this->unexpectedFailure($path, $e);
        }

        $this->assertSuccessful($response, $path);

        return $response->json() ?? [];
    }

    /**
     * Exécute une requête sans token.
     *
     * @param  callable(PendingRequest): Response  $perform
     *
     * @throws InsamIaException
     */
    private function send(callable $perform, string $path, ?int $timeout = null): array
    {
        if ($this->baseUrl === '') {
            throw InsamIaException::notConfigured();
        }

        try {
            $response = $perform($this->request($timeout));
        } catch (ConnectionException $e) {
            throw $this->connectionFailure($path, $e);
        } catch (Throwable $e) {
            throw $this->unexpectedFailure($path, $e);
        }

        $this->assertSuccessful($response, $path);

        return $response->json() ?? [];
    }

    private function request(?int $timeout = null): PendingRequest
    {
        return Http::acceptJson()
            ->timeout($timeout ?? $this->timeout)
            // Un seul réessai, réservé aux coupures réseau passagères. Les
            // réponses applicatives ne sont jamais rejouées : un 401 doit
            // remonter jusqu'ici pour déclencher le renouvellement du token,
            // et rejouer un 4xx avec les mêmes paramètres est inutile.
            ->retry(
                2,
                300,
                fn (Throwable $e) => $e instanceof ConnectionException,
                throw: false
            );
    }

    private function url(string $path): string
    {
        return $this->baseUrl . '/' . ltrim($path, '/');
    }

    /**
     * @throws InsamIaException
     */
    private function assertConfigured(): void
    {
        if (!$this->isConfigured()) {
            throw InsamIaException::notConfigured();
        }
    }

    /**
     * @throws InsamIaException
     */
    private function assertSuccessful(Response $response, string $path): void
    {
        if ($response->successful()) {
            return;
        }

        if ($response->status() === 404) {
            throw InsamIaException::notFound("Ressource INSAM-IA introuvable : {$path}");
        }

        Log::warning('INSAM-IA : réponse en erreur', [
            'path' => $path,
            'status' => $response->status(),
            'body' => mb_substr($response->body(), 0, 500),
        ]);

        throw InsamIaException::unavailable(
            "INSAM-IA a répondu une erreur ({$response->status()}) sur {$path}.",
            $response->status()
        );
    }

    private function connectionFailure(string $path, Throwable $e): InsamIaException
    {
        Log::warning('INSAM-IA : injoignable', [
            'path' => $path,
            'error' => $e->getMessage(),
        ]);

        return InsamIaException::unavailable("INSAM-IA est injoignable ({$path}).");
    }

    private function unexpectedFailure(string $path, Throwable $e): InsamIaException
    {
        Log::error('INSAM-IA : échec inattendu', [
            'path' => $path,
            'error' => $e->getMessage(),
        ]);

        return InsamIaException::unavailable("Appel INSAM-IA en échec ({$path}).");
    }

    /**
     * Extrait le nom de fichier d'un en-tête Content-Disposition.
     */
    private function filenameFromDisposition(?string $disposition): ?string
    {
        if (!$disposition) {
            return null;
        }

        if (preg_match('/filename\*?=(?:UTF-8\'\')?"?([^";]+)"?/i', $disposition, $matches)) {
            return trim(urldecode($matches[1]));
        }

        return null;
    }
}
