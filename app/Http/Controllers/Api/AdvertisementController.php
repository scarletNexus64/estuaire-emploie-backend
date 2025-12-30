<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Advertisements",
 *     description="API Endpoints pour les publicités"
 * )
 */
class AdvertisementController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/advertisements",
     *     summary="Liste des publicités actives",
     *     tags={"Advertisements"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des publicités actives",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $advertisements = Advertisement::where('is_active', true)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('ad_type', 'homepage_banner')
            ->orderBy('display_order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($ad) {
                return [
                    'id' => $ad->id,
                    'title' => $ad->title,
                    'description' => $ad->description,
                    'image_url' => $ad->image_url, // URL complète via accessor
                    'background_color' => $ad->background_color,
                    'ad_type' => $ad->ad_type,
                    'start_date' => $ad->start_date->format('Y-m-d'),
                    'end_date' => $ad->end_date->format('Y-m-d'),
                    'impressions_count' => $ad->impressions_count,
                    'clicks_count' => $ad->clicks_count,
                    'ctr' => $ad->ctr,
                    'display_order' => $ad->display_order,
                    'is_active' => $ad->is_active,
                    'status' => $ad->status,
                    'created_at' => $ad->created_at->toISOString(),
                    'updated_at' => $ad->updated_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $advertisements,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/advertisements/{id}/impression",
     *     summary="Enregistrer une impression",
     *     tags={"Advertisements"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Impression enregistrée"
     *     )
     * )
     */
    public function recordImpression($id): JsonResponse
    {
        $ad = Advertisement::findOrFail($id);
        $ad->increment('impressions_count');
        $ad->calculateCTR();

        return response()->json([
            'success' => true,
            'message' => 'Impression enregistrée',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/advertisements/{id}/click",
     *     summary="Enregistrer un clic",
     *     tags={"Advertisements"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Clic enregistré"
     *     )
     * )
     */
    public function recordClick($id): JsonResponse
    {
        $ad = Advertisement::findOrFail($id);
        $ad->increment('clicks_count');
        $ad->calculateCTR();

        return response()->json([
            'success' => true,
            'message' => 'Clic enregistré',
        ]);
    }
}
