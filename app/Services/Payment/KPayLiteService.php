<?php

namespace App\Services\Payment;

use App\Jobs\ProcessDepositPolling;
use App\Models\Company;
use App\Models\Payment;
use App\Models\User;
use App\Services\CurrencyService;
use App\Services\WalletNotifier;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Paiement par carte bancaire (Visa / Mastercard) via KPay Lite
 * (https://lite.kpay.site), compte distinct de KPay Mobile Money.
 *
 * Mode GATEWAY uniquement : on initialise, KPay Lite renvoie une `gatewayUrl`
 * (page hébergée ouverte dans la WebView de l'app). Le verdict arrive par
 * webhook signé (`/api/webhooks/kpay-lite`), avec un polling de secours.
 * La redirection de retour n'est qu'un signal d'affichage : elle ne crédite
 * jamais rien.
 *
 * Les paiements gardent `provider = kpay` (même wallet Mobile Money, mêmes
 * rapports) et sont marqués `metadata.kpay_account = lite` : c'est ce marqueur
 * qui aiguille statut, polling et webhook vers le bon compte.
 */
class KPayLiteService
{
    public const ACCOUNT = 'lite';

    /** Marge appliquée au taux USD pour rester au-dessus du plancher de 1 USD. */
    protected const USD_MARGIN = 1.05;

    protected KPayLiteConfig $config;
    protected KPayClient $client;

    /**
     * @param KPayLiteConfig|null $config Non typé pour que le conteneur
     *   (app(...)) n'injecte pas une configuration construite à vide.
     */
    public function __construct($config = null)
    {
        $this->config = $config instanceof KPayLiteConfig ? $config : KPayLiteConfig::make();
        $this->client = KPayClient::forLite($this->config);
    }

    public function config(): KPayLiteConfig
    {
        return $this->config;
    }

    /** KPay Lite doit-il porter les paiements carte ? (activé + clés présentes) */
    public static function isActive(): bool
    {
        return KPayLiteConfig::make()->isActive();
    }

    /** Ce paiement a-t-il été initié sur le compte KPay Lite ? */
    public static function isLitePayment(?Payment $payment): bool
    {
        if (!$payment) {
            return false;
        }

        $metadata = is_array($payment->metadata) ? $payment->metadata : [];

        return ($metadata['kpay_account'] ?? null) === self::ACCOUNT;
    }

    /**
     * Montant minimum accepté par carte (XAF) : le plus grand entre le minimum
     * configuré et l'équivalent de 1 USD (+5 %) au taux du jour, quand il est
     * connu. KPay Lite refuse sinon le paiement (400 `amount`).
     */
    public function minAmountXaf(): int
    {
        $min = $this->config->configuredMinAmountXaf();

        try {
            $oneUsd = app(CurrencyService::class)->convert(1, 'USD', 'XAF');
            if ($oneUsd > 0) {
                $min = max($min, (int) (ceil($oneUsd * self::USD_MARGIN / 50) * 50));
            }
        } catch (\Throwable $e) {
            // Pas de taux : on s'en tient au minimum configuré.
        }

        return $min;
    }

    // =====================================================================
    //  INITIALISATION
    // =====================================================================

    /**
     * Initie un dépôt par carte sur la page hébergée KPay Lite.
     *
     * Chaque appel crée un NOUVEAU Payment avec un NOUVEL externalId : KPay
     * Lite refuse un externalId déjà vu (409), un « Réessayer » côté app est
     * donc toujours une nouvelle tentative.
     *
     * @return array{payment: Payment, gateway_url: string, expires_at: string|null, return_url: string, is_test: bool}
     * @throws KPayException
     */
    public function initCardDeposit(
        User|Company $payer,
        float $amount,
        string $description,
        $payable = null,
        ?string $paymentType = null,
        ?string $customerEmail = null,
        string $currency = 'XAF'
    ): array {
        if (!$this->config->isConfigured()) {
            throw new KPayException(__('wallet.card_unavailable'), 0, 'NOT_CONFIGURED');
        }

        $minAmount = $this->minAmountXaf();
        if ($amount < $minAmount) {
            throw new KPayException(
                __('wallet.card_min_amount', ['amount' => number_format($minAmount, 0, ',', ' ')]),
                0,
                'CARD_AMOUNT_TOO_LOW'
            );
        }

        $externalId = $this->newExternalId();
        $returnUrl = route('api.payments.kpay-lite.return');

        $payment = DB::transaction(function () use ($payer, $amount, $description, $externalId, $payable, $paymentType, $currency) {
            $data = [
                'amount' => $amount,
                'fees' => 0,
                'total' => $amount,
                'description' => $description,
                'external_id' => $externalId,
                'status' => 'pending',
                'provider' => 'kpay',
                'payment_method' => 'card',
                'payment_type' => $paymentType,
                'currency' => $currency,
                'metadata' => [
                    'kpay_account' => self::ACCOUNT,
                    'kpay_mode' => 'GATEWAY',
                    'kpay_payment_method' => 'CARD',
                    'kpay_is_test' => $this->config->isTest(),
                ],
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

        Log::info('[KPayLite] Init dépôt carte', [
            'payment_id' => $payment->id,
            'amount' => $amount,
            'external_id' => $externalId,
            'is_test' => $this->config->isTest(),
        ]);

        // Mode GATEWAY : ni phoneNumber, ni provider, ni customerName (400).
        $body = [
            'amount' => (int) round($amount),
            'currency' => $currency,
            'paymentMethod' => 'CARD',
            'externalId' => $externalId,
            'returnUrl' => $returnUrl,
            'description' => KPayService::MERCHANT_LABEL,
            'metadata' => ['payment_id' => $payment->id],
        ];

        if ($customerEmail && filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $body['customerEmail'] = $customerEmail;
        }

        try {
            $response = $this->client->post('payments/init', $body);
        } catch (KPayException $e) {
            $mapped = $this->mapInitError($e);
            $payment->markAsFailed($mapped->getMessage());
            throw $mapped;
        }

        $kpayId = $response['id'] ?? null;
        $gatewayUrl = $response['gatewayUrl'] ?? null;

        if (!$kpayId || !$gatewayUrl) {
            $payment->markAsFailed('Réponse KPay Lite sans gatewayUrl');
            throw new KPayException(__('wallet.card_gateway_unavailable'), 0, 'NO_GATEWAY_URL', true);
        }

        $metadata = $payment->metadata ?? [];
        $metadata['kpay_reference'] = $response['reference'] ?? null;
        $metadata['kpay_is_test'] = (bool) ($response['isTest'] ?? $this->config->isTest());

        $payment->update([
            'provider_reference' => $kpayId,
            'payment_provider_response' => $response,
            'metadata' => $metadata,
        ]);

        // Polling de secours (le webhook reste la source de vérité). Le job
        // se replanifie lui-même jusqu'au verdict ou à l'expiration.
        ProcessDepositPolling::dispatch($payment->id)->delay(now()->addSeconds(30));

        Log::info('[KPayLite] Dépôt carte initié', ['payment_id' => $payment->id, 'kpay_id' => $kpayId]);

        return [
            'payment' => $payment->fresh(),
            'gateway_url' => $gatewayUrl,
            'expires_at' => $response['expiresAt'] ?? null,
            'return_url' => $returnUrl,
            'is_test' => (bool) ($response['isTest'] ?? $this->config->isTest()),
        ];
    }

    /**
     * Traduit une erreur d'initialisation KPay Lite en message client clair.
     */
    protected function mapInitError(KPayException $e): KPayException
    {
        $body = $e->responseBody;
        $haystack = strtolower(json_encode($body) . ' ' . $e->getMessage());

        [$code, $message, $retryable] = match (true) {
            $e->statusCode === 409 => ['CARD_DUPLICATE', __('wallet.card_duplicate_attempt'), true],
            $e->statusCode === 400 && str_contains($haystack, 'paymentmethod') => ['CARD_NOT_ENABLED', __('wallet.card_unavailable'), false],
            $e->statusCode === 400 && str_contains($haystack, 'amount') => [
                'CARD_AMOUNT_TOO_LOW',
                __('wallet.card_min_amount', ['amount' => number_format($this->minAmountXaf(), 0, ',', ' ')]),
                false,
            ],
            in_array($e->statusCode, [401, 403], true), $e->kpayCode === 'NOT_CONFIGURED' => ['CARD_NOT_CONFIGURED', __('wallet.card_unavailable'), false],
            $e->statusCode === 429, $e->statusCode >= 500, $e->statusCode === 0 => ['CARD_GATEWAY_ERROR', __('wallet.card_gateway_unavailable'), true],
            default => ['CARD_INIT_FAILED', __('wallet.card_init_failed'), true],
        };

        Log::warning('[KPayLite] Init refusée', [
            'http_status' => $e->statusCode,
            'kpay_code' => $e->kpayCode,
            'mapped_code' => $code,
            'detail' => $e->getMessage(),
        ]);

        $mapped = new KPayException($message, $e->statusCode, $code, $retryable, $e);
        $mapped->responseBody = $body;

        return $mapped;
    }

    // =====================================================================
    //  STATUT / VERDICT
    // =====================================================================

    /**
     * GET /api/v1/payments/:id (UUID ou référence encodée).
     * @throws KPayException
     */
    public function checkPaymentStatus(string $kpayPaymentId): array
    {
        return $this->client->get('payments/' . rawurlencode($kpayPaymentId));
    }

    /**
     * Applique un statut KPay Lite à un Payment, de façon idempotente.
     *
     * COMPLETED crédite (verrou + garde de statut dans WalletService) ;
     * FAILED / CANCELLED clôt avec le `failureReason` client fourni par KPay.
     * Pas de fenêtre de grâce USSD ici : un refus carte est définitif.
     *
     * @return string|null statut local résultant si changé (`completed`/`failed`), sinon null
     */
    public function applyStatus(Payment $payment, array $data, string $source): ?string
    {
        $status = strtoupper((string) ($data['status'] ?? ''));

        if ($status === 'COMPLETED') {
            // Garde-fou : dans la même devise, le montant encaissé ne peut pas
            // être inférieur au montant demandé (la carte, elle, est réglée en
            // USD : on ne compare que lorsque la devise annoncée est la nôtre).
            $paidAmount = $data['amount'] ?? null;
            $paidCurrency = strtoupper((string) ($data['currency'] ?? ''));
            if (is_numeric($paidAmount) && $paidCurrency !== ''
                && $paidCurrency === strtoupper((string) $payment->currency)
                && (float) $paidAmount + 1 < (float) $payment->amount) {
                Log::error("[KPayLite] {$source} COMPLETED avec un montant inférieur, crédit bloqué", [
                    'payment_id' => $payment->id,
                    'expected' => $payment->amount,
                    'received' => $paidAmount,
                    'currency' => $paidCurrency,
                ]);

                return null;
            }

            $credited = app(WalletService::class)->completeRechargeForPayment($payment);
            if ($credited) {
                app(WalletNotifier::class)->rechargeSuccess($payment->fresh());
            }
            Log::info("🟢 [KPayLite] {$source} COMPLETED", ['payment_id' => $payment->id, 'credited' => $credited]);

            return 'completed';
        }

        if (in_array($status, ['FAILED', 'CANCELLED', 'CANCELED'], true)) {
            $fresh = $payment->fresh();
            if (!$fresh || !$fresh->isPending()) {
                return null;
            }

            $reason = $data['failureReason'] ?? null;
            $reason = is_string($reason) && trim($reason) !== ''
                ? Str::limit(trim($reason), 250)
                : ($status === 'FAILED' ? 'declined' : 'cancelled');

            // Mise à jour conditionnelle : un webhook COMPLETED concurrent ne
            // doit jamais être écrasé par un échec.
            $updated = Payment::whereKey($fresh->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'failed',
                    'failure_reason' => $reason,
                    'payment_provider_response' => json_encode($data),
                    'updated_at' => now(),
                ]);
            if (!$updated) {
                return null;
            }
            app(WalletNotifier::class)->rechargeFailed($fresh->fresh());
            Log::info("🔴 [KPayLite] {$source} {$status}", ['payment_id' => $payment->id, 'reason' => $reason]);

            return 'failed';
        }

        if ($status === 'REFUNDED') {
            // Remboursement à traiter manuellement : on ne débite pas le wallet
            // automatiquement (le solde a pu être dépensé entre-temps).
            Log::warning("[KPayLite] {$source} REFUNDED — à rapprocher manuellement", ['payment_id' => $payment->id]);
        }

        return null;
    }

    /**
     * Interroge KPay Lite et applique le verdict ; clôt le paiement s'il est
     * abandonné depuis trop longtemps. Utilisé par l'endpoint de statut et le
     * polling de secours.
     */
    public function refreshPayment(Payment $payment, string $source = 'status-check'): void
    {
        if (!$payment->isPending() || !$payment->provider_reference) {
            $this->expireIfAbandoned($payment);

            return;
        }

        try {
            $response = $this->checkPaymentStatus($payment->provider_reference);
            if ($this->applyStatus($payment, $response, $source) !== null) {
                return;
            }
        } catch (KPayException $e) {
            Log::warning("[KPayLite] {$source} statut indisponible: {$e->getMessage()}", ['payment_id' => $payment->id]);
        }

        $this->expireIfAbandoned($payment->fresh() ?? $payment, false);
    }

    /**
     * Un paiement abandonné sur la page hébergée reste PENDING chez KPay Lite,
     * sans webhook. Passé `expiry_minutes`, on le clôt en `failed` / `expired`
     * — après une dernière vérification, pour ne jamais rater un paiement payé
     * au dernier moment.
     *
     * @return bool true si le paiement a été expiré
     */
    public function expireIfAbandoned(Payment $payment, bool $checkFirst = true): bool
    {
        if (!$payment->isPending() || !self::isLitePayment($payment)) {
            return false;
        }

        if ($payment->created_at && $payment->created_at->gt(now()->subMinutes($this->config->expiryMinutes()))) {
            return false;
        }

        if ($checkFirst && $payment->provider_reference) {
            try {
                $response = $this->checkPaymentStatus($payment->provider_reference);
                if ($this->applyStatus($payment, $response, 'expiry-check') !== null) {
                    return false;
                }
            } catch (KPayException $e) {
                // 404/erreur réseau : on expire quand même, un paiement réel
                // arrivé plus tard reste créditable par webhook.
                Log::warning('[KPayLite] Vérification avant expiration impossible: ' . $e->getMessage(), ['payment_id' => $payment->id]);
            }
        }

        $expired = Payment::whereKey($payment->id)
            ->where('status', 'pending')
            ->update(['status' => 'failed', 'failure_reason' => 'expired', 'updated_at' => now()]);

        if ($expired) {
            Log::info('[KPayLite] Paiement carte abandonné → expired', ['payment_id' => $payment->id]);
        }

        return (bool) $expired;
    }

    // =====================================================================
    //  SIGNATURES
    // =====================================================================

    /**
     * Retour de passerelle : HMAC-SHA256 hex de `status|reference|externalId|ts`
     * avec le secret passerelle Lite. `ts` de plus de 10 min rejeté.
     */
    public function verifyGatewaySignature(
        string $status,
        string $reference,
        string $externalId,
        string $timestamp,
        string $signature
    ): bool {
        $secret = $this->config->gatewaySecret();

        if (!$secret || $signature === '' || $timestamp === '' || !ctype_digit($timestamp)) {
            return false;
        }

        // `ts` peut arriver en millisecondes ou en secondes.
        $ts = (int) $timestamp;
        $tsSeconds = $ts > 100000000000 ? intdiv($ts, 1000) : $ts;
        if (abs(now()->timestamp - $tsSeconds) > 600) {
            Log::warning('[KPayLite] Retour passerelle expiré', ['age_seconds' => now()->timestamp - $tsSeconds]);

            return false;
        }

        $expected = hash_hmac('sha256', "{$status}|{$reference}|{$externalId}|{$timestamp}", $secret);

        return hash_equals($expected, strtolower(trim($signature)));
    }

    /**
     * Webhook : HMAC-SHA256 hex du corps BRUT avec le secret webhook Lite.
     */
    public function verifyWebhookSignature(string $rawBody, string $signature): bool
    {
        $secret = $this->config->webhookSecret();

        if (!$secret || $signature === '') {
            return false;
        }

        $sig = strtolower(trim(preg_replace('/^(sha256|hmac-sha256)=/i', '', trim($signature))));

        return hash_equals(hash_hmac('sha256', $rawBody, $secret), $sig);
    }

    // =====================================================================
    //  ADMIN
    // =====================================================================

    /**
     * « Tester la connexion » : GET d'un paiement inexistant. Sans effet de
     * bord : 404 = clés acceptées (et environnement cohérent), 401/403 = clés
     * refusées.
     */
    public function testConnection(): array
    {
        $environment = $this->config->environment();

        if (!$this->config->isConfigured()) {
            return ['success' => false, 'message' => 'Configuration KPay Lite incomplète (API key / secret key).', 'data' => null];
        }

        try {
            $this->client->get('payments/00000000-0000-4000-8000-000000000000', 15);

            return [
                'success' => true,
                'message' => 'Connexion KPay Lite réussie (' . $this->environmentLabel($environment) . ').',
                'data' => ['environment' => $environment],
            ];
        } catch (KPayException $e) {
            return match (true) {
                $e->statusCode === 404 => [
                    'success' => true,
                    'message' => 'Connexion KPay Lite réussie : clés acceptées (' . $this->environmentLabel($environment) . ').',
                    'data' => ['environment' => $environment],
                ],
                in_array($e->statusCode, [401, 403], true) => [
                    'success' => false,
                    'message' => "Clés KPay Lite refusées (HTTP {$e->statusCode}). Vérifiez l'API key et la secret key.",
                    'data' => null,
                ],
                $e->statusCode === 429 => [
                    'success' => false,
                    'message' => 'KPay Lite limite les requêtes (429). Réessayez dans une minute.',
                    'data' => null,
                ],
                default => [
                    'success' => false,
                    'message' => "Échec du test KPay Lite (HTTP {$e->statusCode}) : {$e->getMessage()}",
                    'data' => null,
                ],
            };
        }
    }

    protected function environmentLabel(string $environment): string
    {
        return match ($environment) {
            'sandbox' => 'Sandbox',
            'live' => 'Live',
            default => 'environnement indéterminé',
        };
    }

    protected function newExternalId(): string
    {
        do {
            $externalId = 'CARD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(8));
        } while (Payment::where('external_id', $externalId)->exists());

        return $externalId;
    }
}
