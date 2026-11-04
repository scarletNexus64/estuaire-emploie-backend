<?php

namespace App\Services\Social;

use App\Models\Job;
use App\Models\QuickService;
use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Fil d'accueil social : republications des autres utilisateurs + offres
 * récentes des entreprises suivies, 30 derniers jours, publications encore en
 * ligne uniquement, une entrée par publication. Classement (v2) : d'abord
 * les republications des personnes suivies et les offres des entreprises
 * suivies, puis les autres republications ; plus récent d'abord dans chaque
 * groupe. `actor_followed` indique si le visiteur suit le republieur.
 *
 * Aussi : activité publique d'un utilisateur (profil Réseau), même forme
 * d'élément.
 *
 * Les deux sources sont agrégées par publication en SQL (bornées par le
 * volume de 30 jours), fusionnées/dédoublonnées en PHP, puis seule la page
 * demandée est hydratée — avec exactement les mêmes relations/décorations que
 * GET /jobs et GET /quick-services pour que `item` ait la même forme.
 */
class SocialFeedService
{
    private const WINDOW_DAYS = 30;

    // Plafonds du fil (paginé par priorité/récence, les pages lointaines ne
    // sont jamais consultées).
    private const MAX_GROUPS = 300;

    private const POOL_SIZE = 1500;

    private const POOL_TTL = 120;

    public function feed(User $user, int $page, int $perPage, ?string $candidateCity = null): LengthAwarePaginator
    {
        $since = now()->subDays(self::WINDOW_DAYS);
        $userId = (int) $user->id;

        // Priorité 1 : republications des personnes suivies + offres des
        // entreprises suivies ; priorité 0 : autres republications.
        $entries = [];
        foreach ($this->repostActivities($userId, $since, true) as $row) {
            $this->keepBest($entries, $this->repostEntry($row, 1, true));
        }
        foreach ($this->repostActivities($userId, $since, false) as $row) {
            $this->keepBest($entries, $this->repostEntry($row, 0, false));
        }
        foreach ($this->followedCompanyJobs($userId, $since, $candidateCity) as $row) {
            $this->keepBest($entries, [
                'id' => 'follow_job_'.(int) $row->id,
                'kind' => 'followed_company_job',
                'item_type' => SocialTarget::JOB,
                'item_id' => (int) $row->id,
                'repost_id' => null,
                'at' => Carbon::parse($row->activity_at),
                'priority' => 1,
                'actor_followed' => false,
            ]);
        }

        $entries = array_values($entries);
        usort($entries, function ($a, $b) {
            return [$b['priority'], $b['at']->getTimestamp(), $b['repost_id'] ?? 0, $b['item_id']]
                <=> [$a['priority'], $a['at']->getTimestamp(), $a['repost_id'] ?? 0, $a['item_id']];
        });

        $total = count($entries);
        $slice = array_slice($entries, ($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator(
            $this->hydrate($slice),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }

    /**
     * Activité publique d'un utilisateur : ses republications (publications
     * encore en ligne) et, pour un recruteur, les offres publiées de ses
     * entreprises ; plus récent d'abord, paginé en SQL.
     */
    public function activity(User $subject, int $page, int $perPage): LengthAwarePaginator
    {
        $subjectId = (int) $subject->id;

        $reposts = DB::table('post_reposts')
            ->selectRaw("'repost' as kind, target_type as item_type, target_id as item_id, id as repost_id, created_at as activity_at")
            ->where('user_id', $subjectId)
            ->where(fn ($q) => $this->whereTargetActive($q));

        $union = $reposts;
        if ($subject->role === 'recruiter') {
            $companyIds = DB::table('recruiters')->where('user_id', $subjectId)->pluck('company_id')->all();
            if ($subject->current_company_id) {
                $companyIds[] = (int) $subject->current_company_id;
            }
            $companyIds = array_values(array_unique(array_map('intval', $companyIds)));

            if ($companyIds !== []) {
                $union = $reposts->unionAll(
                    DB::table('jobs')
                        ->selectRaw("'company_job' as kind, 'job' as item_type, id as item_id, NULL as repost_id, COALESCE(published_at, created_at) as activity_at")
                        ->whereIn('company_id', $companyIds)
                        ->where('status', 'published')
                        ->whereNull('deleted_at')
                );
            }
        }

        $paginator = DB::query()
            ->fromSub($union, 'activity')
            ->orderByDesc('activity_at')
            ->orderByDesc('item_id')
            ->paginate($perPage, ['*'], 'page', $page);

        $slice = [];
        foreach ($paginator->items() as $row) {
            $isRepost = $row->kind === 'repost';
            $slice[] = [
                'id' => $isRepost ? 'repost_'.(int) $row->repost_id : 'company_job_'.(int) $row->item_id,
                'kind' => $row->kind,
                'item_type' => $row->item_type,
                'item_id' => (int) $row->item_id,
                'repost_id' => $isRepost ? (int) $row->repost_id : null,
                'at' => Carbon::parse($row->activity_at),
                'priority' => 0,
                'actor_followed' => null, // calculé à l'hydratation
            ];
        }

        return new LengthAwarePaginator(
            $this->hydrate($slice),
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }

    /**
     * Commentaires de [$subject] (racines et réponses), du plus récent au plus
     * ancien, sur des publications toujours actives. Même format que le fil :
     * `kind = comment`, `comment` = texte du commentaire, `item` = publication.
     */
    public function comments(User $subject, int $page, int $perPage): LengthAwarePaginator
    {
        $paginator = DB::table('post_comments')
            ->where('user_id', (int) $subject->id)
            ->whereNull('deleted_at')
            ->where(fn ($q) => $this->whereTargetActive($q))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($perPage, ['id', 'target_type', 'target_id', 'parent_id', 'body', 'created_at'], 'page', $page);

        $rows = [];
        $slice = [];
        foreach ($paginator->items() as $row) {
            $key = 'comment_'.(int) $row->id;
            $rows[$key] = $row;
            $slice[] = [
                'id' => $key,
                'kind' => 'comment',
                'item_type' => $row->target_type,
                'item_id' => (int) $row->target_id,
                'repost_id' => null,
                'at' => Carbon::parse($row->created_at),
                'priority' => 0,
                'actor_followed' => false,
            ];
        }

        // [$subject] peut n'avoir que quelques colonnes chargées (visibleUser).
        $actor = SocialPresenter::user(User::query()->whereKey($subject->id)->first(SocialPresenter::USER_COLUMNS));
        $items = array_map(function (array $item) use ($rows, $actor) {
            $row = $rows[$item['id']];

            return array_merge($item, [
                'actor' => $actor,
                'comment' => (string) $row->body,
                'comment_id' => (int) $row->id,
                'is_reply' => $row->parent_id !== null,
            ]);
        }, $this->hydrate($slice));

        return new LengthAwarePaginator(
            $items,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }

    private function repostEntry(object $row, int $priority, bool $actorFollowed): array
    {
        return [
            'id' => 'repost_'.(int) $row->last_repost_id,
            'kind' => 'repost',
            'item_type' => $row->target_type,
            'item_id' => (int) $row->target_id,
            'repost_id' => (int) $row->last_repost_id,
            'at' => Carbon::parse($row->last_at),
            'priority' => $priority,
            'actor_followed' => $actorFollowed,
        ];
    }

    /** Une entrée par publication : priorité la plus haute, puis activité la plus récente. */
    private function keepBest(array &$entries, array $entry): void
    {
        $key = $entry['item_type'].':'.$entry['item_id'];
        $current = $entries[$key] ?? null;
        if ($current === null
            || $entry['priority'] > $current['priority']
            || ($entry['priority'] === $current['priority'] && $entry['at']->greaterThan($current['at']))) {
            $entries[$key] = $entry;
        }
    }

    /**
     * Une ligne par publication republiée par d'autres, avec la
     * republication la plus récente — restreinte aux personnes que suit
     * l'utilisateur ($followed = true) ou à toutes les autres (false).
     */
    private function repostActivities(int $userId, Carbon $since, bool $followed)
    {
        if (! $followed) {
            return $this->otherRepostActivities($userId);
        }

        $followedIds = DB::table('user_follows')->select('followed_id')->where('follower_id', $userId);

        try {
            // Plafonné : le fil est trié par récence, inutile de tout charger.
            return DB::table('post_reposts')
                ->select('target_type', 'target_id')
                ->selectRaw('MAX(id) as last_repost_id, MAX(created_at) as last_at')
                ->where('user_id', '!=', $userId)
                ->where('created_at', '>=', $since)
                ->where(fn ($q) => $this->whereTargetActive($q))
                ->whereIn('user_id', $followedIds)
                ->groupBy('target_type', 'target_id')
                ->orderByDesc('last_at')
                ->limit(self::MAX_GROUPS)
                ->get();
        } catch (\Throwable $e) {
            // user_follows absente (migration v2 non jouée) : fil v1.
            return collect();
        }
    }

    /**
     * Republications des autres (hors visiteur et personnes suivies).
     * Le pool des republications récentes est global (indépendant du
     * visiteur) et mis en cache 120 s ; le filtrage par visiteur et le
     * regroupement par publication se font ensuite en PHP, avec la même
     * sémantique que l'ancien GROUP BY (MAX(id), MAX(created_at)).
     */
    private function otherRepostActivities(int $userId)
    {
        $pool = Cache::remember('social_feed:recent_reposts:v1:'.self::WINDOW_DAYS, self::POOL_TTL, function () {
            return DB::table('post_reposts')
                ->select('id', 'user_id', 'target_type', 'target_id', 'created_at')
                ->where('created_at', '>=', now()->subDays(self::WINDOW_DAYS))
                ->where(fn ($q) => $this->whereTargetActive($q))
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->limit(self::POOL_SIZE)
                ->get()
                ->map(fn ($r) => [
                    'id' => (int) $r->id,
                    'user_id' => (int) $r->user_id,
                    'target_type' => (string) $r->target_type,
                    'target_id' => (int) $r->target_id,
                    'created_at' => (string) $r->created_at,
                ])
                ->all();
        });

        try {
            $excluded = DB::table('user_follows')->where('follower_id', $userId)->pluck('followed_id')
                ->map(fn ($id) => (int) $id)->flip()->all();
        } catch (\Throwable $e) {
            // user_follows absente (migration v2 non jouée) : fil v1.
            $excluded = [];
        }
        $excluded[$userId] = true;

        $groups = [];
        foreach ($pool as $r) {
            if (isset($excluded[$r['user_id']])) {
                continue;
            }
            $key = $r['target_type'].':'.$r['target_id'];
            if (! isset($groups[$key])) {
                if (count($groups) >= self::MAX_GROUPS) {
                    continue;
                }
                $groups[$key] = (object) [
                    'target_type' => $r['target_type'],
                    'target_id' => $r['target_id'],
                    'last_repost_id' => $r['id'],
                    'last_at' => $r['created_at'],
                ];

                continue;
            }
            $g = $groups[$key];
            if ($r['id'] > $g->last_repost_id) {
                $g->last_repost_id = $r['id'];
            }
            if ($r['created_at'] > $g->last_at) {
                $g->last_at = $r['created_at'];
            }
        }

        return collect(array_values($groups));
    }

    /** Restreint post_reposts aux publications encore en ligne. */
    private function whereTargetActive($q): void
    {
        $q->where(function ($j) {
            $j->where('target_type', SocialTarget::JOB)
                ->whereIn('target_id', DB::table('jobs')->select('id')
                    ->where('status', 'published')
                    ->whereNull('deleted_at'));
        })->orWhere(function ($s) {
            $s->where('target_type', SocialTarget::QUICK_SERVICE)
                ->whereIn('target_id', DB::table('quick_services')->select('id')
                    ->whereIn('status', SocialTarget::QUICK_SERVICE_ACTIVE_STATUSES)
                    ->whereNull('deleted_at')
                    ->where(function ($e) {
                        $e->whereNull('expires_at')->orWhere('expires_at', '>', now());
                    }));
        });
    }

    /** Offres publiées ces 30 derniers jours par les entreprises suivies. */
    private function followedCompanyJobs(int $userId, Carbon $since, ?string $candidateCity)
    {
        $query = Job::query()
            ->select('id')
            ->selectRaw('COALESCE(published_at, created_at) as activity_at')
            ->where('status', 'published')
            ->whereIn('company_id', DB::table('company_follows')->select('company_id')->where('user_id', $userId))
            ->where(function ($q) use ($since) {
                $q->where('published_at', '>=', $since)
                    ->orWhere(function ($w) use ($since) {
                        $w->whereNull('published_at')->where('created_at', '>=', $since);
                    });
            })
            ->where(function ($q) use ($userId) {
                $q->whereNull('posted_by')->orWhere('posted_by', '!=', $userId);
            });

        // Même règle de visibilité géographique que GET /jobs, seulement si
        // l'app transmet la ville du candidat.
        if ($candidateCity !== null && trim($candidateCity) !== '') {
            $query->visibleFor($candidateCity);
        }

        return $query->orderByDesc('activity_at')->limit(self::MAX_GROUPS)->toBase()->get();
    }

    private function hydrate(array $slice): array
    {
        if ($slice === []) {
            return [];
        }

        $jobIds = [];
        $serviceIds = [];
        $repostIds = [];
        foreach ($slice as $entry) {
            if ($entry['item_type'] === SocialTarget::JOB) {
                $jobIds[] = $entry['item_id'];
            } else {
                $serviceIds[] = $entry['item_id'];
            }
            if ($entry['repost_id']) {
                $repostIds[] = $entry['repost_id'];
            }
        }

        // Mêmes relations que JobController::index.
        $jobs = $jobIds === [] ? collect() : Job::with([
            'company',
            'category',
            'contractType',
            'specialty',
            'skillTests' => function ($query) {
                $query->where('is_active', true)
                    ->select('id', 'job_id', 'title', 'description', 'duration_minutes', 'passing_score');
            },
        ])
            ->withCount(['skillTests' => function ($query) {
                $query->where('is_active', true);
            }])
            ->whereIn('id', $jobIds)
            ->get()
            ->keyBy('id');

        // Mêmes relations que QuickServiceController::index.
        $services = $serviceIds === [] ? collect() : QuickService::with(['user:'.implode(',', QuickService::LIST_USER_COLUMNS), 'category'])
            ->withCount('responses')
            ->whereIn('id', $serviceIds)
            ->get()
            ->keyBy('id');
        $this->decorateServicePrices($services);

        $reposts = $repostIds === [] ? collect() : DB::table('post_reposts')
            ->whereIn('id', $repostIds)
            ->get(['id', 'user_id', 'comment'])
            ->keyBy('id');
        $actors = $reposts->isEmpty() ? collect() : User::whereIn('id', $reposts->pluck('user_id')->unique()->all())
            ->get(SocialPresenter::USER_COLUMNS)
            ->keyBy('id');
        $context = app(SocialContext::class);
        foreach ($actors as $actor) {
            $context->track('user', $actor->id);
        }

        $items = [];
        foreach ($slice as $entry) {
            $item = $entry['item_type'] === SocialTarget::JOB
                ? $jobs->get($entry['item_id'])
                : $services->get($entry['item_id']);
            if (! $item) {
                continue;
            }

            $repost = $entry['repost_id'] ? $reposts->get($entry['repost_id']) : null;
            $actorFollowed = $entry['actor_followed'];
            if ($actorFollowed === null) {
                $actorFollowed = $repost !== null && $context->isFollowingUser($repost->user_id);
            }

            $items[] = [
                'id' => $entry['id'],
                'kind' => $entry['kind'],
                'created_at' => SocialPresenter::date($entry['at']),
                'actor' => $repost ? SocialPresenter::user($actors->get($repost->user_id)) : null,
                'actor_followed' => (bool) $actorFollowed,
                'comment' => $repost?->comment,
                'item_type' => $entry['item_type'],
                'item' => $item,
            ];
        }

        return $items;
    }

    /**
     * Copie de QuickServiceController::decoratePrices (privée) : prix
     * d'affichage dans la devise du visiteur, pour un `item` identique à
     * GET /quick-services.
     */
    private function decorateServicePrices($services): void
    {
        if ($services->isEmpty()) {
            return;
        }

        $currency = app(CurrencyService::class);
        $target = $currency->resolveCurrency(auth('sanctum')->user());

        foreach ($services as $service) {
            $service->base_currency = CurrencyService::BASE_CURRENCY;
            $service->display_currency = $target;

            if ($service->price_min !== null) {
                $min = $currency->displayFor((float) $service->price_min, $target);
                $service->display_price_min = $min['display_price'];
                $service->display_price_min_formatted = $min['display_price_formatted'];
            }
            if ($service->price_max !== null) {
                $max = $currency->displayFor((float) $service->price_max, $target);
                $service->display_price_max = $max['display_price'];
                $service->display_price_max_formatted = $max['display_price_formatted'];
            }
        }
    }
}
