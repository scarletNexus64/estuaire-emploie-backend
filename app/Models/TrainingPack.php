<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainingPack extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'learning_objectives',
        'price_xaf',
        'price_usd',
        'price_eur',
        'category',
        'level',
        'duration_hours',
        'cover_image',
        'preview_video',
        'instructor_name',
        'instructor_bio',
        'instructor_photo',
        'is_active',
        'whatsapp_group_link',
        'is_featured',
        'display_order',
        'purchases_count',
        'views_count',
        'average_rating',
        'reviews_count',
    ];

    protected function casts(): array
    {
        return [
            'price_xaf' => 'decimal:2',
            'price_usd' => 'decimal:2',
            'price_eur' => 'decimal:2',
            'duration_hours' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'integer',
            'purchases_count' => 'integer',
            'views_count' => 'integer',
            'average_rating' => 'decimal:2',
            'reviews_count' => 'integer',
        ];
    }

    /**
     * Boot method
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
            // Supprimer les fichiers médias
            if ($pack->cover_image) {
                Storage::disk('public')->delete($pack->cover_image);
            }
            if ($pack->instructor_photo) {
                Storage::disk('public')->delete($pack->instructor_photo);
            }
        });
    }

    /**
     * Relation : Un pack contient plusieurs vidéos
     */
    public function trainingVideos()
    {
        return $this->belongsToMany(TrainingVideo::class, 'training_pack_videos')
                    ->withPivot(['section_name', 'section_order', 'display_order'])
                    ->withTimestamps()
                    ->orderBy('training_pack_videos.section_order')
                    ->orderBy('training_pack_videos.display_order');
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
     * Scope pour filtrer par catégorie
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour filtrer par niveau
     */
    public function scopeLevel($query, string $level)
    {
        return $query->where('level', $level);
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

        $url = Storage::url($this->cover_image);

        // Si l'URL est relative, la convertir en URL absolue
        if (!str_starts_with($url, 'http')) {
            $url = url($url);
        }

        return $url;
    }

    /**
     * Obtenir l'URL de la photo de l'instructeur
     */
    public function getInstructorPhotoUrlAttribute(): ?string
    {
        if (!$this->instructor_photo) {
            return null;
        }

        $url = Storage::url($this->instructor_photo);

        // Si l'URL est relative, la convertir en URL absolue
        if (!str_starts_with($url, 'http')) {
            $url = url($url);
        }

        return $url;
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
     * Nombre de vidéos dans le pack
     */
    public function getVideosCountAttribute(): int
    {
        return $this->trainingVideos()->count();
    }

    /**
     * Catégories disponibles (depuis la base de données)
     */
    public static function getCategories(): array
    {
        return TrainingCategory::getSelectOptions();
    }

    /**
     * Niveaux disponibles
     */
    public static function getLevels(): array
    {
        return [
            'Débutant' => 'Débutant',
            'Intermédiaire' => 'Intermédiaire',
            'Avancé' => 'Avancé',
            'Expert' => 'Expert',
        ];
    }
}
