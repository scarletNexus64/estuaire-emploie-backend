<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuickService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'service_category_id',
        'title',
        'description',
        'price_type',
        'price_min',
        'price_max',
        'latitude',
        'longitude',
        'location_name',
        'urgency',
        'desired_date',
        'estimated_duration',
        'status',
        'expires_at',
        'approved_at',
        'images',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'desired_date' => 'date',
            'expires_at' => 'datetime',
            'approved_at' => 'datetime',
            'price_min' => 'decimal:2',
            'price_max' => 'decimal:2',
            'views_count' => 'integer',
        ];
    }

    /**
     * Relation avec l'utilisateur qui a posté le service
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la catégorie de service
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Relation avec les réponses au service
     */
    public function responses(): HasMany
    {
        return $this->hasMany(ServiceResponse::class);
    }

    /**
     * Scope pour récupérer les services ouverts
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope pour récupérer les services actifs (non expirés)
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope pour récupérer les services en attente d'approbation
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour récupérer les services approuvés
     */
    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['approved', 'open', 'in_progress', 'completed']);
    }

    /**
     * Scope pour rechercher par proximité
     */
    public function scopeNearby($query, $latitude, $longitude, $radiusInKm = 10)
    {
        // Formule Haversine pour calculer la distance
        $query->selectRaw('*, ( 6371 * acos( cos( radians(?) ) *
            cos( radians( latitude ) ) *
            cos( radians( longitude ) - radians(?) ) +
            sin( radians(?) ) *
            sin( radians( latitude ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radiusInKm)
            ->orderBy('distance');

        return $query;
    }

    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Vérifier si le service est ouvert
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Vérifier si le service est complété
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Vérifier si le service est expiré
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Vérifier si le service est en attente d'approbation
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifier si le service est approuvé
     */
    public function isApproved(): bool
    {
        return in_array($this->status, ['approved', 'open', 'in_progress', 'completed']);
    }

    /**
     * Obtenir le prix formaté
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price_type === 'negotiable') {
            return 'À négocier';
        } elseif ($this->price_type === 'range') {
            return number_format($this->price_min, 0, ',', ' ') . ' - ' . number_format($this->price_max, 0, ',', ' ') . ' FCFA';
        } else {
            return number_format($this->price_min, 0, ',', ' ') . ' FCFA';
        }
    }
}
