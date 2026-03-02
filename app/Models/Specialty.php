<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Specialty extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-générer le slug à la création
        static::creating(function ($specialty) {
            if (empty($specialty->slug)) {
                $specialty->slug = Str::slug($specialty->name);
            }
        });

        // Mettre à jour le slug si le nom change
        static::updating(function ($specialty) {
            if ($specialty->isDirty('name')) {
                $specialty->slug = Str::slug($specialty->name);
            }
        });
    }

    /**
     * Scope pour récupérer uniquement les spécialités actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour trier par ordre d'affichage
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }

    /**
     * Relation avec les épreuves
     */
    public function examPapers()
    {
        return $this->hasMany(ExamPaper::class, 'specialty', 'name');
    }

    /**
     * Relation avec les packs d'épreuves
     */
    public function examPacks()
    {
        return $this->hasMany(ExamPack::class, 'specialty', 'name');
    }

    /**
     * Obtenir toutes les spécialités actives pour un select
     */
    public static function getSelectOptions(): array
    {
        return static::active()
            ->ordered()
            ->pluck('name', 'name')
            ->toArray();
    }

    /**
     * Obtenir toutes les spécialités avec leur slug
     */
    public static function getWithSlug(): array
    {
        return static::active()
            ->ordered()
            ->pluck('name', 'slug')
            ->toArray();
    }
}
