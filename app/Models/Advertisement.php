<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Advertisement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'created_by_user_id',
        'title',
        'description',
        'image',
        'background_color',
        'overlay_opacity',
        'content_type',
        'target_audience',
        'target_countries',
        'budget',
        'target_reach',
        'payment_id',
        'source',
        'ad_type',
        'start_date',
        'end_date',
        'impressions_count',
        'clicks_count',
        'ctr',
        'display_order',
        'is_active',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'budget' => 'decimal:2',
            'overlay_opacity' => 'integer',
            'target_reach' => 'integer',
            'impressions_count' => 'integer',
            'clicks_count' => 'integer',
            'ctr' => 'decimal:2',
            'is_active' => 'boolean',
            'target_countries' => 'array',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Cible les annonces visibles par un rôle utilisateur donné.
     * Une annonce 'all' est visible par tout le monde. Un visiteur non
     * authentifié ($role = null) ne voit que les annonces 'all'.
     */
    public function scopeForAudience(Builder $query, ?string $role): Builder
    {
        return $query->where(function (Builder $q) use ($role) {
            $q->where('target_audience', 'all');
            if ($role !== null) {
                $q->orWhere('target_audience', $role);
            }
        });
    }

    /**
     * Cible les annonces visibles dans un pays donné (code ISO alpha-2).
     * Une annonce sans ciblage pays (target_countries NULL ou vide) est
     * visible partout. Si $countryCode est null, on ne montre que ces
     * annonces "tous pays".
     */
    public function scopeForCountry(Builder $query, ?string $countryCode): Builder
    {
        return $query->where(function (Builder $q) use ($countryCode) {
            $q->whereNull('target_countries')
                ->orWhereJsonLength('target_countries', 0);
            if ($countryCode !== null) {
                $q->orWhereJsonContains('target_countries', $countryCode);
            }
        });
    }

    /**
     * Annonces actuellement diffusables (actives + dans la période).
     */
    public function scopeCurrentlyActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    // Accessor pour obtenir l'URL complète de l'image
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            // Retourner l'URL complète avec le domaine
            return url(Storage::url($this->image));
        }
        return null;
    }

    public function isActive(): bool
    {
        return $this->is_active &&
               $this->status === 'active' &&
               $this->start_date <= now() &&
               $this->end_date >= now();
    }

    public function calculateCTR(): void
    {
        if ($this->impressions_count > 0) {
            $this->ctr = ($this->clicks_count / $this->impressions_count) * 100;
            $this->save();
        }
    }
}
