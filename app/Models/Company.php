<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'logo',
        'description',
        'sector',
        'website',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'status',
        'subscription_plan',
        'verified_at',
    ];

    protected $appends = ['logo_url', 'is_verified'];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Set the email attribute - always store in lowercase
     */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Get the full URL of the company logo
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        // Si le logo commence déjà par http:// ou https://, le retourner tel quel
        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        // Sinon, construire l'URL complète
        return url('storage/' . $this->logo);
    }

    /**
     * Get the verification status as a boolean
     */
    public function getIsVerifiedAttribute(): bool
    {
        return $this->status === 'verified';
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function recruiters(): HasMany
    {
        return $this->hasMany(Recruiter::class);
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isPremium(): bool
    {
        return $this->subscription_plan === 'premium';
    }

    /**
     * Vérifie si l'entreprise a des coordonnées géographiques
     */
    public function hasGeolocation(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Retourne les coordonnées géographiques sous forme de tableau
     */
    public function getCoordinates(): ?array
    {
        if (!$this->hasGeolocation()) {
            return null;
        }

        return [
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
        ];
    }

    /**
     * Définit les coordonnées géographiques
     */
    public function setCoordinates(float $latitude, float $longitude): void
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->save();
    }

    /**
     * Calcule la distance (en km) entre cette entreprise et des coordonnées données
     * Utilise la formule de Haversine
     */
    public function distanceTo(float $latitude, float $longitude): ?float
    {
        if (!$this->hasGeolocation()) {
            return null;
        }

        $earthRadius = 6371; // Rayon de la Terre en km

        $latFrom = deg2rad((float) $this->latitude);
        $lonFrom = deg2rad((float) $this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return $angle * $earthRadius;
    }

    /**
     * Scope pour filtrer les entreprises à proximité d'une position
     */
    public function scopeNearby($query, float $latitude, float $longitude, float $radiusKm = 10)
    {
        // Utiliser une approximation simple pour filtrer (peut être amélioré avec des requêtes spatiales)
        return $query->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance');
    }
}
