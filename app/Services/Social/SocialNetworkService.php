<?php

namespace App\Services\Social;

use App\Models\CompanyFollow;
use App\Models\PostComment;
use App\Models\PostRepost;
use App\Models\Resume;
use App\Models\User;
use App\Models\UserFollow;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Onglet « Réseau » (style LinkedIn) : abonnements entre utilisateurs,
 * suggestions, recherche, résumé et profil public.
 *
 * Compteurs dénormalisés users.followers_count / users.following_count mis
 * à jour atomiquement (SocialCounter) dans la transaction de la bascule.
 */
class SocialNetworkService
{
    public function __construct(
        private readonly SocialNotifier $notifier,
        private readonly SocialContext $context,
    ) {
    }

    /** @return array{following: bool, followers_count: int} */
    public function toggleFollow(User $viewer, int $userId): array
    {
        if ((int) $viewer->id === $userId) {
            throw SocialException::unprocessable('social.errors.cannot_follow_self');
        }

        $target = User::query()->whereKey($userId)->first(['id', 'name', 'is_active']);
        if (! $target) {
            throw SocialException::notFound('social.errors.user_not_found');
        }

        $following = DB::transaction(function () use ($viewer, $target) {
            $deleted = UserFollow::where('follower_id', $viewer->id)
                ->where('followed_id', $target->id)
                ->delete();

            if ($deleted > 0) {
                SocialCounter::decrement('users', (int) $target->id, 'followers_count', $deleted);
                SocialCounter::decrement('users', (int) $viewer->id, 'following_count', $deleted);

                return false;
            }

            // Se désabonner reste toujours possible ; s'abonner exige un
            // compte actif.
            if (! $target->is_active) {
                throw SocialException::notFound('social.errors.user_not_found');
            }

            try {
                UserFollow::create(['follower_id' => $viewer->id, 'followed_id' => $target->id]);
            } catch (UniqueConstraintViolationException) {
                // Double appui concurrent : déjà abonné par l'autre requête.
                return true;
            }

            SocialCounter::increment('users', (int) $target->id, 'followers_count');
            SocialCounter::increment('users', (int) $viewer->id, 'following_count');

            $this->notifier->notify(
                SocialNotifier::USER_FOLLOW,
                (int) $viewer->id,
                [(int) $target->id],
                "user:{$target->id}",
                ['user_id' => (string) $viewer->id],
                ['name' => (string) $viewer->name],
            );

            return true;
        }, 3); // 3 tentatives : interblocage (verrous de trou InnoDB) sur double appui

        $this->context->remember('user_following', (int) $target->id, $following);

        return [
            'following' => $following,
            'followers_count' => SocialCounter::value('users', (int) $target->id, 'followers_count'),
        ];
    }

    /** Abonnés d'un utilisateur, abonnement le plus récent d'abord. */
    public function followers(User $viewer, int $userId, int $perPage): LengthAwarePaginator
    {
        $this->visibleUser($viewer, $userId);

        return $this->followPage('followed_id', $userId, 'follower', $perPage);
    }

    /** Abonnements d'un utilisateur, le plus récent d'abord. */
    public function following(User $viewer, int $userId, int $perPage): LengthAwarePaginator
    {
        $this->visibleUser($viewer, $userId);

        return $this->followPage('follower_id', $userId, 'followed', $perPage);
    }

    /** @return array{followers_count: int, following_count: int, followed_companies_count: int, new_followers_7d: int} */
    public function summary(User $viewer): array
    {
        $counts = DB::table('users')->where('id', $viewer->id)->first(['followers_count', 'following_count']);

        return [
            'followers_count' => (int) ($counts->followers_count ?? 0),
            'following_count' => (int) ($counts->following_count ?? 0),
            'followed_companies_count' => CompanyFollow::where('user_id', $viewer->id)->whereHas('company')->count(),
            'new_followers_7d' => UserFollow::where('followed_id', $viewer->id)
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];
    }

    /**
     * Personnes à suivre. Classement SQL « au mieux » : abonnés en commun,
     * même spécialité, même ville (entreprise), même pays ; puis les plus
     * suivis ; puis activité récente (dernière connexion).
     *
     * Perf : le score n'est calculé que sur un ensemble de candidats
     * présélectionnés (amis d'amis, même spécialité/ville/pays, plus suivis),
     * et la liste classée des ids est mise en cache 10 min par visiteur.
     * Les exclusions (soi, déjà suivis, inactifs) sont réappliquées à chaque
     * requête, la pagination reste « LengthAware » (même meta pour l'app).
     */
    public function suggestions(User $viewer, int $perPage): LengthAwarePaginator
    {
        $me = (int) $viewer->id;
        $page = max(1, \Illuminate\Pagination\Paginator::resolveCurrentPage('page'));

        $rankedIds = Cache::remember("social:suggestions:v1:{$me}", 600, fn () => $this->rankedSuggestionIds($viewer));

        // Exclusions toujours à jour : abonnements faits depuis la mise en cache.
        $followed = UserFollow::where('follower_id', $me)->pluck('followed_id')->map(fn ($id) => (int) $id)->flip()->all();
        $ids = array_values(array_filter($rankedIds, fn ($id) => $id !== $me && ! isset($followed[$id])));

        $pageIds = array_slice($ids, ($page - 1) * $perPage, $perPage);
        $users = $pageIds === [] ? collect() : $this->discoverable($me)
            ->whereIn('users.id', $pageIds)
            ->get()
            ->sortBy(fn ($u) => array_search((int) $u->id, $pageIds, true))
            ->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $users,
            count($ids),
            $perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }

    /** Ids des suggestions classés par pertinence (sans pagination). @return int[] */
    private function rankedSuggestionIds(User $viewer): array
    {
        $me = (int) $viewer->id;
        $viewer->loadMissing(SocialPresenter::cardRelations());

        $specialty = is_string($viewer->specialty) ? mb_strtolower(trim($viewer->specialty)) : '';
        $country = is_string($viewer->country) ? trim($viewer->country) : '';
        $companyCity = $viewer->currentCompany?->city ?? $viewer->recruiter?->company?->city;
        $city = is_string($companyCity) ? mb_strtolower(trim($companyCity)) : '';

        // Présélection des candidats (chaque source plafonnée).
        $candidates = DB::table('user_follows as theirs')
            ->join('user_follows as mine', 'mine.followed_id', '=', 'theirs.follower_id')
            ->where('mine.follower_id', $me)
            ->groupBy('theirs.followed_id')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(500)
            ->pluck('theirs.followed_id')
            ->all();

        $active = fn () => DB::table('users')
            ->where('is_active', true)
            ->where('role', '!=', 'admin')
            ->whereNull('deleted_at')
            ->where('id', '!=', $me);

        if ($specialty !== '') {
            $candidates = array_merge($candidates, $active()
                ->whereRaw('LOWER(TRIM(specialty)) = ?', [$specialty])
                ->orderByDesc('followers_count')->limit(300)->pluck('id')->all());
        }
        if ($city !== '') {
            $candidates = array_merge($candidates, $active()
                ->whereIn('current_company_id', DB::table('companies')->select('id')->whereRaw('LOWER(TRIM(city)) = ?', [$city]))
                ->orderByDesc('followers_count')->limit(300)->pluck('id')->all());
        }
        if ($country !== '') {
            $candidates = array_merge($candidates, $active()
                ->where('country', $country)
                ->orderByDesc('followers_count')->limit(300)->pluck('id')->all());
        }
        // Complément : les plus suivis puis les plus récemment actifs.
        $candidates = array_merge($candidates, $active()
            ->orderByDesc('followers_count')->orderByDesc('last_login_at')->orderByDesc('id')
            ->limit(300)->pluck('id')->all());

        $candidates = array_values(array_unique(array_map('intval', $candidates)));
        if ($candidates === []) {
            return [];
        }

        $parts = ['LEAST((SELECT COUNT(*) FROM user_follows theirs INNER JOIN user_follows mine ON mine.followed_id = theirs.follower_id WHERE mine.follower_id = ? AND theirs.followed_id = users.id), 5) * 3'];
        $bindings = [$me];
        if ($specialty !== '') {
            $parts[] = '(CASE WHEN LOWER(TRIM(users.specialty)) = ? THEN 4 ELSE 0 END)';
            $bindings[] = $specialty;
        }
        if ($city !== '') {
            $parts[] = '(CASE WHEN EXISTS (SELECT 1 FROM companies c WHERE c.id = users.current_company_id AND LOWER(TRIM(c.city)) = ?) THEN 2 ELSE 0 END)';
            $bindings[] = $city;
        }
        if ($country !== '') {
            $parts[] = '(CASE WHEN users.country = ? THEN 1 ELSE 0 END)';
            $bindings[] = $country;
        }

        return User::query()
            ->select('users.id')
            ->where('users.is_active', true)
            ->where('users.role', '!=', 'admin')
            ->where('users.id', '!=', $me)
            ->whereIn('users.id', $candidates)
            ->whereNotExists(function ($q) use ($me) {
                $q->select(DB::raw(1))
                    ->from('user_follows')
                    ->whereColumn('user_follows.followed_id', 'users.id')
                    ->where('user_follows.follower_id', $me);
            })
            ->selectRaw('('.implode(' + ', $parts).') as relevance', $bindings)
            ->orderByDesc('relevance')
            ->orderByDesc('users.followers_count')
            ->orderByDesc('users.last_login_at')
            ->orderByDesc('users.id')
            ->toBase()
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /** Recherche par nom, spécialité, ville ou nom d'entreprise (q ≥ 2 caractères). */
    public function search(User $viewer, string $q, int $perPage): LengthAwarePaginator
    {
        $q = trim(preg_replace('/\s+/u', ' ', $q));
        if (mb_strlen($q) < 2) {
            throw SocialException::unprocessable('social.validation.search_min');
        }

        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], mb_substr($q, 0, 100));
        $like = "%{$escaped}%";

        $companyMatch = function ($c) use ($like) {
            $c->where('companies.name', 'like', $like)->orWhere('companies.city', 'like', $like);
        };

        return $this->discoverable((int) $viewer->id)
            ->where(function (Builder $w) use ($like, $companyMatch) {
                $w->where('users.name', 'like', $like)
                    ->orWhere('users.specialty', 'like', $like)
                    ->orWhereHas('currentCompany', $companyMatch)
                    ->orWhereHas('companies', $companyMatch);
            })
            ->orderByRaw('CASE WHEN users.name LIKE ? THEN 0 ELSE 1 END', ["{$escaped}%"])
            ->orderByDesc('users.followers_count')
            ->orderByDesc('users.id')
            ->paginate($perPage);
    }

    /**
     * Recherche d'entreprises (nom, ville, secteur) pour l'onglet Réseau :
     * entreprises non suspendues, celles dont le nom commence par la saisie
     * d'abord, puis les plus suivies.
     */
    public function searchCompanies(string $q, int $perPage): LengthAwarePaginator
    {
        $q = trim(preg_replace('/\s+/u', ' ', $q));
        if (mb_strlen($q) < 2) {
            throw SocialException::unprocessable('social.validation.search_min');
        }

        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], mb_substr($q, 0, 100));
        $like = "%{$escaped}%";

        return \App\Models\Company::query()
            ->select(['companies.id', 'companies.name', 'companies.logo', 'companies.sector', 'companies.city', 'companies.status', 'companies.followers_count'])
            ->where('companies.status', '!=', 'suspended')
            ->where(function (Builder $w) use ($like) {
                $w->where('companies.name', 'like', $like)
                    ->orWhere('companies.sector', 'like', $like)
                    ->orWhere('companies.city', 'like', $like);
            })
            ->orderByRaw('CASE WHEN companies.name LIKE ? THEN 0 ELSE 1 END', ["{$escaped}%"])
            ->orderByDesc('companies.followers_count')
            ->orderByDesc('companies.id')
            ->paginate($perPage);
    }

    /** Profil public (carte + bio, compétences, statistiques). */
    public function profile(User $viewer, int $userId): array
    {
        $user = User::query()
            ->select(SocialPresenter::PROFILE_COLUMNS)
            ->addSelect('users.is_active')
            ->with(SocialPresenter::cardRelations())
            ->whereKey($userId)
            ->first();

        $isSelf = (int) $viewer->id === $userId;
        if (! $user || (! $user->is_active && ! $isSelf)) {
            throw SocialException::notFound('social.errors.user_not_found');
        }

        $bio = is_string($user->bio) && trim($user->bio) !== '' ? trim($user->bio) : null;

        return SocialPresenter::userCard($user) + [
            'bio' => $bio,
            'cover' => SocialPresenter::photoUrl($user->cover_photo),
            'member_since' => SocialPresenter::date($user->created_at),
            'skills' => $this->skillsOf($user),
            'stats' => [
                'reposts_count' => PostRepost::where('user_id', $user->id)->count(),
                'comments_count' => PostComment::where('user_id', $user->id)->count(),
            ],
            'is_self' => $isSelf,
        ];
    }

    /** Utilisateur consultable (actif, ou soi-même) — 404 sinon. */
    public function visibleUser(User $viewer, int $userId): User
    {
        $user = User::query()->whereKey($userId)->first(['id', 'name', 'role', 'current_company_id', 'is_active']);
        if (! $user || (! $user->is_active && (int) $viewer->id !== $userId)) {
            throw SocialException::notFound('social.errors.user_not_found');
        }

        return $user;
    }

    /** Comptes visibles dans le Réseau : actifs, hors admins, hors soi. */
    private function discoverable(int $me): Builder
    {
        return User::query()
            ->select(SocialPresenter::CARD_COLUMNS)
            ->with(SocialPresenter::cardRelations())
            ->where('users.is_active', true)
            ->where('users.role', '!=', 'admin')
            ->where('users.id', '!=', $me);
    }

    private function followPage(string $column, int $userId, string $relation, int $perPage): LengthAwarePaginator
    {
        return UserFollow::query()
            ->where($column, $userId)
            ->whereHas($relation, fn ($q) => $q->where('is_active', true))
            ->with([$relation => fn ($q) => $q->select(SocialPresenter::CARD_COLUMNS)->with(SocialPresenter::cardRelations())])
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /**
     * Compétences publiques : users.skills (texte libre « a, b, c » ou JSON),
     * sinon celles du CV par défaut / le plus récent.
     *
     * @return string[]
     */
    private function skillsOf(User $user): array
    {
        $skills = $this->normalizeSkills($user->skills);
        if ($skills !== []) {
            return $skills;
        }

        $resume = Resume::query()
            ->where('user_id', $user->id)
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->first(['id', 'skills']);

        return $resume ? $this->normalizeSkills($resume->skills) : [];
    }

    /** @return string[] */
    private function normalizeSkills(mixed $raw): array
    {
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $raw = is_array($decoded) ? $decoded : preg_split('/[,;\n|]+/u', $raw);
        }
        if (! is_array($raw)) {
            return [];
        }

        $names = [];
        foreach ($raw as $entry) {
            if (is_array($entry)) {
                $entry = $entry['name'] ?? $entry['skill'] ?? $entry['label'] ?? $entry['title'] ?? null;
            }
            if (! is_string($entry) && ! is_numeric($entry)) {
                continue;
            }
            $name = trim((string) $entry);
            if ($name !== '' && mb_strlen($name) <= 80) {
                $names[mb_strtolower($name)] = $name;
            }
        }

        return array_slice(array_values($names), 0, 30);
    }
}
