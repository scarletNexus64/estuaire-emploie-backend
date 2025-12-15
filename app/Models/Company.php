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
        'status',
        'subscription_plan',
        'verified_at',
    ];

    protected $appends = ['logo_url'];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
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
}
