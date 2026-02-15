<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'type',
        'description',
        'objectives',
        'icon',
        'duration_weeks',
        'order',
        'is_active',
        'required_packs',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_weeks' => 'integer',
        'order' => 'integer',
        'required_packs' => 'array',
    ];

    /**
     * Relation avec les étapes du programme
     */
    public function steps(): HasMany
    {
        return $this->hasMany(ProgramStep::class)->orderBy('order');
    }

    /**
     * Get the display name for the program type
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'immersion_professionnelle' => 'Programme d\'immersion professionnelle',
            'entreprenariat' => 'Programme en entreprenariat',
            'transformation_professionnelle' => 'Programme de transformation professionnelle et personnel',
            'digital_skills' => 'Compétences digitales et tech',
            'agriculture_agrobusiness' => 'Agriculture et agrobusiness',
            'tourisme_hotellerie' => 'Tourisme, hôtellerie et restauration',
            'btp_construction' => 'BTP et construction',
            'sante_social' => 'Santé, social et services',
            'commerce_vente' => 'Commerce, vente et relation client',
            'artisanat_metiers' => 'Artisanat et métiers',
            'finance_comptabilite' => 'Finance et comptabilité',
            'energie_environnement' => 'Énergie et environnement',
            default => $this->type,
        };
    }
}
