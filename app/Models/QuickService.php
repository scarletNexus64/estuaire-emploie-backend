<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuickService extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected array $translatable = ['title', 'description'];

    protected $fillable = [
        'slug',
        'user_id',
        'service_category_id',
        'title',
        'language',
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
        // Programmes « jobs étudiants » publiés par la plateforme
        'is_student_program',
        'program_partner',
        'program_type',
        'commission_rate',
        'commission_basis',
        'commission_cap',
        'fixed_bonus',
        'fixed_bonus_basis',
        'bonus_is_cumulative',
        'min_active_days',
        'has_rating_system',
        'program_order',
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
            'is_student_program' => 'boolean',
            'commission_rate' => 'decimal:2',
            'commission_cap' => 'decimal:2',
            'fixed_bonus' => 'decimal:2',
            'bonus_is_cumulative' => 'boolean',
            'min_active_days' => 'integer',
            'has_rating_system' => 'boolean',
            'program_order' => 'integer',
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
        if ($this->price_type === 'commission') {
            return $this->formatted_compensation;
        } elseif ($this->price_type === 'negotiable') {
            return __('quick_service.price_negotiable');
        } elseif ($this->price_type === 'range') {
            return number_format($this->price_min, 0, ',', ' ') . ' - ' . number_format($this->price_max, 0, ',', ' ') . ' FCFA';
        } else {
            return number_format($this->price_min, 0, ',', ' ') . ' FCFA';
        }
    }

    /**
     * Programmes « jobs étudiants » publiés par la plateforme (apporteurs
     * d'affaires, coursier), par opposition aux demandes ponctuelles des
     * recruteurs. Ordonnés pour un affichage stable côté application.
     */
    public function scopeStudentPrograms($query)
    {
        return $query->where('is_student_program', true)
            ->orderBy('program_order');
    }

    /**
     * Exclut les programmes de la plateforme d'une liste de services rapides,
     * qui sont présentés dans leur propre section.
     */
    public function scopeExcludingStudentPrograms($query)
    {
        return $query->where('is_student_program', false);
    }

    /**
     * Rémunération d'un programme, en une phrase lisible :
     * « 5 % du premier mois de chiffre d'affaires », « 3 % de la première
     * transaction (max 50 000 FCFA) », éventuellement suivie de la prime fixe.
     */
    public function getFormattedCompensationAttribute(): string
    {
        $parts = [];

        if ($this->commission_rate !== null && $this->commission_basis !== null) {
            $rate = rtrim(rtrim(number_format((float) $this->commission_rate, 2, ',', ' '), '0'), ',');

            $commission = __('quick_service.commission.' . $this->commission_basis, ['rate' => $rate]);

            if ($this->commission_cap !== null) {
                $commission .= ' ' . __('quick_service.commission_cap', [
                    'amount' => number_format((float) $this->commission_cap, 0, ',', ' '),
                ]);
            }

            $parts[] = $commission;
        }

        if ($this->fixed_bonus !== null) {
            $parts[] = __('quick_service.fixed_bonus', [
                'amount' => number_format((float) $this->fixed_bonus, 0, ',', ' '),
                'basis' => $this->fixed_bonus_basis
                    ? __('quick_service.bonus_basis.' . $this->fixed_bonus_basis)
                    : '',
            ]);
        }

        if ($parts === []) {
            return __('quick_service.price_negotiable');
        }

        // « ou » quand la prime remplace la commission (Estuaire Eat),
        // « et » quand les deux se cumulent (Estuaire Emploi).
        $separator = $this->bonus_is_cumulative
            ? __('quick_service.compensation_and')
            : __('quick_service.compensation_separator');

        return implode(' ' . $separator . ' ', $parts);
    }
}
