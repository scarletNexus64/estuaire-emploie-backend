<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SocialResponses;
use App\Http\Controllers\Controller;
use App\Models\CompanyFollow;
use App\Services\Social\SocialFollowService;
use App\Services\Social\SocialPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Abonnements aux entreprises.
 */
class SocialFollowController extends Controller
{
    use SocialResponses;

    public function __construct(private readonly SocialFollowService $service)
    {
    }

    /** POST /companies/{company}/follow — bascule. */
    public function toggle(Request $request, int $company): JsonResponse
    {
        return $this->socialAction(fn () => response()->json([
            'success' => true,
            'data' => $this->service->toggle($request->user(), $company),
        ]));
    }

    /** GET /companies/{company}/followers — membres de l'entreprise uniquement. */
    public function followers(Request $request, int $company): JsonResponse
    {
        return $this->socialAction(function () use ($request, $company) {
            $paginator = $this->service->followers(
                $request->user(),
                $company,
                SocialPresenter::perPage($request->query('per_page'))
            );

            return response()->json([
                'success' => true,
                'data' => $paginator->getCollection()->map(fn (CompanyFollow $follow) => SocialPresenter::user($follow->user) + [
                    'followed_at' => SocialPresenter::date($follow->created_at),
                ])->values(),
                'meta' => SocialPresenter::meta($paginator),
            ]);
        });
    }

    /** GET /user/followed-companies — entreprises que je suis. */
    public function followed(Request $request): JsonResponse
    {
        return $this->socialAction(function () use ($request) {
            $paginator = $this->service->followedCompanies(
                $request->user(),
                SocialPresenter::perPage($request->query('per_page'))
            );

            return response()->json([
                'success' => true,
                'data' => $paginator->getCollection()->map(function (CompanyFollow $follow) {
                    $company = $follow->company;

                    return [
                        'id' => (int) $company->id,
                        'name' => (string) $company->name,
                        // URL absolue (même valeur que `logo_url` des autres payloads).
                        'logo' => $company->logo_url,
                        'logo_url' => $company->logo_url,
                        'sector' => $company->sector,
                        'city' => $company->city,
                        'is_verified' => (bool) $company->is_verified,
                        'followers_count' => (int) $company->followers_count,
                        'followed_at' => SocialPresenter::date($follow->created_at),
                    ];
                })->values(),
                'meta' => SocialPresenter::meta($paginator),
            ]);
        });
    }
}
