<?php

namespace App\Services\Payment;

use App\Models\ServiceConfiguration;

/**
 * Réglages KPay Lite (cartes Visa / Mastercard).
 *
 * Résolution champ par champ : la valeur saisie dans l'admin
 * (ServiceConfiguration `kpay_lite`, colonne JSON `config`) prime ; à défaut
 * on retombe sur `config('services.kpay_lite')`, alimenté par KPAY_LITE_*.
 *
 * L'environnement n'est pas un réglage : KPay Lite le déduit du préfixe de la
 * clé API (`kpay_lite_test_…` sandbox, `kpay_lite_live_…` live), avec la même
 * URL de base pour les deux.
 */
class KPayLiteConfig
{
    public const DEFAULT_BASE_URL = 'https://lite.kpay.site';

    /** Champs secrets : jamais réaffichés dans le formulaire admin. */
    public const SECRET_FIELDS = ['api_key', 'secret_key', 'webhook_secret', 'gateway_secret'];

    protected array $stored = [];
    protected bool $hasRow = false;
    protected bool $rowActive = false;

    /**
     * @param ServiceConfiguration|null $row Volontairement non typé : le
     *   conteneur injecterait sinon un modèle vide lors d'un app(...).
     */
    public function __construct($row = null)
    {
        $row = $row instanceof ServiceConfiguration ? $row : ServiceConfiguration::getKPayLiteConfig();

        if ($row) {
            $this->hasRow = true;
            $this->rowActive = (bool) $row->is_active;
            $this->stored = is_array($row->config) ? $row->config : [];
        }
    }

    public static function make(): self
    {
        return new self();
    }

    /** Valeur admin si renseignée, sinon repli env. */
    protected function value(string $key, mixed $default = null): mixed
    {
        $stored = $this->stored[$key] ?? null;
        if ($stored !== null && $stored !== '') {
            return $stored;
        }

        $env = config("services.kpay_lite.{$key}");

        return ($env !== null && $env !== '') ? $env : $default;
    }

    /** Vrai si la valeur vient de l'admin (et non du repli env). */
    public function isStored(string $key): bool
    {
        $stored = $this->stored[$key] ?? null;

        return $stored !== null && $stored !== '';
    }

    /**
     * Interrupteur : une fois la ligne admin créée, c'est son `is_active` qui
     * décide ; avant, on suit KPAY_LITE_ENABLED.
     */
    public function enabled(): bool
    {
        return $this->hasRow
            ? $this->rowActive
            : (bool) config('services.kpay_lite.enabled', false);
    }

    public function baseUrl(): string
    {
        return rtrim((string) $this->value('base_url', self::DEFAULT_BASE_URL), '/');
    }

    public function apiKey(): ?string
    {
        return $this->value('api_key');
    }

    public function secretKey(): ?string
    {
        return $this->value('secret_key');
    }

    public function webhookSecret(): ?string
    {
        return $this->value('webhook_secret');
    }

    public function gatewaySecret(): ?string
    {
        return $this->value('gateway_secret');
    }

    public function timeout(): int
    {
        return max(5, (int) $this->value('timeout', 30));
    }

    /** Montant minimum configuré (XAF), avant prise en compte du taux USD. */
    public function configuredMinAmountXaf(): int
    {
        return max(1, (int) $this->value('min_amount_xaf', 1000));
    }

    public function expiryMinutes(): int
    {
        return max(15, (int) $this->value('expiry_minutes', 45));
    }

    /** Clés présentes : de quoi appeler l'API. */
    public function isConfigured(): bool
    {
        return filled($this->apiKey()) && filled($this->secretKey());
    }

    /** Proposé aux clients : activé ET configuré. */
    public function isActive(): bool
    {
        return $this->enabled() && $this->isConfigured();
    }

    /** Sandbox si la clé API porte le préfixe de test. */
    public function isTest(): bool
    {
        return str_starts_with((string) $this->apiKey(), 'kpay_lite_test_');
    }

    /** `sandbox`, `live` ou `unknown` (clé absente ou préfixe inattendu). */
    public function environment(): string
    {
        $key = (string) $this->apiKey();

        return match (true) {
            str_starts_with($key, 'kpay_lite_test_') => 'sandbox',
            str_starts_with($key, 'kpay_lite_live_') => 'live',
            default => 'unknown',
        };
    }

    /**
     * Identifiants pour KPayClient (mode « credentials explicites »).
     */
    public function clientCredentials(): array
    {
        return [
            'base_url' => $this->baseUrl(),
            'api_key' => $this->apiKey(),
            'secret_key' => $this->secretKey(),
            'timeout' => $this->timeout(),
            'log_tag' => '[KPayLite]',
        ];
    }

    /** Données brutes stockées (pour la fusion lors d'une mise à jour admin). */
    public function stored(): array
    {
        return $this->stored;
    }

    /** Aperçu masqué d'un secret pour l'admin : « ••••1a2b » ou null. */
    public function maskedPreview(string $key): ?string
    {
        $value = (string) $this->value($key, '');
        if ($value === '') {
            return null;
        }

        return '••••' . substr($value, -4);
    }
}
