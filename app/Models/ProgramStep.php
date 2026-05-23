<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramStep extends Model
{
    use HasFactory, HasTranslations;
    protected $fillable = [
        'program_id',
        'title',
        'description',
        'content',
        'resources',
        'order',
        'estimated_duration_days',
        'is_required',
    ];

    protected $casts = [
        'resources' => 'array',
        'is_required' => 'boolean',
        'order' => 'integer',
        'estimated_duration_days' => 'integer',
    ];

    /**
     * Champs textuels traduisibles. `resources` (JSON) reste non traduit pour
     * l'instant — les URLs sont indépendantes de la langue ; si besoin, les
     * libellés des ressources pourront être traduits dans une seconde itération.
     */
    protected array $translatable = ['title', 'description', 'content'];

    /**
     * Relation avec le programme parent
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
