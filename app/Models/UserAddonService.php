<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * Service additionnel acheté par un utilisateur
 *
 * Représente l'achat d'un service par un user (recruteur ou candidat).
 * Exemples: Boost WhatsApp, accès coordonnées candidat, vérification diplôme, etc.
 *
 * @property int $id
 * @property int $user_id
 * @property int $addon_services_config_id
 * @property int|null $payment_id
 * @property int|null $related_job_id
 * @property int|null $related_user_id
 * @property Carbon $purchased_at
 * @property Carbon|null $activated_at
 * @property Carbon|null $expires_at
 * @property bool $is_active
 * @property int $views_count
 * @property int $clicks_count
 * @property int|null $uses_remaining
 * @property array|null $metadata
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read User $user
 * @property-read AddonServicesConfig $addonServiceConfig
 * @property-read Payment|null $payment
 * @property-read Job|null $relatedJob
 * @property-read User|null $relatedUser
 */
class UserAddonService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'addon_services_config_id',
        'payment_id',
        'related_job_id',
        'related_user_id',
        'purchased_at',
        'activated_at',
        'expires_at',
        'is_active',
        'views_count',
        'clicks_count',
        'uses_remaining',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'purchased_at' => 'datetime',
            'activated_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
            'views_count' => 'integer',
            'clicks_count' => 'integer',
            'uses_remaining' => 'integer',
            'metadata' => 'array',
        ];
    }

    /**
     * Utilisateur qui a acheté le service
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Configuration du service acheté
     */
    public function addonServiceConfig(): BelongsTo
    {
        return $this->belongsTo(AddonServicesConfig::class, 'addon_services_config_id');
    }

    /**
     * Paiement associé
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Job concerné (pour les boosts)
     */
    public function relatedJob(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'related_job_id');
    }

    /**
     * Candidat concerné (pour accès coordonnées, vérification diplôme)
     */
    public function relatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    /**
     * Vérifie si le service est toujours valide
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Vérifier expiration
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Vérifier uses_remaining
        if ($this->uses_remaining !== null && $this->uses_remaining <= 0) {
            return false;
        }

        return true;
    }

    /**
     * Vérifie si le service est expiré
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Active le service
     */
    public function activate(): void
    {
        $this->activated_at = now();
        $this->is_active = true;
        $this->save();

        // Synchroniser les features sur le user
        $this->user->syncAddonServiceFeatures($this);
    }

    /**
     * Désactive le service
     */
    public function deactivate(): void
    {
        $this->is_active = false;
        $this->save();

        // Retirer les features du user
        $this->user->removeAddonServiceFeatures($this);
    }

    /**
     * Consomme une utilisation
     */
    public function consumeUse(): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->uses_remaining === null) {
            // Utilisation illimitée
            return true;
        }

        if ($this->uses_remaining <= 0) {
            return false;
        }

        $this->decrement('uses_remaining');

        // Si épuisé, désactiver
        if ($this->fresh()->uses_remaining <= 0) {
            $this->deactivate();
        }

        return true;
    }

    /**
     * Incrémente le compteur de vues (pour les boosts)
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Incrémente le compteur de clics (pour les boosts)
     */
    public function incrementClicks(): void
    {
        $this->increment('clicks_count');
    }

    /**
     * Retourne le multiplicateur de boost (si applicable)
     */
    public function getBoostMultiplier(): ?int
    {
        return $this->metadata['boost_multiplier'] ?? $this->addonServiceConfig->boost_multiplier ?? null;
    }

    /**
     * Scope: Services actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('uses_remaining')
                    ->orWhere('uses_remaining', '>', 0);
            });
    }

    /**
     * Scope: Services expirés
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    /**
     * Scope: Services d'un type spécifique
     */
    public function scopeOfType($query, string $serviceType)
    {
        return $query->whereHas('addonServiceConfig', function ($q) use ($serviceType) {
            $q->where('service_type', $serviceType);
        });
    }

    /**
     * Scope: Services pour un user spécifique
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Boosts actifs pour un job spécifique
     */
    public function scopeBoostsForJob($query, int $jobId)
    {
        return $query->active()
            ->where('related_job_id', $jobId)
            ->ofType('job_boost');
    }
}
