<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackPromotion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'promotionable_type',
        'promotionable_id',
        'is_active',
        'start_date',
        'end_date',
        'usage_duration_days',
        'max_activations',
        'current_activations',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'usage_duration_days' => 'integer',
            'max_activations' => 'integer',
            'current_activations' => 'integer',
            'created_by' => 'integer',
        ];
    }

    /**
     * Relation polymorphique vers le pack cible
     * (ExamPack, TrainingPack, StoragePack, SubscriptionPlan)
     */
    public function promotionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relation : Activations de cette promotion par les utilisateurs
     */
    public function activations(): HasMany
    {
        return $this->hasMany(PackPromotionActivation::class);
    }

    /**
     * Relation : Administrateur créateur
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope : Promotions actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope : Promotions en cours (entre start_date et end_date)
     */
    public function scopeCurrent($query)
    {
        $now = Carbon::now();
        return $query->active()
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    /**
     * Scope : Promotions disponibles (current + max_activations pas atteint)
     */
    public function scopeAvailable($query)
    {
        return $query->current()
                    ->where(function($q) {
                        $q->whereNull('max_activations')
                          ->orWhereRaw('current_activations < max_activations');
                    });
    }

    /**
     * Scope : Filtrer par type de pack
     */
    public function scopeForType($query, string $type)
    {
        return $query->where('promotionable_type', $type);
    }

    /**
     * Vérifie si la promotion est disponible maintenant
     */
    public function isAvailable(): bool
    {
        $now = Carbon::now();

        // Vérifier si active
        if (!$this->is_active) {
            return false;
        }

        // Vérifier si dans la période
        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        // Vérifier limite d'activations
        if ($this->max_activations !== null && $this->current_activations >= $this->max_activations) {
            return false;
        }

        return true;
    }

    /**
     * Vérifie si un utilisateur peut activer cette promotion
     */
    public function canActivate(User $user): bool
    {
        // Vérifier si disponible
        if (!$this->isAvailable()) {
            return false;
        }

        // Vérifier si l'utilisateur n'a pas déjà activé cette promotion
        $hasActivated = $this->activations()
                            ->where('user_id', $user->id)
                            ->exists();

        return !$hasActivated;
    }

    /**
     * Active la promotion pour un utilisateur
     */
    public function activate(User $user): PackPromotionActivation
    {
        if (!$this->canActivate($user)) {
            throw new \Exception('Cette promotion ne peut pas être activée.');
        }

        $activatedAt = Carbon::now();
        $expiresAt = $activatedAt->copy()->addDays($this->usage_duration_days);

        // Créer l'activation
        $activation = $this->activations()->create([
            'user_id' => $user->id,
            'activated_at' => $activatedAt,
            'expires_at' => $expiresAt,
            'is_expired' => false,
        ]);

        // Incrémenter le compteur
        $this->incrementActivations();

        return $activation;
    }

    /**
     * Incrémente le compteur d'activations
     */
    public function incrementActivations(): void
    {
        $this->increment('current_activations');
    }

    /**
     * Obtient le nombre de jours restants avant la fin de la promotion
     */
    public function getRemainingDaysAttribute(): int
    {
        $now = Carbon::now();

        if ($now->gt($this->end_date)) {
            return 0;
        }

        return $now->diffInDays($this->end_date);
    }

    /**
     * Obtient le nombre d'activations restantes (si limite définie)
     */
    public function getRemainingActivationsAttribute(): ?int
    {
        if ($this->max_activations === null) {
            return null; // Illimité
        }

        return max(0, $this->max_activations - $this->current_activations);
    }

    /**
     * Vérifie si la promotion est expirée
     */
    public function isExpired(): bool
    {
        return Carbon::now()->gt($this->end_date);
    }

    /**
     * Obtient le statut texte de la promotion
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        $now = Carbon::now();

        if ($now->lt($this->start_date)) {
            return 'scheduled';
        }

        if ($now->gt($this->end_date)) {
            return 'expired';
        }

        if ($this->max_activations !== null && $this->current_activations >= $this->max_activations) {
            return 'full';
        }

        return 'active';
    }

    /**
     * Obtient le nom du pack cible
     */
    public function getPackNameAttribute(): string
    {
        return $this->promotionable?->name ?? 'Pack supprimé';
    }

    /**
     * Obtient le type de pack lisible
     */
    public function getPackTypeNameAttribute(): string
    {
        return match($this->promotionable_type) {
            'App\Models\ExamPack' => 'Pack Examen',
            'App\Models\TrainingPack' => 'Pack Formation',
            'App\Models\StoragePack' => 'Pack Stockage',
            'App\Models\SubscriptionPlan' => 'Plan d\'Abonnement',
            default => 'Pack',
        };
    }

    /**
     * Types de packs disponibles pour les promotions
     */
    public static function getPromotionableTypes(): array
    {
        return [
            'App\Models\ExamPack' => 'Pack Examen',
            'App\Models\TrainingPack' => 'Pack Formation',
            'App\Models\StoragePack' => 'Pack Stockage',
            'App\Models\SubscriptionPlan' => 'Plan d\'Abonnement',
        ];
    }
}
