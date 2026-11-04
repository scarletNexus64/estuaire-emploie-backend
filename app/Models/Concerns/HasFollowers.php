<?php

namespace App\Models\Concerns;

use App\Models\CompanyFollow;
use App\Services\Social\SocialContext;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Champs sociaux d'une entreprise, ajoutés à TOUTE sérialisation JSON
 * (objet `company` imbriqué dans les offres, détail, annuaire…) :
 *
 *   followers_count    → colonne dénormalisée (0 si absente)
 *   is_following       → le visiteur suit-il l'entreprise (false invité)
 *   active_jobs_count  → offres publiées ; valeur d'un withCount
 *                        `active_jobs_count` si présente, sinon chargée par
 *                        lot via SocialContext (aucun N+1)
 */
trait HasFollowers
{
    public static function bootHasFollowers(): void
    {
        static::retrieved(function ($model) {
            app(SocialContext::class)->track('company', $model->getKey());
        });
    }

    public function initializeHasFollowers(): void
    {
        $this->append(['followers_count', 'is_following', 'active_jobs_count']);
    }

    public function follows(): HasMany
    {
        return $this->hasMany(CompanyFollow::class);
    }

    public function getFollowersCountAttribute(): int
    {
        // Lu dans les attributs bruts : pour un « append », Laravel passe
        // toujours null comme valeur à l'accesseur.
        return (int) ($this->attributes['followers_count'] ?? 0);
    }

    public function getIsFollowingAttribute(): bool
    {
        if (! $this->exists) {
            return false;
        }

        return app(SocialContext::class)->isFollowing($this->getKey());
    }

    public function getActiveJobsCountAttribute(): int
    {
        if (array_key_exists('active_jobs_count', $this->attributes)) {
            return (int) $this->attributes['active_jobs_count'];
        }
        if (! $this->exists) {
            return 0;
        }

        return app(SocialContext::class)->activeJobsCount($this->getKey());
    }
}
