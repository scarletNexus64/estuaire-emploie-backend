<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackPromotionActivation extends Model
{
    use HasFactory;

    protected $fillable = [
        'pack_promotion_id',
        'user_id',
        'activated_at',
        'expires_at',
        'is_expired',
    ];

    protected function casts(): array
    {
        return [
            'pack_promotion_id' => 'integer',
            'user_id' => 'integer',
            'activated_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_expired' => 'boolean',
        ];
    }

    /**
     * Relation : Promotion associée
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(PackPromotion::class, 'pack_promotion_id');
    }

    /**
     * Relation : Utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope : Activations actives (non expirées)
     */
    public function scopeActive($query)
    {
        return $query->where('is_expired', false)
                    ->where('expires_at', '>', Carbon::now());
    }

    /**
     * Scope : Activations expirées
     */
    public function scopeExpired($query)
    {
        return $query->where('is_expired', true)
                    ->orWhere('expires_at', '<=', Carbon::now());
    }

    /**
     * Scope : Activations pour un utilisateur
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Vérifie si l'activation est expirée
     */
    public function isExpired(): bool
    {
        if ($this->is_expired) {
            return true;
        }

        return Carbon::now()->gt($this->expires_at);
    }

    /**
     * Marque l'activation comme expirée
     */
    public function markAsExpired(): void
    {
        if (!$this->is_expired) {
            $this->update(['is_expired' => true]);
        }
    }

    /**
     * Obtient le nombre de jours restants avant expiration
     */
    public function getRemainingDaysAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->expires_at, false);
    }

    /**
     * Obtient le nombre d'heures restantes avant expiration
     */
    public function getRemainingHoursAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return Carbon::now()->diffInHours($this->expires_at, false);
    }

    /**
     * Obtient le statut texte de l'activation
     */
    public function getStatusAttribute(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        }

        $remainingDays = $this->remaining_days;

        if ($remainingDays <= 3) {
            return 'expiring_soon';
        }

        return 'active';
    }

    /**
     * Obtient la durée totale d'utilisation (en jours)
     */
    public function getTotalDurationDaysAttribute(): int
    {
        return $this->activated_at->diffInDays($this->expires_at);
    }

    /**
     * Obtient le pourcentage de temps écoulé
     */
    public function getPercentageUsedAttribute(): float
    {
        $totalDays = $this->total_duration_days;

        if ($totalDays === 0) {
            return 100.0;
        }

        $daysPassed = $this->activated_at->diffInDays(Carbon::now());

        return min(100.0, ($daysPassed / $totalDays) * 100);
    }

    /**
     * Boot method pour gérer les événements
     */
    protected static function boot()
    {
        parent::boot();

        // Vérifier automatiquement l'expiration lors de l'accès
        static::retrieved(function ($activation) {
            if ($activation->isExpired() && !$activation->is_expired) {
                $activation->markAsExpired();
            }
        });
    }
}
