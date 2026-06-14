<?php

namespace App\Services\Payment;

use App\Jobs\ProcessDepositPolling;
use App\Models\Company;
use App\Models\Payment;
use App\Models\ServiceConfiguration;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service métier KPay (https://admin.kpay.site/api/v1).
 *
 * Remplace FreeMoPayService. Différences clés :
 *  - Auth double-header (pas de token Bearer)
 *  - initDeposit est ASYNCHRONE : retourne immédiatement un Payment "pending".
 *    Le crédit du wallet est finalisé par le webhook (source de vérité) ou le
 *    job ProcessDepositPolling (secours), via WalletService::completeRechargeForPayment().
 */
class KPayService
{
    protected ?ServiceConfiguration $config;
    protected KPayClient $client;

    /**
     * Libellé marchand envoyé à KPay (visible côté opérateur/relevé client).
     * On n'expose jamais "KPay" ni le détail interne ; seulement la marque.
     */
    public const MERCHANT_LABEL = 'Estuaire Emploi';

    public function __construct()
    {
        $this->config = ServiceConfiguration::getKPayConfig();
        $this->client = new KPayClient();
    }

    /**
     * Description envoyée dans le body KPay (tronquée si la longueur est limitée).
     */
    protected function merchantDescription(): string
    {
        return self::MERCHANT_LABEL;
    }

    // =====================================================================
    //  DÉPÔT (recharge wallet) — ASYNCHRONE
    // =====================================================================

    /**
     * Initie un dépôt (USSD). Crée le Payment, appelle KPay, retourne immédiatement
     * le Payment "pending". NE POLL PAS.
     *
     * @param  User|Company  $payer
     * @param  string|null   $providerCode  Code opérateur KPay (ex. MTN_MOMO_CMR). Dérivé si null.
     * @throws KPayException
     */
    public function initDeposit(
        User|Company $payer,
        float $amount,
        string $phoneNumber,
        string $description,
        ?string $externalId = null,
        $payable = null,
        ?string $paymentType = null,
        ?string $providerCode = null
    ): Payment {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new KPayException('Le service KPay n\'est pas configuré correctement.', 0, 'NOT_CONFIGURED');
        }

        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);
        $externalId = $this->ensureUniqueExternalId($externalId ?: $this->generateExternalId());
        $providerCode = $providerCode ?: $this->deriveProviderCode($normalizedPhone);

        Log::info('[KPay] Init dépôt', [
            'amount' => $amount,
            'provider' => $providerCode,
            'external_id' => $externalId,
        ]);

        // 1. Créer le Payment (pending) en base
        $payment = DB::transaction(function () use ($payer, $amount, $normalizedPhone, $description, $externalId, $payable, $paymentType, $providerCode) {
            $data = [
                'amount' => $amount,
                'fees' => 0,
                'total' => $amount,
                'phone_number' => $normalizedPhone,
                'description' => $description,
                'external_id' => $externalId,
                'status' => 'pending',
                'provider' => 'kpay',
                'payment_method' => $this->localPaymentMethod($providerCode),
                'payment_type' => $paymentType,
                'currency' => $this->currencyForProvider($providerCode),
                'metadata' => ['kpay_provider' => $providerCode],
            ];

            if ($payer instanceof Company) {
                $data['company_id'] = $payer->id;
            } elseif ($payer instanceof User) {
                $data['user_id'] = $payer->id;
            }

            if ($payable) {
                $data['payable_type'] = get_class($payable);
                $data['payable_id'] = $payable->id;
            }

            return Payment::create($data);
        });

        // 2. Appeler KPay /payments/init
        try {
            $response = $this->client->post('payments/init', [
                'amount' => (int) round($amount),
                'provider' => $providerCode,
                'phoneNumber' => $normalizedPhone,
                'externalId' => $externalId,
                'description' => $this->merchantDescription(),
                'metadata' => ['payment_id' => $payment->id],
            ]);

            $kpayId = $response['id'] ?? null;
            if (!$kpayId) {
                $payment->markAsFailed('Réponse KPay sans id de paiement');
                throw new KPayException('Réponse KPay invalide (id manquant).', 0, 'NO_PAYMENT_ID');
            }

            $payment->update([
                'provider_reference' => $kpayId,
                'payment_provider_response' => $response,
            ]);

            // 3. Job de polling de secours (le webhook reste la source de vérité)
            ProcessDepositPolling::dispatch($payment->id)->delay(now()->addSeconds(15));

            Log::info('[KPay] Dépôt initié', ['payment_id' => $payment->id, 'kpay_id' => $kpayId]);

            return $payment->fresh();
        } catch (KPayException $e) {
            $payment->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    /**
     * GET /payments/:id — statut d'un dépôt.
     * @throws KPayException
     */
    public function checkPaymentStatus(string $kpayPaymentId): array
    {
        return $this->client->get("payments/{$kpayPaymentId}");
    }

    // =====================================================================
    //  RETRAIT (payout) — déjà asynchrone via ProcessWithdrawalPolling
    // =====================================================================

    /**
     * POST /payments/withdraw — initie un retrait (USSD).
     * @throws KPayException
     */
    public function initWithdrawal(
        string $phoneNumber,
        float $amount,
        string $providerCode,
        ?string $externalId = null,
        ?string $description = null
    ): array {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new KPayException('Le service KPay n\'est pas configuré correctement.', 0, 'NOT_CONFIGURED');
        }

        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);

        $payload = [
            'amount' => (int) round($amount),
            'provider' => $providerCode,
            'phoneNumber' => $normalizedPhone,
        ];
        if ($externalId) {
            $payload['externalId'] = $externalId;
        }
        // Libellé marchand fixe envoyé à KPay (jamais le détail interne).
        $payload['description'] = $this->merchantDescription();

        return $this->client->post('payments/withdraw', $payload);
    }

    /**
     * GET /payments/withdraw/:id — statut d'un retrait.
     * @throws KPayException
     */
    public function checkWithdrawalStatus(string $kpayWithdrawId): array
    {
        return $this->client->get("payments/withdraw/{$kpayWithdrawId}");
    }

    // =====================================================================
    //  UTILITAIRES
    // =====================================================================

    /**
     * POST /payments/predict-provider — devine l'opérateur d'un numéro.
     * @throws KPayException
     */
    public function predictProvider(string $phoneNumber): array
    {
        return $this->client->post('payments/predict-provider', [
            'phoneNumber' => $this->normalizePhoneNumber($phoneNumber, false),
        ]);
    }

    /**
     * GET /payments/availability — état opérationnel des opérateurs (caché ~60s).
     * @throws KPayException
     */
    public function getAvailability(bool $fresh = false): array
    {
        if ($fresh) {
            Cache::forget('kpay_availability');
        }

        return Cache::remember('kpay_availability', now()->addSeconds(60), function () {
            return $this->client->get('payments/availability');
        });
    }

    /**
     * GET /payments/balance — solde du wallet KPay de l'application.
     * @throws KPayException
     */
    public function getBalance(): array
    {
        return $this->client->get('payments/balance');
    }

    /**
     * Teste la connexion KPay (utilisé par l'admin). Appelle /balance.
     */
    public function testConnection(): array
    {
        try {
            if (!$this->config || !$this->config->isConfigured()) {
                return ['success' => false, 'message' => 'Configuration KPay incomplète', 'data' => null];
            }

            $balance = $this->getBalance();

            return [
                'success' => true,
                'message' => 'Connexion KPay réussie',
                'data' => ['balances' => $balance, 'environment' => $this->config->kpay_environment],
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => "Échec du test: {$e->getMessage()}", 'data' => null];
        }
    }

    // =====================================================================
    //  HELPERS internes
    // =====================================================================

    /**
     * Normalise un numéro au format international (chiffres uniquement, sans +).
     * Cameroun par défaut si numéro local à 9 chiffres commençant par 6.
     *
     * @param  bool  $strict  Si true, valide la longueur (12 chiffres CMR/RDC).
     */
    public function normalizePhoneNumber(string $phone, bool $strict = true): string
    {
        if (!$phone) {
            throw new KPayException('Le numéro de téléphone est requis.', 0, 'PHONE_REQUIRED');
        }

        $cleaned = preg_replace('/[\s\-+()]/', '', $phone);

        // Numéro local Cameroun (9 chiffres, commence par 6) → préfixe 237
        if (strlen($cleaned) === 9 && str_starts_with($cleaned, '6')) {
            $cleaned = '237' . $cleaned;
        }

        if (!ctype_digit($cleaned)) {
            throw new KPayException("Format de numéro invalide: {$phone}", 0, 'INVALID_PHONE');
        }

        // En mode strict on garde la validation CMR/RDC (12 chiffres) pour
        // rester aligné avec le périmètre actuel ; pour le multi-pays on
        // accepte tout numéro international 8-15 chiffres.
        if ($strict && (str_starts_with($cleaned, '237') || str_starts_with($cleaned, '243'))) {
            if (strlen($cleaned) !== 12) {
                throw new KPayException("Numéro CMR/RDC attendu sur 12 chiffres: {$phone}", 0, 'INVALID_PHONE');
            }
        } elseif (strlen($cleaned) < 8 || strlen($cleaned) > 15) {
            throw new KPayException("Numéro international invalide (8-15 chiffres): {$phone}", 0, 'INVALID_PHONE');
        }

        return $cleaned;
    }

    /**
     * Dérive le code opérateur KPay depuis un numéro international normalisé.
     * Couvre le Cameroun (237) et la RDC (243). Pour les autres pays, retourne
     * null → le frontend doit fournir provider_code explicitement.
     */
    public function deriveProviderCode(string $intlPhone): ?string
    {
        // Cameroun (237) - XAF
        if (str_starts_with($intlPhone, '237')) {
            $p2 = substr($intlPhone, 3, 2);
            $p3 = substr($intlPhone, 3, 3);

            if (in_array($p2, ['67', '68']) || in_array($p3, ['650', '651', '652', '653', '654'])) {
                return 'MTN_MOMO_CMR';
            }
            if ($p2 === '69' || in_array($p3, ['655', '656', '657', '658', '659'])) {
                return 'ORANGE_CMR';
            }
            return 'MTN_MOMO_CMR'; // défaut CMR
        }

        // RDC (243)
        if (str_starts_with($intlPhone, '243')) {
            $p2 = substr($intlPhone, 3, 2);
            if (in_array($p2, ['81', '82', '83', '84', '85'])) {
                return 'AIRTEL_COD';
            }
            if (in_array($p2, ['89', '80'])) {
                return 'ORANGE_COD';
            }
        }

        return null;
    }

    /**
     * Famille de méthode locale (pour reporting/enum) à partir du code KPay.
     */
    protected function localPaymentMethod(?string $providerCode): string
    {
        if (!$providerCode) {
            return 'kpay';
        }
        if (str_starts_with($providerCode, 'MTN')) {
            return 'mtn_money';
        }
        if (str_starts_with($providerCode, 'ORANGE')) {
            return 'orange_money';
        }
        return 'kpay';
    }

    /**
     * Devise dérivée du code opérateur (XAF par défaut pour la zone CEMAC).
     */
    protected function currencyForProvider(?string $providerCode): string
    {
        if (!$providerCode) {
            return 'XAF';
        }
        // Suffixe pays → devise (sous-ensemble courant ; XAF par défaut)
        return match (true) {
            str_ends_with($providerCode, '_CMR'), str_ends_with($providerCode, '_GAB'),
            str_ends_with($providerCode, '_COG') => 'XAF',
            str_ends_with($providerCode, '_BEN'), str_ends_with($providerCode, '_CIV'),
            str_ends_with($providerCode, '_SEN'), str_ends_with($providerCode, '_BFA') => 'XOF',
            default => 'XAF',
        };
    }

    protected function generateExternalId(string $prefix = 'PAY'): string
    {
        return "{$prefix}-" . now()->format('YmdHis') . '-' . substr(uniqid(), -4);
    }

    protected function ensureUniqueExternalId(string $base): string
    {
        $externalId = $base;
        $counter = 1;
        while (Payment::where('external_id', $externalId)->exists()) {
            $externalId = "{$base}-{$counter}";
            $counter++;
        }
        return $externalId;
    }
}
