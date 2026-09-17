<?php

namespace App\Models\InsamIa;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Épreuve d'entraînement produite par l'IA, conservée chez Estuaire.
 *
 * INSAM-IA sait générer et supprimer une épreuve, mais pas les lister : sans
 * ce miroir, l'étudiant perdrait son sujet dès qu'il quitte l'écran.
 */
class InsamIaGeneratedExam extends Model
{
    protected $table = 'insam_ia_generated_exams';

    protected $fillable = [
        'user_id',
        'remote_id',
        'matiere',
        'filiere',
        'niveau',
        'difficulte',
        'nombre',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'remote_id' => 'integer',
            'nombre' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
