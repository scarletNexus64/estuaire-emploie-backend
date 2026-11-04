<?php

namespace App\Services\Social;

use RuntimeException;

/**
 * Erreur métier de la couche sociale : porte un code HTTP et une clé de
 * traduction (lang/{fr,en,es,ar}/social.php). Les contrôleurs la convertissent
 * en `{ "success": false, "message": "..." }`.
 */
class SocialException extends RuntimeException
{
    public function __construct(public readonly string $translationKey, public readonly int $status)
    {
        parent::__construct($translationKey);
    }

    public static function notFound(string $key = 'social.errors.not_found'): self
    {
        return new self($key, 404);
    }

    public static function forbidden(string $key = 'social.errors.forbidden'): self
    {
        return new self($key, 403);
    }

    public static function unprocessable(string $key): self
    {
        return new self($key, 422);
    }

    public function translatedMessage(): string
    {
        return __($this->translationKey);
    }
}
