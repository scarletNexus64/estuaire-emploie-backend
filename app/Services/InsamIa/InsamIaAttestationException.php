<?php

namespace App\Services\InsamIa;

use RuntimeException;

/**
 * Le parcours ne donne pas droit à une attestation : évaluation non soumise
 * ou sous le seuil de réussite, formation vidéo inachevée. Distincte
 * d'[InsamIaException] : le service tiers n'est pas en cause, c'est une règle
 * métier d'Estuaire.
 */
class InsamIaAttestationException extends RuntimeException
{
    /**
     * Toutes les vidéos de la formation n'ont pas encore été visionnées.
     */
    public static function trainingIncomplete(int $completed, int $total): self
    {
        return new self(__('insam_ia.attestation_errors.training_incomplete', [
            'completed' => $completed,
            'total' => $total,
        ]));
    }

    /**
     * Le catalogue n'annonce aucune vidéo pour cette formation : il n'y a
     * rien à attester, et le compter comme achevé délivrerait l'attestation
     * à un étudiant qui n'a rien suivi.
     */
    public static function trainingWithoutVideos(): self
    {
        return new self(__('insam_ia.attestation_errors.training_without_videos'));
    }
}
