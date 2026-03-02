<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TrainingCategory extends Model
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
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        // Mettre à jour le slug si le nom change
        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Scope pour récupérer uniquement les catégories actives
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
     * Relation avec les packs de formation
     */
    public function trainingPacks()
    {
        return $this->hasMany(TrainingPack::class, 'category', 'name');
    }

    /**
     * Obtenir toutes les catégories actives pour un select
     */
    public static function getSelectOptions(): array
    {
        return static::active()
            ->ordered()
            ->pluck('name', 'name')
            ->toArray();
    }

    /**
     * Obtenir toutes les catégories avec leur slug
     */
    public static function getWithSlug(): array
    {
        return static::active()
            ->ordered()
            ->pluck('name', 'slug')
            ->toArray();
    }
}
