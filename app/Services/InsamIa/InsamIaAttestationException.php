<?php

namespace App\Services\InsamIa;

use RuntimeException;

/**
 * La tentative ne donne pas droit à une attestation (non soumise, ou note
 * inférieure au seuil de réussite). Distincte d'[InsamIaException] : le
 * service tiers n'est pas en cause, c'est une règle métier d'Estuaire.
 */
class InsamIaAttestationException extends RuntimeException
{
}
