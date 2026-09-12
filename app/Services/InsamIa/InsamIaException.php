<?php

namespace App\Services\InsamIa;

use RuntimeException;

/**
 * Échec d'un appel à INSAM-IA.
 *
 * Distingue les causes pour que l'API Estuaire réponde correctement :
 * `unavailable` (service tiers injoignable, mal configuré ou en erreur) donne
 * un 503 côté client, tandis que `not_found` donne un 404.
 */
class InsamIaException extends RuntimeException
{
    public const REASON_NOT_CONFIGURED = 'not_configured';
    public const REASON_UNAVAILABLE = 'unavailable';
    public const REASON_UNAUTHENTICATED = 'unauthenticated';
    public const REASON_NOT_FOUND = 'not_found';
    public const REASON_INVALID_REQUEST = 'invalid_request';

    public function __construct(
        string $message,
        public readonly string $reason = self::REASON_UNAVAILABLE,
        public readonly ?int $upstreamStatus = null,
    ) {
        parent::__construct($message);
    }

    public static function notConfigured(): self
    {
        return new self(
            'Les identifiants INSAM-IA ne sont pas configurés.',
            self::REASON_NOT_CONFIGURED
        );
    }

    public static function unavailable(string $detail, ?int $status = null): self
    {
        return new self($detail, self::REASON_UNAVAILABLE, $status);
    }

    public static function notFound(string $detail): self
    {
        return new self($detail, self::REASON_NOT_FOUND, 404);
    }

    /**
     * Le contenu demandé est absent chez INSAM-IA (≠ service en panne).
     */
    public function isNotFound(): bool
    {
        return $this->reason === self::REASON_NOT_FOUND;
    }

    /**
     * Code HTTP à renvoyer au client Estuaire.
     */
    public function httpStatus(): int
    {
        return $this->isNotFound() ? 404 : 503;
    }
}
