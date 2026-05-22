<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributs calculés exposés automatiquement dans les réponses JSON
     * (liste, détail, création...) pour permettre le partage d'une offre.
     */
    protected $appends = [
        'share_url',
        'deep_link',
    ];

    protected $fillable = [
        'company_id',
        'category_id',
        'contract_type_id',
        'posted_by',
        'title',
        'description',
        'requirements',
        'benefits',
        'salary_min',
        'salary_max',
        'salary_negotiable',
        'experience_level',
        'status',
        'visibility',
        'is_featured',
        'views_count',
        'application_deadline',
        'published_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'salary_negotiable' => 'boolean',
            'is_featured' => 'boolean',
            'application_deadline' => 'date',
            'published_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // category_id référence désormais une company_category de niveau 3
    // (secteur choisi par le recruteur), et non plus la table `categories`.
    public function category(): BelongsTo
    {
        return $this->belongsTo(CompanyCategory::class, 'category_id');
    }

    public function contractType(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function skillTests(): HasMany
    {
        return $this->hasMany(RecruiterSkillTest::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Lien web public de partage de l'offre.
     *
     * Cette URL ouvre une page HTML (avec aperçu réseaux sociaux) qui tente
     * d'ouvrir l'offre dans l'application mobile, et propose les stores en
     * fallback si l'app n'est pas installée. Visible aussi en mode vitrine.
     */
    protected function shareUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => rtrim(config('app.share_base_url'), '/') . "/jobs/{$this->id}/share",
        );
    }

    /**
     * Deeplink direct vers l'offre dans l'application mobile
     * (custom scheme), utilisable par l'app pour router en interne.
     */
    protected function deepLink(): Attribute
    {
        return Attribute::make(
            get: fn () => config('app.app_scheme') . "://job/{$this->id}",
        );
    }

    /**
     * Filtre les offres visibles pour un candidat selon sa ville.
     *
     * - visibility = 'national' : toujours visible.
     * - visibility = 'local'    : visible uniquement si la ville de
     *   l'entreprise correspond à la ville du candidat (comparaison
     *   insensible à la casse et aux accents, comme la recherche).
     *
     * Si $candidateCity est vide/null, seules les offres nationales
     * sont retournées.
     */
    public function scopeVisibleFor(Builder $query, ?string $candidateCity): Builder
    {
        $city = $candidateCity !== null ? trim($candidateCity) : '';

        if ($city === '') {
            return $query->where('visibility', 'national');
        }

        return $query->where(function (Builder $q) use ($city) {
            $q->where('visibility', 'national')
                ->orWhere(function (Builder $sub) use ($city) {
                    $sub->where('visibility', 'local')
                        ->whereHas('company', function (Builder $companyQuery) use ($city) {
                            $companyQuery->whereRaw(
                                'LOWER(city) COLLATE utf8mb4_general_ci = LOWER(?) COLLATE utf8mb4_general_ci',
                                [$city]
                            );
                        });
                });
        });
    }
}