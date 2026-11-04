<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SocialResponses;
use App\Http\Controllers\Controller;
use App\Models\PostComment;
use App\Services\Social\SocialCommentService;
use App\Services\Social\SocialPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Commentaires des publications (un seul niveau de réponses).
 */
class SocialCommentController extends Controller
{
    use SocialResponses;

    public function __construct(private readonly SocialCommentService $service)
    {
    }

    /** GET /social/{type}/{id}/comments — racines, plus récentes d'abord, + replies_preview. */
    public function index(Request $request, string $type, int $id): JsonResponse
    {
        return $this->socialAction(function () use ($request, $type, $id) {
            $this->assertSocialType($type);

            [$paginator, $item] = $this->service->topLevel($type, $id, SocialPresenter::perPage($request->query('per_page')));

            return $this->commentPage($request, $paginator, $item);
        });
    }

    /** GET /social/comments/{comment}/replies — réponses, plus anciennes d'abord. */
    public function replies(Request $request, int $comment): JsonResponse
    {
        return $this->socialAction(function () use ($request, $comment) {
            [$paginator, $item] = $this->service->replies($comment, SocialPresenter::perPage($request->query('per_page')));

            return $this->commentPage($request, $paginator, $item);
        });
    }

    /** POST /social/{type}/{id}/comments */
    public function store(Request $request, string $type, int $id): JsonResponse
    {
        return $this->socialAction(function () use ($request, $type, $id) {
            $this->assertSocialType($type);
            $validated = $this->validateCommentBody($request->all(), true);

            [$comment, $commentsCount, $item] = $this->service->create(
                $request->user(),
                $type,
                $id,
                $validated['body'],
                isset($validated['parent_id']) ? (int) $validated['parent_id'] : null,
            );

            $memberIds = $this->service->memberIds($item);
            $viewerId = (int) $request->user()->id;

            return response()->json([
                'success' => true,
                'data' => SocialPresenter::comment($comment, $viewerId, $memberIds, $this->service->canModerate($viewerId, $memberIds)),
                'comments_count' => $commentsCount,
            ], 201);
        });
    }

    /** PUT /social/comments/{comment} — auteur uniquement. */
    public function update(Request $request, int $comment): JsonResponse
    {
        return $this->socialAction(function () use ($request, $comment) {
            $validated = $this->validateCommentBody($request->all());

            [$model, $item] = $this->service->update($request->user(), $comment, $validated['body']);

            $memberIds = $this->service->memberIds($item);
            $viewerId = (int) $request->user()->id;

            return response()->json([
                'success' => true,
                'data' => SocialPresenter::comment($model, $viewerId, $memberIds, $this->service->canModerate($viewerId, $memberIds)),
            ]);
        });
    }

    /** POST /social/comments/{comment}/like — bascule du j'aime. */
    public function toggleLike(Request $request, int $comment): JsonResponse
    {
        return $this->socialAction(fn () => response()->json([
            'success' => true,
            'data' => $this->service->toggleLike($request->user(), $comment),
        ]));
    }

    /** DELETE /social/comments/{comment} — auteur ou modérateur. */
    public function destroy(Request $request, int $comment): JsonResponse
    {
        return $this->socialAction(function () use ($request, $comment) {
            return response()->json([
                'success' => true,
                'comments_count' => $this->service->delete($request->user(), $comment),
            ]);
        });
    }

    private function commentPage(Request $request, $paginator, $item): JsonResponse
    {
        $memberIds = $this->service->memberIds($item);
        $viewerId = (int) $request->user()->id;
        $canModerate = $this->service->canModerate($viewerId, $memberIds);

        return response()->json([
            'success' => true,
            'data' => $paginator->getCollection()
                ->map(fn (PostComment $c) => SocialPresenter::comment($c, $viewerId, $memberIds, $canModerate))
                ->values(),
            'meta' => SocialPresenter::meta($paginator),
        ]);
    }
}
