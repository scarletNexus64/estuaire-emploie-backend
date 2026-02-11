<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Configuration des services additionnels pour recruteurs
 *
 * Définit les services qu'un recruteur peut acheter en plus de son plan.
 * Exemples: boost job, accès coordonnées, vérification diplôme, etc.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $display_order
 * @property float $price
 * @property int|null $duration_days
 * @property string $service_type
 * @property int|null $boost_multiplier
 * @property array|null $features
 * @property bool $is_active
 * @property bool $is_popular
 * @property string|null $color
 * @property string|null $icon
 */
class AddonServicesConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'display_order',
        'price',
        'duration_days',
        'service_type',
        'boost_multiplier',
        'features',
        'is_active',
        'is_popular',
        'color',
        'icon',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'display_order' => 'integer',
            'duration_days' => 'integer',
            'boost_multiplier' => 'integer',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ];
    }

    /**
     * Services achetés par les users
     */
    public function userAddonServices(): HasMany
    {
        return $this->hasMany(UserAddonService::class, 'addon_services_config_id');
    }

    /**
     * Services achetés par les companies (ancien système)
     */
    public function companyAddonServices(): HasMany
    {
        return $this->hasMany(CompanyAddonService::class, 'addon_services_config_id');
    }

    /**
     * Scope: Services actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Services populaires
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    /**
     * Scope: Services par type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('service_type', $type);
    }

    /**
     * Scope: Ordonné par display_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Retourne la clé de feature correspondante
     */
    public function getFeatureKey(): string
    {
        return match($this->service_type) {
            'extra_job_posting' => 'extra_job_posting',
            'job_boost' => 'boost_whatsapp',
            'candidate_contact' => 'candidate_contact_single',
            'diploma_verification' => 'verify_diplomas',
            'skills_test' => 'skills_test',
            default => 'addon_' . $this->slug,
        };
    }

    /**
     * Vérifie si le service a une durée limitée
     */
    public function hasExpiration(): bool
    {
        return $this->duration_days !== null;
    }

    /**
     * Vérifie si le service est un boost
     */
    public function isBoost(): bool
    {
        return $this->service_type === 'job_boost';
    }
}
