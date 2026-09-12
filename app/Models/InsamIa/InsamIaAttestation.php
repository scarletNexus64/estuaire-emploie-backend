<?php

namespace App\Models\InsamIa;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Attestation de fin de parcours, délivrée par Estuaire à l'issue d'une
 * évaluation réussie et matérialisée par un PDF généré côté backend.
 */
class InsamIaAttestation extends Model
{
    protected $table = 'insam_ia_attestations';

    /**
     * Note minimale (en %) ouvrant droit à l'attestation.
     */
    public const PASS_THRESHOLD = 70;

    protected $fillable = [
        'user_id',
        'attempt_id',
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
