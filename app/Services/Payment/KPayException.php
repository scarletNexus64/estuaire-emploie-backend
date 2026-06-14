<?php

namespace App\Services\Payment;

/**
 * Exception levée par la couche KPay.
 *
 * - $statusCode : code HTTP renvoyé par l'API KPay (0 si erreur réseau)
 * - $kpayCode   : code métier KPay (ex. BAD_REQUEST, INSUFFICIENT_BALANCE)
 * - $retryable  : true si l'appel peut être retenté (429, 5xx, erreur réseau)
 */
class KPayException extends \Exception
{
    public function __construct(
        string $message,
        public int $statusCode = 0,
        public ?string $kpayCode = null,
        public bool $retryable = false,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    public function isRetryable(): bool
    {
        return $this->retryable;
    }
}
