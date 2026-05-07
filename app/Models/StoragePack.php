<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StoragePack extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'storage_mb',
        'duration_days',
        'price',
        'is_active',
        'description',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'storage_mb' => 'integer',
            'duration_days' => 'integer',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    /**
     * Génère automatiquement un slug basé sur le nom
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pack) {
            if (empty($pack->slug)) {
                $pack->slug = Str::slug($pack->name);
            }
        });

        static::updating(function ($pack) {
            if ($pack->isDirty('name') && empty($pack->slug)) {
                $pack->slug = Str::slug($pack->name);
            }
        });
    }

    /**
     * Scope pour récupérer uniquement les packs actifs
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
        return $query->orderBy('display_order', 'asc')->orderBy('price', 'asc');
    }

    /**
     * Retourne l'espace de stockage formaté
     */
    public function getFormattedStorageAttribute(): string
    {
        if ($this->storage_mb < 1024) {
            return $this->storage_mb . ' Mo';
        }

        return round($this->storage_mb / 1024, 2) . ' Go';
    }

    /**
     * Retourne la durée formatée
     */
    public function getFormattedDurationAttribute(): string
    {
        // 10 ans ou plus = "À vie"
        if ($this->duration_days >= 3650) {
            return 'À vie';
        }

        if ($this->duration_days < 30) {
            return $this->duration_days . ' jours';
        } elseif ($this->duration_days == 30) {
            return '1 mois';
        } elseif ($this->duration_days < 365) {
            $months = round($this->duration_days / 30);
            return $months . ' mois';
        } else {
            $years = round($this->duration_days / 365);
            return $years . ' an' . ($years > 1 ? 's' : '');
        }
    }

    /**
     * Retourne le prix formaté
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' CFA';
    }
}
