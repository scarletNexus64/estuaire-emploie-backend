<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedPartnerAd;
use App\Models\FeedVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Contenus média du fil candidat : vidéos courtes « métiers » et cartes
 * sponsorisées des applications partenaires. Routes publiques.
 *
 * @OA\Tag(name="Feed Media", description="Vidéos courtes et pubs partenaires du fil")
 */
class FeedMediaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/feed/videos",
     *     summary="Vidéos courtes actives du fil (paginées)",
     *     tags={"Feed Media"},
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", default=1)),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=10, maximum=20)),
     *     @OA\Response(response=200, description="Liste paginée des vidéos")
     * )
     */
    public function videos(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 10), 1), 20);

        $videos = FeedVideo::active()->ordered()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $videos->getCollection()->map(fn (FeedVideo $v) => $v->toApiArray())->values(),
            'meta' => [
                'current_page' => $videos->currentPage(),
                'last_page' => $videos->lastPage(),
                'per_page' => $videos->perPage(),
                'total' => $videos->total(),
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/feed/videos/{id}/view",
     *     summary="Comptabilise une vue de vidéo",
     *     tags={"Feed Media"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Vue enregistrée")
     * )
     */
    /**
     * Lecture du fichier vidéo avec prise en charge des requêtes partielles
     * (BinaryFileResponse gère l'en-tête Range). Utilisé en local.
     */
    public function stream(int $id): BinaryFileResponse
    {
        $video = FeedVideo::active()->findOrFail($id);
        abort_unless($video->video_path && Storage::disk('public')->exists($video->video_path), 404);

        return response()->file(Storage::disk('public')->path($video->video_path), [
            'Content-Type' => Storage::disk('public')->mimeType($video->video_path) ?: 'video/mp4',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function recordView(int $id): JsonResponse
    {
        FeedVideo::active()->whereKey($id)->increment('views_count');

        return response()->json(['success' => true]);
    }

    /**
     * @OA\Get(
     *     path="/api/feed/partner-ads",
     *     summary="Cartes sponsorisées actives des applications partenaires",
     *     tags={"Feed Media"},
     *     @OA\Response(response=200, description="Liste des pubs partenaires")
     * )
     */
    public function partnerAds(): JsonResponse
    {
        $ads = Cache::remember(FeedPartnerAd::CACHE_KEY, now()->addMinutes(5), function () {
            return FeedPartnerAd::active()
                ->orderBy('display_order')
                ->orderBy('id')
                ->get()
                ->map(fn (FeedPartnerAd $ad) => $ad->toApiArray())
                ->values()
                ->all();
        });

        return response()->json(['success' => true, 'data' => $ads]);
    }

    /**
     * @OA\Post(
     *     path="/api/feed/partner-ads/{id}/impression",
     *     summary="Comptabilise un affichage de pub partenaire",
     *     tags={"Feed Media"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Impression enregistrée")
     * )
     */
    public function recordImpression(int $id): JsonResponse
    {
        FeedPartnerAd::whereKey($id)->increment('impressions_count');

        return response()->json(['success' => true]);
    }

    /**
     * @OA\Post(
     *     path="/api/feed/partner-ads/{id}/click",
     *     summary="Comptabilise un clic sur une pub partenaire",
     *     tags={"Feed Media"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Clic enregistré")
     * )
     */
    public function recordClick(int $id): JsonResponse
    {
        FeedPartnerAd::whereKey($id)->increment('clicks_count');

        return response()->json(['success' => true]);
    }
}
