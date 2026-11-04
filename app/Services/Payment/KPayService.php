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

    /** Devise du wallet et des prix de l'app. */
    public const WALLET_CURRENCY = 'XAF';

    /**
     * Devises à parité fixe avec le FCFA CEMAC : le franc CFA UEMOA (XOF) et
     * le franc CFA CEMAC (XAF) sont tous deux arrimés à 655,957 par euro.
     */
    protected const FIXED_PARITY = ['XAF', 'XOF'];

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
     * @param  string|null   $countryIso3   Pays sélectionné dans l'app (ex. CMR), utilisé
     *                                      pour préfixer un numéro saisi au format national.
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
        ?string $providerCode = null,
        ?string $countryIso3 = null
    ): Payment {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new KPayException('Le service KPay n\'est pas configuré correctement.', 0, 'NOT_CONFIGURED');
        }

        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber, true, $countryIso3);
        $externalId = $this->ensureUniqueExternalId($externalId ?: $this->generateExternalId());
        $providerCode = $providerCode ?: $this->deriveProviderCode($normalizedPhone);

        // Un provider inconnu partait auparavant tel quel (voire `null`) dans le
        // payload : KPay répondait 400 et le Payment restait échoué sans motif
        // exploitable. On tranche ici, avant toute écriture en base.
        if (!KPayCatalog::isValidProvider($providerCode)) {
            throw new KPayException(
                'Opérateur indéterminé pour ce numéro. Sélectionnez votre opérateur.',
                0,
                'PROVIDER_REQUIRED'
            );
        }

        // `$amount` est en FCFA (devise du wallet et des prix). KPay débite
        // dans la devise de l'opérateur sans rien convertir : on convertit ici.
        $quote = $this->quoteForProvider((float) $amount, $providerCode, true);

        Log::info('[KPay] Init dépôt', [
            'amount' => $amount,
            'charged_amount' => $quote['amount'],
            'charged_currency' => $quote['currency'],
            'provider' => $providerCode,
            'external_id' => $externalId,
        ]);

        // 1. Créer le Payment (pending) en base
        $payment = DB::transaction(function () use ($payer, $amount, $normalizedPhone, $description, $externalId, $payable, $paymentType, $providerCode, $quote) {
            $metadata = ['kpay_provider' => $providerCode];
            if ($quote['converted']) {
                $metadata['currency_conversion'] = [
                    'original_amount' => (float) $amount,
                    'original_currency' => self::WALLET_CURRENCY,
                    'converted_amount' => $quote['amount'],
                    'converted_currency' => $quote['currency'],
                    'rate' => $quote['rate'],
                    'rate_source' => $quote['source'],
                ];
            }

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
                // `amount` reste en FCFA (c'est ce qui est crédité) ; le montant
                // réellement débité est dans metadata.currency_conversion.
                'currency' => self::WALLET_CURRENCY,
                'metadata' => $metadata,
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
                'amount' => $quote['amount'],
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

    // =====================================================================
    //  DÉPÔT PAR CARTE BANCAIRE (Visa / Mastercard) — passerelle hébergée
    // =====================================================================

    /**
     * Initie un dépôt par carte bancaire via la passerelle hébergée KPay.
     *
     * La carte n'existe pas en USSD : aucun push téléphone n'est possible. On
     * envoie donc `paymentMethod: CARD` + `returnUrl`, sans `provider` ni
     * `phoneNumber`, et KPay répond avec une `gatewayUrl` sur laquelle le
     * client saisit sa carte (l'app l'ouvre dans une WebView). Ce mode force la
     * passerelle même si l'application KPay est configurée en USSD.
     *
     * Le statut final n'est jamais connu de façon synchrone : il arrive par
     * webhook (source de vérité), avec le polling en secours. Le retour de
     * passerelle est seulement un signal de redirection, jamais une preuve de
     * paiement — d'où la vérification de signature dans `verifyGatewaySignature`.
     *
     * @return array{payment: Payment, gateway_url: string, expires_at: string|null}
     * @throws KPayException
     */
    public function initCardDeposit(
        User|Company $payer,
        float $amount,
        string $returnUrl,
        string $description,
        ?string $externalId = null,
        $payable = null,
        ?string $paymentType = null,
        ?string $customerEmail = null,
        ?string $cancelUrl = null,
        string $currency = 'XAF'
    ): array {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new KPayException('Le service KPay n\'est pas configuré correctement.', 0, 'NOT_CONFIGURED');
        }

        $externalId = $this->ensureUniqueExternalId($externalId ?: $this->generateExternalId('CARD'));

        Log::info('[KPay] Init dépôt carte', [
            'amount' => $amount,
            'external_id' => $externalId,
        ]);

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
                'metadata' => ['kpay_mode' => 'GATEWAY', 'kpay_payment_method' => 'CARD'],
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

        try {
            $body = [
                'amount' => (int) round($amount),
                'paymentMethod' => 'CARD',
                'externalId' => $externalId,
                'returnUrl' => $returnUrl,
                'description' => $this->merchantDescription(),
                'metadata' => ['payment_id' => $payment->id],
            ];

            if ($cancelUrl) {
                $body['cancelUrl'] = $cancelUrl;
            }

            if ($customerEmail) {
                $body['customerEmail'] = $customerEmail;
            }

            $response = $this->client->post('payments/init', $body);

            $kpayId = $response['id'] ?? null;
            $gatewayUrl = $response['gatewayUrl'] ?? null;

            if (!$kpayId || !$gatewayUrl) {
                $payment->markAsFailed('Réponse KPay sans gatewayUrl');
                throw new KPayException(
                    'La page de paiement par carte est indisponible. Réessayez dans quelques instants.',
                    0,
                    'NO_GATEWAY_URL'
                );
            }

            $payment->update([
                'provider_reference' => $kpayId,
                'payment_provider_response' => $response,
            ]);

            ProcessDepositPolling::dispatch($payment->id)->delay(now()->addSeconds(15));

            Log::info('[KPay] Dépôt carte initié', ['payment_id' => $payment->id, 'kpay_id' => $kpayId]);

            return [
                'payment' => $payment->fresh(),
                'gateway_url' => $gatewayUrl,
                'expires_at' => $response['expiresAt'] ?? null,
            ];
        } catch (KPayException $e) {
            $payment->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    /**
     * Vérifie la signature d'un retour de passerelle.
     *
     * La chaîne signée est `status|reference|externalId|ts`, en HMAC-SHA256 hex
     * avec le secret passerelle. Un `ts` de plus de 10 minutes est rejeté
     * (anti-rejeu). Même valide, cette signature n'autorise jamais à créditer
     * un wallet : elle dit seulement que la redirection vient bien de KPay.
     */
    public function verifyGatewaySignature(
        string $status,
        string $reference,
        string $externalId,
        string $timestamp,
        string $signature
    ): bool {
        $secret = $this->config->kpay_gateway_secret ?? $this->config->kpay_webhook_secret ?? null;

        if (!$secret) {
            Log::warning('[KPay] Signature passerelle non vérifiable : aucun secret configuré');

            return false;
        }

        // `ts` est en millisecondes dans la query de retour.
        $ageSeconds = abs((now()->timestamp * 1000 - (int) $timestamp) / 1000);
        if ($ageSeconds > 600) {
            Log::warning('[KPay] Retour passerelle expiré', ['age_seconds' => $ageSeconds]);

            return false;
        }

        $expected = hash_hmac('sha256', "{$status}|{$reference}|{$externalId}|{$timestamp}", $secret);

        return hash_equals($expected, $signature);
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

        // `$amount` est en FCFA : converti vers la devise de l'opérateur,
        // arrondi vers le bas pour ne jamais verser plus que le montant dû.
        $quote = $this->quoteForProvider((float) $amount, $providerCode, false);

        $payload = [
            'amount' => $quote['amount'],
            'provider' => $providerCode,
            'phoneNumber' => $normalizedPhone,
        ];
        if ($externalId) {
            $payload['externalId'] = $externalId;
        }
        // Libellé marchand fixe envoyé à KPay (jamais le détail interne).
        $payload['description'] = $this->merchantDescription();

        $response = $this->client->post('payments/withdraw', $payload);

        if ($quote['converted']) {
            $response['currency_conversion'] = [
                'original_amount' => (float) $amount,
                'original_currency' => self::WALLET_CURRENCY,
                'converted_amount' => $quote['amount'],
                'converted_currency' => $quote['currency'],
                'rate' => $quote['rate'],
                'rate_source' => $quote['source'],
            ];
        }

        return $response;
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
    public function normalizePhoneNumber(string $phone, bool $strict = true, ?string $countryIso3 = null): string
    {
        if (!$phone) {
            throw new KPayException('Le numéro de téléphone est requis.', 0, 'PHONE_REQUIRED');
        }

        $cleaned = preg_replace('/[\s\-+()]/', '', $phone);

        // Numéro saisi au format national (sans indicatif) : on préfixe avec
        // l'indicatif du pays sélectionné dans l'app.
        //
        // Le « 0 » de tête n'est retiré que là où il est un préfixe d'appel
        // national. En Côte d'Ivoire il appartient au numéro lui-même : KPay
        // attend bien `2250503456089`, et le supprimer casserait le paiement.
        if ($countryIso3 && ($country = KPayCatalog::country($countryIso3))) {
            $dial = $country['dial'];
            if (!str_starts_with($cleaned, $dial)) {
                $keepsLeadingZero = in_array(strtoupper($countryIso3), ['CIV', 'COG'], true);
                $cleaned = $dial . ($keepsLeadingZero ? $cleaned : ltrim($cleaned, '0'));
            }
        } elseif (strlen($cleaned) === 9 && str_starts_with($cleaned, '6')) {
            // Rétrocompatibilité : numéro local Cameroun (9 chiffres, commence par 6).
            $cleaned = '237' . $cleaned;
        }

        if (!ctype_digit($cleaned)) {
            throw new KPayException("Format de numéro invalide: {$phone}", 0, 'INVALID_PHONE');
        }

        // Le numéro doit appartenir à un pays couvert par KPay. La longueur
        // n'est plus figée à 12 chiffres (règle CMR appliquée à tort à la RDC,
        // dont les numéros font 12 ou 13 chiffres) : on valide l'indicatif,
        // puis une longueur internationale plausible.
        if ($strict) {
            $iso3 = KPayCatalog::countryForPhone($cleaned);
            if (!$iso3) {
                throw new KPayException(
                    "Pays non pris en charge pour ce numéro: {$phone}",
                    0,
                    'UNSUPPORTED_COUNTRY'
                );
            }
        }

        if (strlen($cleaned) < 8 || strlen($cleaned) > 15) {
            throw new KPayException("Numéro international invalide (8-15 chiffres): {$phone}", 0, 'INVALID_PHONE');
        }

        return $cleaned;
    }

    /**
     * Dérive le code opérateur KPay depuis un numéro international normalisé,
     * en s'appuyant sur le catalogue (12 pays, 23 opérateurs).
     *
     * Retourne null quand l'opérateur ne peut pas être tranché de façon fiable :
     * l'appelant doit alors exiger un `provider_code` explicite. L'ancien
     * fallback « tout numéro 237 inconnu → MTN » est supprimé, car il envoyait
     * des paiements Orange vers MTN (refus opérateur côté KPay).
     */
    public function deriveProviderCode(string $intlPhone): ?string
    {
        return KPayCatalog::guessProvider($intlPhone);
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
     * Devise dérivée du code opérateur, via le catalogue KPay.
     *
     * XAF ne sert plus de défaut aveugle : un provider hors catalogue
     * enregistrait auparavant un paiement CDF/KES/UGX/ZMW en XAF.
     */
    /**
     * Montant à envoyer à KPay pour un montant en FCFA, dans la devise de
     * l'opérateur (KPay ne convertit pas en mode USSD).
     *
     * Dépôt : arrondi vers le haut (on ne débite jamais moins que le crédit).
     * Retrait : arrondi vers le bas (on ne verse jamais plus que le débit).
     *
     * @return array{amount: int|float, currency: string, rate: float, converted: bool, source: string}
     * @throws KPayException si aucun taux fiable n'est disponible.
     */
    public function quoteForProvider(float $amountXaf, ?string $providerCode, bool $isDeposit = true): array
    {
        $currency = strtoupper($this->currencyForProvider($providerCode));

        if (in_array($currency, self::FIXED_PARITY, true)) {
            return [
                'amount' => $this->formatAmount($amountXaf, $providerCode),
                'currency' => $currency,
                'rate' => 1.0,
                'converted' => $currency !== self::WALLET_CURRENCY,
                'source' => 'parity',
            ];
        }

        [$rate, $source] = $this->exchangeRate(self::WALLET_CURRENCY, $currency);

        $decimals = KPayCatalog::supportsDecimals($providerCode) ? 2 : 0;
        $factor = 10 ** $decimals;
        $raw = $amountXaf * $rate * $factor;
        // Petite tolérance pour qu'un 1160.0000001 ne devienne pas 1161.
        $units = $isDeposit ? ceil(round($raw, 6)) : floor(round($raw, 6));
        $local = $decimals > 0 ? round($units / $factor, $decimals) : (int) $units;

        if ($local <= 0) {
            throw new KPayException('Montant trop faible pour cet opérateur.', 0, 'AMOUNT_TOO_LOW');
        }

        return [
            'amount' => $local,
            'currency' => $currency,
            'rate' => $rate,
            'converted' => true,
            'source' => $source,
        ];
    }

    /**
     * Taux `from` → `to` : celui de KPay en priorité (temps réel, celui que
     * KPay applique lui-même), mis en cache 30 min ; à défaut, nos taux
     * locaux s'ils sont à jour. Jamais de taux périmé pour un paiement.
     *
     * @return array{0: float, 1: string}  [taux, source]
     * @throws KPayException
     */
    protected function exchangeRate(string $from, string $to): array
    {
        $cacheKey = "kpay_fx_{$from}_{$to}";

        $rate = Cache::get($cacheKey);
        if (is_numeric($rate) && (float) $rate > 0) {
            return [(float) $rate, 'kpay'];
        }

        try {
            $response = $this->client->get('payments/exchange-rate?from=' . urlencode($from) . '&to=' . urlencode($to));
            $rate = $response['rate'] ?? null;
            if (is_numeric($rate) && (float) $rate > 0) {
                Cache::put($cacheKey, (float) $rate, now()->addMinutes(30));
                return [(float) $rate, 'kpay'];
            }
        } catch (\Throwable $e) {
            Log::warning('[KPay] Taux de change KPay indisponible', [
                'from' => $from,
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
        }

        $currencyService = app(\App\Services\CurrencyService::class);
        if (!$currencyService->ratesAreStale()) {
            try {
                $sample = 1000000.0;
                $converted = $currencyService->convert($sample, $from, $to);
                if ($converted > 0) {
                    return [$converted / $sample, 'local'];
                }
            } catch (\Throwable $e) {
                // Pas de paire locale : on refuse ci-dessous.
            }
        }

        throw new KPayException(
            __('wallet.exchange_rate_unavailable'),
            0,
            'EXCHANGE_RATE_UNAVAILABLE'
        );
    }

    /**
     * Taux FCFA → devise, pour l'estimation affichée dans l'app (`null` si
     * indisponible). Un échec est mémorisé 5 min pour ne pas ralentir le
     * catalogue à chaque appel quand KPay ne répond pas.
     */
    public function displayRateFromWallet(string $currency): ?float
    {
        $currency = strtoupper($currency);
        if (in_array($currency, self::FIXED_PARITY, true)) {
            return 1.0;
        }

        $failKey = "kpay_fx_fail_{$currency}";
        if (Cache::has($failKey)) {
            return null;
        }

        try {
            return $this->exchangeRate(self::WALLET_CURRENCY, $currency)[0];
        } catch (\Throwable $e) {
            Cache::put($failKey, true, now()->addMinutes(5));
            return null;
        }
    }

    protected function currencyForProvider(?string $providerCode): string
    {
        return KPayCatalog::currencyForProvider($providerCode) ?? 'XAF';
    }

    /**
     * Montant transmis à KPay : entier pour les providers sans décimales,
     * arrondi à 2 décimales pour ceux qui les acceptent (AIRTEL_GAB,
     * ORANGE_COD, MTN_MOMO_UGA, zone ZMB…).
     */
    protected function formatAmount(float $amount, ?string $providerCode): int|float
    {
        return KPayCatalog::supportsDecimals($providerCode)
            ? round($amount, 2)
            : (int) round($amount);
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
