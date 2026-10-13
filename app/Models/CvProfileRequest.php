<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Demande de profil publiée par une entreprise depuis la CVThèque
 * (« je cherche un CV de tel profil »). Diffusée à tous les utilisateurs ;
 * les réponses arrivent dans la messagerie, la demande épinglée.
 */
class CvProfileRequest extends Model
{
    use SoftDeletes;

    /** Niveaux d'expérience proposés dans l'app. */
    public const EXPERIENCE_LEVELS = [
        'beginner' => 'Débutant',
        'junior' => 'Junior (1-3 ans)',
        'intermediate' => 'Confirmé (3-5 ans)',
        'senior' => 'Senior (5 ans et +)',
        'any' => 'Tous niveaux',
    ];

    /** Types de contrat proposés dans l'app. */
    public const CONTRACT_TYPES = [
        'cdi' => 'CDI',
        'cdd' => 'CDD',
        'internship' => 'Stage',
        'freelance' => 'Freelance',
        'part_time' => 'Temps partiel',
        'task' => 'Tâche / mission',
    ];

    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'specialty',
        'experience_level',
        'contract_type',
        'city',
        'skills',
        'description',
        'status',
        'notified_at',
        'responses_count',
    ];

    protected $appends = ['experience_level_label', 'contract_type_label'];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'notified_at' => 'datetime',
            'responses_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function getExperienceLevelLabelAttribute(): ?string
    {
        return self::EXPERIENCE_LEVELS[$this->experience_level] ?? null;
    }

    public function getContractTypeLabelAttribute(): ?string
    {
        return self::CONTRACT_TYPES[$this->contract_type] ?? null;
    }

    /** Nom affiché de l'auteur : l'entreprise si connue, sinon le recruteur. */
    public function publisherName(): string
    {
        return $this->company?->name ?? $this->user?->name ?? 'Une entreprise';
    }

    /** Snapshot épinglé sur les messages de réponse (metadata.cv_profile_request). */
    public function toChatSnapshot(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'specialty' => $this->specialty,
            'experience_level_label' => $this->experience_level_label,
            'contract_type_label' => $this->contract_type_label,
            'city' => $this->city,
            'company_name' => $this->publisherName(),
            'owner_id' => $this->user_id,
            'image' => $this->company?->logo_url,
        ];
    }
}
