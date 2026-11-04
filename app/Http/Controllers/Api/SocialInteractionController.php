<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SocialResponses;
use App\Http\Controllers\Controller;
use App\Models\PostLike;
use App\Services\Social\SocialInteractionService;
use App\Services\Social\SocialPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * J'aime et republications sur les offres (`job`) et services rapides
 * (`quick_service`).
 */
class SocialInteractionController extends Controller
{
    use SocialResponses;

    public function __construct(private readonly SocialInteractionService $service)
    {
    }

    /** POST /social/{type}/{id}/like — bascule. */
    public function toggleLike(Request $request, string $type, int $id): JsonResponse
    {
        return $this->socialAction(function () use ($request, $type, $id) {
            $this->assertSocialType($type);

            return response()->json([
                'success' => true,
                'data' => $this->service->toggleLike($request->user(), $type, $id),
            ]);
        });
    }

    /** GET /social/{type}/{id}/likes — qui a aimé, plus récent d'abord. */
    public function likes(Request $request, string $type, int $id): JsonResponse
    {
        return $this->socialAction(function () use ($request, $type, $id) {
            $this->assertSocialType($type);

            $paginator = $this->service->likers($type, $id, SocialPresenter::perPage($request->query('per_page')));

            return response()->json([
                'success' => true,
                'data' => $paginator->getCollection()->map(fn (PostLike $like) => SocialPresenter::user($like->user) + [
                    'liked_at' => SocialPresenter::date($like->created_at),
                ])->values(),
                'meta' => SocialPresenter::meta($paginator),
            ]);
        });
    }

    /** POST /social/{type}/{id}/repost — crée ou met à jour ma republication. */
    public function repost(Request $request, string $type, int $id): JsonResponse
    {
        return $this->socialAction(function () use ($request, $type, $id) {
            $this->assertSocialType($type);

            $validated = Validator::make($request->all(), [
                'comment' => ['nullable', 'string', 'max:500'],
            ], [
                'comment.string' => __('social.validation.repost_comment_max', ['max' => 500]),
                'comment.max' => __('social.validation.repost_comment_max', ['max' => 500]),
            ])->validate();

            return response()->json([
                'success' => true,
                'data' => $this->service->repost($request->user(), $type, $id, $validated['comment'] ?? null),
            ]);
        });
    }

    /** DELETE /social/{type}/{id}/repost — annule ma republication. */
    public function unrepost(Request $request, string $type, int $id): JsonResponse
    {
        return $this->socialAction(function () use ($request, $type, $id) {
            $this->assertSocialType($type);

            return response()->json([
                'success' => true,
                'data' => $this->service->unrepost($request->user(), $type, $id),
            ]);
        });
    }
}
