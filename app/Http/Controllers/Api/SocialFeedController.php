<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SocialResponses;
use App\Http\Controllers\Controller;
use App\Services\Social\SocialFeedService;
use App\Services\Social\SocialPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Fil d'accueil social (republications + offres des entreprises suivies).
 */
class SocialFeedController extends Controller
{
    use SocialResponses;

    public function __construct(private readonly SocialFeedService $service)
    {
    }

    /** GET /social/feed?page=&per_page=&candidate_city= */
    public function index(Request $request): JsonResponse
    {
        return $this->socialAction(function () use ($request) {
            $city = $request->query('candidate_city');

            $paginator = $this->service->feed(
                $request->user(),
                max(1, (int) $request->query('page', 1)),
                SocialPresenter::perPage($request->query('per_page')),
                is_string($city) ? $city : null,
            );

            return response()->json([
                'success' => true,
                'data' => $paginator->items(),
                'meta' => SocialPresenter::meta($paginator),
            ]);
        });
    }
}
