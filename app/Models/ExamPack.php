<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExamPack extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_xaf',
        'price_usd',
        'price_eur',
        'specialty',
        'year',
        'exam_type',
        'cover_image',
        'is_active',
        'is_featured',
        'display_order',
        'purchases_count',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'price_xaf' => 'decimal:2',
            'price_usd' => 'decimal:2',
            'price_eur' => 'decimal:2',
            'year' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'integer',
            'purchases_count' => 'integer',
            'views_count' => 'integer',
        ];
    }

    /**
     * Boot method pour auto-générer le slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pack) {
            if (empty($pack->slug)) {
                $pack->slug = Str::slug($pack->name);
            }
        });

        static::deleting(function ($pack) {
            // Supprimer l'image de couverture
            if ($pack->cover_image) {
                Storage::disk('public')->delete($pack->cover_image);
            }
        });
    }

    /**
     * Relation : Un pack contient plusieurs épreuves
     */
    public function examPapers()
    {
        return $this->belongsToMany(ExamPaper::class, 'exam_pack_papers')
                    ->withPivot('display_order')
                    ->withTimestamps()
                    ->orderBy('exam_pack_papers.display_order');
    }

    /**
     * Relation : Achats du pack par les utilisateurs
     */
    public function purchases()
    {
        return $this->hasMany(PackPurchase::class);
    }

    /**
     * Scope pour les packs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les packs mis en avant
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope pour filtrer par spécialité
     */
    public function scopeSpecialty($query, string $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    /**
     * Scope pour filtrer par année
     */
    public function scopeYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Incrémenter le compteur d'achats
     */
    public function incrementPurchases(): void
    {
        $this->increment('purchases_count');
    }

    /**
     * Obtenir l'URL de l'image de couverture
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }
        return Storage::url($this->cover_image);
    }

    /**
     * Obtenir le prix selon la devise
     */
    public function getPrice(string $currency = 'XAF'): float
    {
        return match(strtoupper($currency)) {
            'USD' => (float) $this->price_usd ?? $this->price_xaf,
            'EUR' => (float) $this->price_eur ?? $this->price_xaf,
            default => (float) $this->price_xaf,
        };
    }

    /**
     * Nombre d'épreuves dans le pack
     */
    public function getPapersCountAttribute(): int
    {
        return $this->examPapers()->count();
    }

    /**
     * Types d'examens disponibles
     */
    public static function getExamTypes(): array
    {
        return [
            'BTS' => 'BTS',
            'Licence' => 'Licence',
            'Master 1' => 'Master 1',
            'Master 2' => 'Master 2',
            'Concours' => 'Concours',
            'Certification' => 'Certification',
        ];
    }
}
