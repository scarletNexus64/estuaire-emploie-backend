<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStoragePack extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'storage_pack_id',
        'storage_mb',
        'storage_used_mb',
        'storage_folder_path',
        'purchased_at',
        'expires_at',
        'is_active',
        'purchase_price',
    ];

    protected function casts(): array
    {
        return [
            'storage_mb' => 'integer',
            'storage_used_mb' => 'integer',
            'purchase_price' => 'decimal:2',
            'is_active' => 'boolean',
            'purchased_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le pack de stockage
     */
    public function storagePack(): BelongsTo
    {
        return $this->belongsTo(StoragePack::class);
    }

    /**
     * Scope pour récupérer uniquement les packs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('expires_at', '>', now());
    }

    /**
     * Scope pour récupérer les packs expirés
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now())
                     ->orWhere('is_active', false);
    }

    /**
     * Vérifie si le pack est expiré
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast() || !$this->is_active;
    }

    /**
     * Retourne l'espace restant en Mo
     */
    public function getRemainingStorageMbAttribute(): int
    {
        return max(0, $this->storage_mb - $this->storage_used_mb);
    }

    /**
     * Retourne le pourcentage d'espace utilisé
     */
    public function getUsagePercentageAttribute(): float
    {
        if ($this->storage_mb == 0) {
            return 0;
        }
        return round(($this->storage_used_mb / $this->storage_mb) * 100, 2);
    }

    /**
     * Retourne l'espace restant formaté
     */
    public function getFormattedRemainingStorageAttribute(): string
    {
        $remaining = $this->remaining_storage_mb;

        if ($remaining < 1024) {
            return $remaining . ' Mo';
        }

        return round($remaining / 1024, 2) . ' Go';
    }

    /**
     * Retourne l'espace total formaté
     */
    public function getFormattedTotalStorageAttribute(): string
    {
        if ($this->storage_mb < 1024) {
            return $this->storage_mb . ' Mo';
        }

        return round($this->storage_mb / 1024, 2) . ' Go';
    }

    /**
     * Retourne l'espace utilisé formaté
     */
    public function getFormattedUsedStorageAttribute(): string
    {
        if ($this->storage_used_mb < 1024) {
            return $this->storage_used_mb . ' Mo';
        }

        return round($this->storage_used_mb / 1024, 2) . ' Go';
    }

    /**
     * Vérifie si l'utilisateur a assez d'espace pour un fichier
     */
    public function hasEnoughSpace(int $sizeInMb): bool
    {
        return $this->remaining_storage_mb >= $sizeInMb;
    }

    /**
     * Ajoute de l'espace utilisé
     */
    public function addUsedStorage(int $sizeInMb): bool
    {
        if (!$this->hasEnoughSpace($sizeInMb)) {
            return false;
        }

        $this->storage_used_mb += $sizeInMb;
        return $this->save();
    }

    /**
     * Libère de l'espace utilisé
     */
    public function freeStorage(int $sizeInMb): bool
    {
        $this->storage_used_mb = max(0, $this->storage_used_mb - $sizeInMb);
        return $this->save();
    }
}
