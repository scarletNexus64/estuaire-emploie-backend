<?php

namespace App\Models\InsamIa;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Attestation de fin de parcours, délivrée par Estuaire et matérialisée par
 * un PDF généré côté backend.
 *
 * Deux origines possibles, distinguées par `source` : une évaluation (QCM)
 * réussie, ou une formation vidéo dont toutes les vidéos ont été visionnées.
 */
class InsamIaAttestation extends Model
{
    protected $table = 'insam_ia_attestations';

    /**
     * Note minimale (en %) ouvrant droit à l'attestation.
     */
    public const PASS_THRESHOLD = 70;

    /**
     * Attestation délivrée sur un QCM réussi (comportement historique).
     */
    public const SOURCE_EVALUATION = 'evaluation';

    /**
     * Attestation délivrée sur une formation vidéo achevée.
     */
    public const SOURCE_TRAINING = 'training';

    protected $fillable = [
        'user_id',
        'attempt_id',
        'source',
        'formation_id',
        'videos_total',
        'reference',
        'title',
        'specialite',
        'score',
        'total',
        'percentage',
        'mention',
        'pdf_path',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'formation_id' => 'integer',
            'videos_total' => 'integer',
            'score' => 'integer',
            'total' => 'integer',
            'percentage' => 'integer',
            'issued_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(InsamIaAttempt::class, 'attempt_id');
    }

    /**
     * L'attestation sanctionne-t-elle une formation vidéo ?
     *
     * Le PDF et l'API en dépendent : « score 8/10 » ne veut pas dire la même
     * chose pour un QCM et pour un nombre de vidéos vues.
     */
    public function isTraining(): bool
    {
        return $this->source === self::SOURCE_TRAINING;
    }

    /**
     * Le résultat ouvre-t-il droit à une attestation ?
     */
    public static function isEligible(?int $percentage): bool
    {
        return $percentage !== null && $percentage >= self::PASS_THRESHOLD;
    }

    /**
     * Mention correspondant à une note, dérivée une seule fois à la
     * délivrance puis figée sur l'attestation.
     */
    public static function mentionFor(int $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'excellent',
            $percentage >= 80 => 'tres_bien',
            $percentage >= 75 => 'bien',
            default => 'assez_bien',
        };
    }

    /**
     * Référence publique et vérifiable, unique par attestation.
     * Format : EE-ATT-<année>-<8 caractères>.
     */
    public static function generateReference(): string
    {
        do {
            $reference = sprintf('EE-ATT-%s-%s', now()->format('Y'), Str::upper(Str::random(8)));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Libellé traduit de la mention, pour le PDF et l'API.
     */
    public function mentionLabel(?string $locale = null): string
    {
        return __('insam_ia.mention.' . $this->mention, [], $locale);
    }
}
