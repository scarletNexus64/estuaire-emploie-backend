<?php

namespace App\Services\Social;

use App\Models\Company;
use App\Models\PostComment;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Formats JSON de la couche sociale (contrat partagé avec l'app Flutter).
 */
class SocialPresenter
{
    /** Colonnes utilisateur nécessaires aux payloads sociaux. */
    public const USER_COLUMNS = ['id', 'name', 'profile_photo', 'specialty'];

    /**
     * Colonnes PUBLIQUES d'une carte utilisateur (SocialUserCard). Jamais
     * d'email, téléphone, mot de passe, jetons ni soldes.
     */
    public const CARD_COLUMNS = [
        'users.id', 'users.name', 'users.profile_photo', 'users.specialty', 'users.role',
        'users.country', 'users.current_company_id', 'users.followers_count', 'users.following_count',
    ];

    /** Colonnes du profil public (carte + bio, compétences, ancienneté). */
    public const PROFILE_COLUMNS = [...self::CARD_COLUMNS, 'users.bio', 'users.skills', 'users.cover_photo', 'users.created_at'];

    /** Relations à précharger pour l'entreprise des recruteurs (aucun N+1). */
    public static function cardRelations(): array
    {
        $companyColumns = fn ($q) => $q->select(['companies.id', 'companies.name', 'companies.logo', 'companies.city']);

        return [
            'currentCompany' => $companyColumns,
            'recruiter' => fn ($q) => $q->select(['id', 'user_id', 'company_id']),
            'recruiter.company' => $companyColumns,
        ];
    }

    /** URL absolue d'une photo de profil (chemin du disk public ou URL). */
    public static function photoUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url('storage/'.ltrim($path, '/'));
    }

    /** `{id, name, photo, headline}` — headline = spécialité, sinon null. */
    public static function user(?User $user): array
    {
        if (! $user) {
            return [
                'id' => null,
                'name' => __('social.deleted_user'),
                'photo' => null,
                'headline' => null,
            ];
        }

        $headline = is_string($user->specialty ?? null) && trim($user->specialty) !== ''
            ? trim($user->specialty)
            : null;

        return [
            'id' => (int) $user->id,
            'name' => (string) $user->name,
            'photo' => self::photoUrl($user->profile_photo),
            'headline' => $headline,
        ];
    }

    /**
     * Cartes utilisateurs d'une page : enregistre d'abord tous les ids dans
     * SocialContext pour que is_following / follows_you / mutual_count
     * soient chargés en une requête chacun.
     *
     * @param  iterable<User|null>  $users
     * @param  array<int, array>  $extra  champs additionnels par id utilisateur
     */
    public static function userCards(iterable $users, array $extra = []): array
    {
        $context = app(SocialContext::class);
        $list = [];
        foreach ($users as $user) {
            if ($user instanceof User) {
                $context->track('user', $user->id);
                $list[] = $user;
            }
        }

        return array_map(
            fn (User $user) => self::userCard($user) + ($extra[(int) $user->id] ?? []),
            $list
        );
    }

    /** SocialUserCard (contrat v2). */
    public static function userCard(User $user): array
    {
        $context = app(SocialContext::class);
        $viewerId = $context->viewerId();
        $id = (int) $user->id;
        $isSelf = $viewerId !== null && $viewerId === $id;
        $company = self::companyOf($user);

        return self::user($user) + [
            'city' => $company && is_string($company->city) && trim($company->city) !== '' ? trim($company->city) : null,
            'country' => is_string($user->country ?? null) && $user->country !== '' ? $user->country : null,
            'role' => $user->role !== null ? (string) $user->role : null,
            'company' => $company ? [
                'id' => (int) $company->id,
                'name' => (string) $company->name,
                'logo' => $company->logo_url,
            ] : null,
            'followers_count' => (int) ($user->getAttributes()['followers_count'] ?? 0),
            'following_count' => (int) ($user->getAttributes()['following_count'] ?? 0),
            'is_following' => ! $isSelf && $context->isFollowingUser($id),
            'follows_you' => ! $isSelf && $context->followsViewer($id),
            'mutual_count' => $isSelf ? null : $context->mutualCount($id),
        ];
    }

    /** Entreprise affichée sur la carte d'un recruteur (entreprise active, sinon la première). */
    private static function companyOf(User $user): ?Company
    {
        if ($user->role !== 'recruiter') {
            return null;
        }

        $company = $user->relationLoaded('currentCompany') ? $user->getRelation('currentCompany') : null;
        if (! $company && $user->relationLoaded('recruiter')) {
            $company = $user->getRelation('recruiter')?->company;
        }

        return $company instanceof Company ? $company : null;
    }

    public static function date(?CarbonInterface $date): ?string
    {
        return $date?->toIso8601String();
    }

    /**
     * @param  int[]  $memberIds  utilisateurs « auteurs » de la publication
     *                            (membres de l'entreprise / auteur du service)
     * @param  bool  $canModerate  le visiteur peut-il supprimer tout commentaire
     */
    public static function comment(PostComment $comment, ?int $viewerId, array $memberIds, bool $canModerate): array
    {
        $isAuthor = $viewerId !== null && (int) $comment->user_id === $viewerId;
        $context = app(SocialContext::class);

        $payload = [
            'id' => (int) $comment->id,
            'body' => (string) $comment->body,
            'created_at' => self::date($comment->created_at),
            'edited' => $comment->edited_at !== null,
            'parent_id' => $comment->parent_id !== null ? (int) $comment->parent_id : null,
            'replies_count' => (int) ($comment->replies_count ?? 0),
            'likes_count' => (int) ($comment->getAttributes()['likes_count'] ?? 0),
            'is_liked' => $comment->exists && $context->isCommentLiked($comment->id),
            'user' => self::user($comment->user) + [
                'is_company_member' => in_array((int) $comment->user_id, $memberIds, true),
                'is_following' => $comment->user !== null && ! $isAuthor && $context->isFollowingUser($comment->user_id),
            ],
            'can_edit' => $isAuthor,
            'can_delete' => $isAuthor || $canModerate,
        ];

        if ($comment->relationLoaded('previewReplies')) {
            $payload['replies_preview'] = $comment->getRelation('previewReplies')
                ->map(fn (PostComment $reply) => self::comment($reply, $viewerId, $memberIds, $canModerate))
                ->values()
                ->all();
        }

        return $payload;
    }

    /** Méta de pagination du contrat. */
    public static function meta(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
    }

    /** per_page : défaut 20, max 50. */
    public static function perPage($value): int
    {
        $perPage = (int) ($value ?: 20);

        return max(1, min(50, $perPage));
    }
}
