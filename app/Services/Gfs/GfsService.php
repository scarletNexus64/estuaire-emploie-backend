<?php

namespace App\Services\Gfs;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Intégration GFSolutions (G-Financials).
 *
 * Crée un compte bancaire GFS offert à l'utilisateur lors de la souscription
 * d'un pack incluant l'avantage `gfs_free_account`. L'appel est idempotent :
 * si l'utilisateur possède déjà un compte (persisté sur `users`), on renvoie
 * ses infos sans rappeler l'API.
 *
 * Le tableau retourné par onboardClient() suit le format attendu par le
 * frontend (clés camelCase : clientNumber, accountNumber, phone,
 * amountDebited, alreadyExists, message).
 */
class GfsService
{
    protected ?string $baseUrl;
    protected ?string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.gfs.base_url'), '/');
        $this->apiKey = config('services.gfs.api_key');
        $this->timeout = (int) config('services.gfs.timeout', 30);
    }

    /**
     * Le service est-il configuré (clé API présente) ?
     */
    public function isConfigured(): bool
    {
        return !empty($this->baseUrl) && !empty($this->apiKey);
    }

    /**
     * Onboarde l'utilisateur chez GFSolutions (ou renvoie son compte existant).
     *
     * @param array $extra Infos complémentaires collectées à la souscription :
     *                     ['gender' => 'M'|'F', 'city' => string, 'region' => string].
     *                     Absentes du profil utilisateur, donc fournies par l'app.
     *
     * @return array{
     *   success: bool, alreadyExists: bool, clientNumber: ?string,
     *   accountNumber: ?string, phone: ?string, amountDebited: ?float,
     *   message: ?string
     * }|null  null si non configuré ou échec (l'appelant doit le gérer
     *         comme non bloquant : la souscription est déjà payée).
     */
    public function onboardClient(User $user, array $extra = []): ?array
    {
        // Idempotence : compte déjà onboardé → renvoyer les infos persistées
        if ($user->hasGfsAccount()) {
            return [
                'success' => true,
                'alreadyExists' => true,
                'clientNumber' => $user->gfs_client_number,
                'accountNumber' => $user->gfs_account_number,
                'phone' => $user->gfs_phone,
                'amountDebited' => null,
                'message' => 'Compte GFSolutions déjà rattaché à votre profil.',
            ];
        }

        if (!$this->isConfigured()) {
            Log::warning('[GfsService] Skipped onboarding: GFS not configured', [
                'user_id' => $user->id,
            ]);
            return null;
        }

        [$firstName, $lastName] = $this->splitName($user->name);
        $phone = $this->normalizePhone($user->phone);

        // Infos complémentaires collectées à la souscription (app), car
        // absentes du profil utilisateur.
        $gender = $this->normalizeGender($extra['gender'] ?? null);
        $city = trim((string) ($extra['city'] ?? ''));
        $region = trim((string) ($extra['region'] ?? ''));

        $payload = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'phone' => $phone,
            'email' => $user->email,
            'gender' => $gender,
            'city' => $city,
            'region' => $region,
            'partnerUserId' => (string) $user->id,
        ];

        try {
            $url = $this->baseUrl . '/gateway/onboard-client';

            Log::info('[GfsService] Onboarding client', [
                'user_id' => $user->id,
                'phone' => $phone,
            ]);

            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout($this->timeout)->post($url, $payload);

            if (!$response->successful()) {
                Log::error('[GfsService] Onboarding failed (HTTP)', [
                    'user_id' => $user->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            if (!is_array($data) || ($data['success'] ?? false) !== true) {
                Log::error('[GfsService] Onboarding rejected by GFS', [
                    'user_id' => $user->id,
                    'response' => $data,
                ]);
                return null;
            }

            $result = [
                'success' => true,
                'alreadyExists' => (bool) ($data['alreadyExists'] ?? false),
                'clientNumber' => $data['clientNumber'] ?? null,
                'accountNumber' => $data['accountNumber'] ?? null,
                'phone' => $data['phone'] ?? $phone,
                'amountDebited' => isset($data['amountDebited'])
                    ? (float) $data['amountDebited']
                    : null,
                'message' => $data['message'] ?? null,
            ];

            // Persister pour l'idempotence des futurs renouvellements
            $this->persist($user, $result);

            Log::info('[GfsService] Client onboarded', [
                'user_id' => $user->id,
                'client_number' => $result['clientNumber'],
                'already_exists' => $result['alreadyExists'],
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::error('[GfsService] Exception during onboarding', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Sauvegarde les identifiants GFS sur l'utilisateur.
     */
    protected function persist(User $user, array $result): void
    {
        try {
            $user->forceFill([
                'gfs_client_number' => $result['clientNumber'],
                'gfs_account_number' => $result['accountNumber'],
                'gfs_phone' => $result['phone'],
                'gfs_onboarded_at' => now(),
            ])->save();
        } catch (\Throwable $e) {
            // La création du compte côté GFS a réussi ; un échec de persistance
            // ne doit pas masquer la réponse. On loggue seulement.
            Log::error('[GfsService] Could not persist GFS account on user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Découpe le nom complet en prénom / nom.
     * Le premier mot devient le prénom, le reste le nom.
     */
    protected function splitName(?string $fullName): array
    {
        $fullName = trim((string) $fullName);
        if ($fullName === '') {
            return ['', ''];
        }

        $parts = preg_split('/\s+/', $fullName);
        $firstName = array_shift($parts);
        $lastName = implode(' ', $parts);

        // Si un seul mot, réutiliser comme nom aussi (GFS exige les deux)
        if ($lastName === '') {
            $lastName = $firstName;
        }

        return [$firstName, $lastName];
    }

    /**
     * Normalise le genre au format attendu par GFS ('M' / 'F').
     * Accepte M/F/H, homme/femme, male/female (insensible à la casse).
     * Retourne '' si non reconnu.
     */
    protected function normalizeGender(?string $gender): string
    {
        $g = strtolower(trim((string) $gender));
        if ($g === '') {
            return '';
        }

        if (in_array($g, ['m', 'h', 'homme', 'male', 'man'], true)) {
            return 'M';
        }
        if (in_array($g, ['f', 'femme', 'female', 'woman'], true)) {
            return 'F';
        }

        return '';
    }

    /**
     * Normalise le numéro au format international camerounais (237XXXXXXXXX),
     * sans le « + » (cohérent avec l'exemple de payload GFS).
     */
    protected function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $phone);

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '237')) {
            return $digits;
        }

        // Numéro local camerounais (commence par 6, 9 chiffres) → préfixer 237
        if (strlen($digits) === 9 && str_starts_with($digits, '6')) {
            return '237' . $digits;
        }

        return $digits;
    }
}
