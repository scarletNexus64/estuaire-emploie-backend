<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SocialResponses;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserFollow;
use App\Services\Social\SocialFeedService;
use App\Services\Social\SocialNetworkService;
use App\Services\Social\SocialPresenter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Onglet « Réseau » : abonnements entre utilisateurs, suggestions,
 * recherche, résumé, profil public et activité.
 */
class SocialNetworkController extends Controller
{
    use SocialResponses;

    public function __construct(
        private readonly SocialNetworkService $service,
        private readonly SocialFeedService $feed,
    ) {
    }

    /** POST /network/users/{user}/follow — bascule. */
    public function toggleFollow(Request $request, int $user): JsonResponse
    {
        return $this->socialAction(fn () => response()->json([
            'success' => true,
            'data' => $this->service->toggleFollow($request->user(), $user),
        ]));
    }

    /** GET /network/users/{user}/followers */
    public function followers(Request $request, int $user): JsonResponse
    {
        return $this->socialAction(fn () => $this->followList(
            $this->service->followers($request->user(), $user, SocialPresenter::perPage($request->query('per_page'))),
            'follower'
        ));
    }

    /** GET /network/users/{user}/following */
    public function following(Request $request, int $user): JsonResponse
    {
        return $this->socialAction(fn () => $this->followList(
            $this->service->following($request->user(), $user, SocialPresenter::perPage($request->query('per_page'))),
            'followed'
        ));
    }

    /** GET /network/suggestions */
    public function suggestions(Request $request): JsonResponse
    {
        return $this->socialAction(fn () => $this->cardList(
            $this->service->suggestions($request->user(), SocialPresenter::perPage($request->query('per_page')))
        ));
    }

    /** GET /network/search?q= */
    public function search(Request $request): JsonResponse
    {
        return $this->socialAction(function () use ($request) {
            $q = $request->query('q');

            return $this->cardList($this->service->search(
                $request->user(),
                is_string($q) ? $q : '',
                SocialPresenter::perPage($request->query('per_page'))
            ));
        });
    }

    /** GET /network/search/companies?q= — entreprises (nom, ville, secteur). */
    public function searchCompanies(Request $request): JsonResponse
    {
        return $this->socialAction(function () use ($request) {
            $q = $request->query('q');
            $paginator = $this->service->searchCompanies(
                is_string($q) ? $q : '',
                SocialPresenter::perPage($request->query('per_page'))
            );
            $context = app(\App\Services\Social\SocialContext::class);

            return response()->json([
                'success' => true,
                'data' => $paginator->getCollection()->map(fn ($company) => [
                    'id' => (int) $company->id,
                    'name' => (string) $company->name,
                    'logo' => $company->logo_url,
                    'logo_url' => $company->logo_url,
                    'sector' => $company->sector,
                    'city' => $company->city,
                    'is_verified' => $company->status === 'verified',
                    'followers_count' => (int) ($company->getAttributes()['followers_count'] ?? 0),
                    'is_following' => $context->isFollowing($company->id),
                ])->values(),
                'meta' => SocialPresenter::meta($paginator),
            ]);
        });
    }

    /** GET /network/summary */
    public function summary(Request $request): JsonResponse
    {
        return $this->socialAction(fn () => response()->json([
            'success' => true,
            'data' => $this->service->summary($request->user()),
        ]));
    }

    /** GET /network/users/{user} — profil public. */
    public function show(Request $request, int $user): JsonResponse
    {
        return $this->socialAction(fn () => response()->json([
            'success' => true,
            'data' => $this->service->profile($request->user(), $user),
        ]));
    }

    /** GET /network/users/{user}/activity — même forme d'élément que GET /social/feed. */
    public function activity(Request $request, int $user): JsonResponse
    {
        return $this->socialAction(function () use ($request, $user) {
            $subject = $this->service->visibleUser($request->user(), $user);

            $paginator = $this->feed->activity(
                $subject,
                max(1, (int) $request->query('page', 1)),
                SocialPresenter::perPage($request->query('per_page')),
            );

            return response()->json([
                'success' => true,
                'data' => $paginator->items(),
                'meta' => SocialPresenter::meta($paginator),
            ]);
        });
    }

    /** GET /network/users/{user}/comments — commentaires de l'utilisateur. */
    public function comments(Request $request, int $user): JsonResponse
    {
        return $this->socialAction(function () use ($request, $user) {
            $subject = $this->service->visibleUser($request->user(), $user);

            $paginator = $this->feed->comments(
                $subject,
                max(1, (int) $request->query('page', 1)),
                SocialPresenter::perPage($request->query('per_page')),
            );

            return response()->json([
                'success' => true,
                'data' => $paginator->items(),
                'meta' => SocialPresenter::meta($paginator),
            ]);
        });
    }

    private function cardList(LengthAwarePaginator $paginator): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => SocialPresenter::userCards($paginator->getCollection()),
            'meta' => SocialPresenter::meta($paginator),
        ]);
    }

    /** Liste d'abonnés / d'abonnements : SocialUserCard + `followed_at`. */
    private function followList(LengthAwarePaginator $paginator, string $relation): JsonResponse
    {
        $users = [];
        $extra = [];
        foreach ($paginator->getCollection() as $follow) {
            /** @var UserFollow $follow */
            $user = $follow->getRelation($relation);
            if ($user instanceof User) {
                $users[] = $user;
                $extra[(int) $user->id] = ['followed_at' => SocialPresenter::date($follow->created_at)];
            }
        }

        return response()->json([
            'success' => true,
            'data' => SocialPresenter::userCards($users, $extra),
            'meta' => SocialPresenter::meta($paginator),
        ]);
    }
}
