<?php

namespace App\Models\Concerns;

use App\Services\Social\SocialContext;
use App\Services\Social\SocialTarget;

/**
 * Champs sociaux d'une publication (offre, service rapide), ajoutés à TOUTE
 * sérialisation JSON du modèle (listes, détail, favoris, feed…) :
 *
 *   likes_count, comments_count, reposts_count  → colonnes dénormalisées (0 si absentes)
 *   is_liked, is_reposted                         → état du visiteur
 *
 * L'état du visiteur est chargé par lot via SocialContext (aucun N+1) ;
 * false pour un invité.
 */
trait HasSocialInteractions
{
    public static function bootHasSocialInteractions(): void
    {
        static::retrieved(function ($model) {
            $type = SocialTarget::typeOf($model);
            if ($type !== null) {
                app(SocialContext::class)->track($type, $model->getKey());
            }
        });
    }

    public function initializeHasSocialInteractions(): void
    {
        // Les compteurs sont aussi « appends » : toujours présents (0 par
        // défaut), même si la colonne n'a pas été sélectionnée ou si le
        // modèle vient d'être créé (le défaut SQL n'est pas relu).
        $this->append(['likes_count', 'comments_count', 'reposts_count', 'is_liked', 'is_reposted']);
    }

    public function getLikesCountAttribute(): int
    {
        // Lu dans les attributs bruts : pour un « append », Laravel passe
        // toujours null comme valeur à l'accesseur.
        return (int) ($this->attributes['likes_count'] ?? 0);
    }

    public function getCommentsCountAttribute(): int
    {
        // Lu dans les attributs bruts : pour un « append », Laravel passe
        // toujours null comme valeur à l'accesseur.
        return (int) ($this->attributes['comments_count'] ?? 0);
    }

    public function getRepostsCountAttribute(): int
    {
        // Lu dans les attributs bruts : pour un « append », Laravel passe
        // toujours null comme valeur à l'accesseur.
        return (int) ($this->attributes['reposts_count'] ?? 0);
    }

    public function getIsLikedAttribute(): bool
    {
        if (! $this->exists) {
            return false;
        }

        return app(SocialContext::class)->isLiked(SocialTarget::typeOf($this), $this->getKey());
    }

    public function getIsRepostedAttribute(): bool
    {
        if (! $this->exists) {
            return false;
        }

        return app(SocialContext::class)->isReposted(SocialTarget::typeOf($this), $this->getKey());
    }
}
