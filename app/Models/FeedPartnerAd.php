<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Carte sponsorisée d'une application partenaire dans le fil candidat.
 */
class FeedPartnerAd extends Model
{
    use SoftDeletes;

    public const CACHE_KEY = 'feed_partner_ads.active';

    /** Partenaires connus (slug => libellé). */
    public const PARTNERS = [
        'estuaire_achats' => 'Estuaire Achats',
        'estuaire_eat' => 'Estuaire Eat',
        'gfs' => 'Global Financial Solution (GFS)',
    ];

    protected $fillable = [
        'partner',
        'name',
        'headline',
        'body',
        'logo_path',
        'image_path',
        'cta_label',
        'accent_color',
        'play_store_url',
        'app_store_url',
        'website_url',
        'is_active',
        'display_order',
        'impressions_count',
        'clicks_count',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'display_order' => 'integer',
            'impressions_count' => 'integer',
            'clicks_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        // Toute modification du contenu invalide le cache public. Les
        // compteurs passent par increment() (requête directe), sans effet ici.
        $flush = fn () => Cache::forget(self::CACHE_KEY);
        static::saved($flush);
        static::deleted($flush);
        static::restored($flush);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? url(Storage::url($this->logo_path)) : null;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? url(Storage::url($this->image_path)) : null;
    }

    public function getCtrAttribute(): float
    {
        return $this->impressions_count > 0
            ? round($this->clicks_count / $this->impressions_count * 100, 2)
            : 0.0;
    }

    /** Représentation consommée par l'application mobile. */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'partner' => $this->partner,
            'name' => $this->name,
            'headline' => $this->headline,
            'body' => $this->body,
            'logo_url' => $this->logo_url,
            'image_url' => $this->image_url,
            'cta_label' => $this->cta_label,
            'accent_color' => $this->accent_color,
            'play_store_url' => $this->play_store_url,
            'app_store_url' => $this->app_store_url,
            'website_url' => $this->website_url,
        ];
    }
}
