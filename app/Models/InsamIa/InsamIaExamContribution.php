<?php

namespace App\Models\InsamIa;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Sujet déposé par un étudiant pour enrichir la banque commune.
 *
 * Le dépôt est hébergé par Estuaire : l'upload distant est hors service, et
 * la modération doit de toute façon rester chez nous — la banque est partagée
 * entre tous les étudiants.
 */
class InsamIaExamContribution extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $table = 'insam_ia_exam_contributions';

    protected $fillable = [
        'user_id',
        'title',
        'matiere',
        'filiere',
        'niveau',
        'annee',
        'file_path',
        'file_name',
        'file_size',
        'extracted_text',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
