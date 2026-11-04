<?php

namespace App\Services\Social;

use App\Models\Job;
use App\Models\QuickService;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Registre unique des publications « sociales » (likables / commentables /
 * republiables) : alias stocké en base ⇄ modèle, règles de visibilité et
 * propriétaires (destinataires des notifications, modérateurs).
 *
 * Les tables sociales stockent l'alias court (`job`, `quick_service`) dans
 * `target_type`, jamais le nom de classe.
 */
class SocialTarget
{
    public const JOB = 'job';
    public const QUICK_SERVICE = 'quick_service';

    public const TYPES = [
        self::JOB => Job::class,
        self::QUICK_SERVICE => QuickService::class,
    ];

    /** Statuts d'un service rapide considéré comme publié/actif. */
    public const QUICK_SERVICE_ACTIVE_STATUSES = ['approved', 'open', 'in_progress'];

    public static function isValidType(string $type): bool
    {
        return array_key_exists($type, self::TYPES);
    }

    public static function typeOf(Model $model): ?string
    {
        return match (true) {
            $model instanceof Job => self::JOB,
            $model instanceof QuickService => self::QUICK_SERVICE,
            default => null,
        };
    }

    public static function tableOf(string $type): string
    {
        return $type === self::JOB ? 'jobs' : 'quick_services';
    }

    /**
     * Charge une publication. `$requireActive` : seules les publications en
     * ligne acceptent de nouvelles interactions (like, commentaire, repost).
     *
     * @throws SocialException 404
     */
    public static function find(string $type, int $id, bool $requireActive = false): Model
    {
        if (! self::isValidType($type)) {
            throw SocialException::notFound();
        }

        $query = self::TYPES[$type]::query()->whereKey($id);
        if ($requireActive) {
            self::scopeActive($type, $query);
        }

        $model = $query->first();
        if (! $model) {
            throw SocialException::notFound('social.errors.item_not_found');
        }

        return $model;
    }

    /** Restreint une requête aux publications en ligne. */
    public static function scopeActive(string $type, Builder $query): Builder
    {
        if ($type === self::JOB) {
            return $query->where('status', 'published');
        }

        return $query->whereIn('status', self::QUICK_SERVICE_ACTIVE_STATUSES)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public static function title(Model $item): string
    {
        return (string) ($item->title ?? '');
    }

    /**
     * Utilisateurs « propriétaires » de la publication : recruteurs de
     * l'entreprise (+ auteur de l'offre) pour une offre, auteur pour un
     * service rapide.
     *
     * @return int[]
     */
    public static function ownerUserIds(Model $item): array
    {
        if ($item instanceof Job) {
            $ids = $item->company_id
                ? Recruiter::where('company_id', $item->company_id)->pluck('user_id')->all()
                : [];
            if ($item->posted_by) {
                $ids[] = $item->posted_by;
            }

            return array_values(array_unique(array_map('intval', $ids)));
        }

        if ($item instanceof QuickService) {
            return $item->user_id ? [(int) $item->user_id] : [];
        }

        return [];
    }

    /**
     * L'utilisateur « possède »-t-il la publication (membre de l'entreprise de
     * l'offre / auteur du service) ? Sert à la modération et à l'interdiction
     * de republier sa propre publication.
     */
    public static function isOwner(?User $user, Model $item): bool
    {
        if (! $user) {
            return false;
        }

        if ($item instanceof Job) {
            if ($item->posted_by && (int) $item->posted_by === (int) $user->id) {
                return true;
            }

            return $item->company_id
                && Recruiter::where('company_id', $item->company_id)->where('user_id', $user->id)->exists();
        }

        if ($item instanceof QuickService) {
            return (int) $item->user_id === (int) $user->id;
        }

        return false;
    }
}
