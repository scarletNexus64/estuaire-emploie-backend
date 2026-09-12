<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     *     summary="Liste des bannières à afficher (campagnes ou repli)",
     *     description="Retourne les campagnes ciblant l'utilisateur. Si aucune n'est diffusable, retourne les bannières par défaut de la plateforme et positionne is_fallback à true.",
     *     tags={"Advertisements"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des bannières",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="is_fallback", type="boolean", example=false, description="true si ce sont les bannières par défaut"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="slug", type="string", nullable=true),
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="description", type="string", nullable=true),
     *                     @OA\Property(property="image_url", type="string", nullable=true),
     *                     @OA\Property(property="is_default", type="boolean"),
     *                     @OA\Property(
     *                         property="redirect",
     *                         type="object",
     *                         @OA\Property(property="type", type="string", enum={"none","internal_route","external_url","deeplink","whatsapp"}),
     *                         @OA\Property(property="target", type="string", nullable=true),
     *                         @OA\Property(property="params", type="object", nullable=true)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        // Ciblage : un user ne voit que les annonces destinées à son rôle ou à 'all'.
        // Un visiteur non authentifié ne voit que les annonces 'all'.
        // La route est publique : on résout l'utilisateur depuis le Bearer token s'il est fourni.
        $user = $request->user();
        if (!$user && $request->bearerToken()) {
            $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken());
            $user = $tokenModel?->tokenable;
        }
        $role = $user?->role;
        // Pays du user pour le ciblage géographique (défaut Cameroun).
        // Un visiteur non authentifié ne voit que les annonces "tous pays".
        $country = $user?->country;

        $advertisements = Advertisement::with('translations')
            ->currentlyActive()
            ->where('ad_type', 'homepage_banner')
            ->where('is_default', false)
            ->forAudience($role)
            ->forCountry($country)
            ->orderBy('display_order')
            ->orderBy('created_at', 'desc')
            ->get();

        // Aucune campagne à diffuser pour cette audience : on sert le carrousel
        // de repli maison plutôt qu'un espace vide.
        $isFallback = $advertisements->isEmpty();

        if ($isFallback) {
            $advertisements = Advertisement::with('translations')
                ->defaults()
                ->forAudience($role)
                ->get();
        }

        return response()->json([
            'success' => true,
            'is_fallback' => $isFallback,
            'data' => $advertisements->map(fn ($ad) => $this->transform($ad))->values(),
        ]);
    }

    /**
     * Payload d'une bannière tel que consommé par l'application mobile.
     * Les textes sont rendus dans la locale courante (cf. middleware SetLocale).
     */
    private function transform(Advertisement $ad): array
    {
        return [
            'id' => $ad->id,
            'slug' => $ad->slug,
            'title' => $ad->t('title'),
            'description' => $ad->t('description'),
            'image_url' => $ad->image_url, // URL complète via accessor
            'background_color' => $ad->background_color,
            'overlay_opacity' => $ad->overlay_opacity,
            'ad_type' => $ad->ad_type,
            'redirect' => $ad->redirect,
            'start_date' => $ad->start_date?->format('Y-m-d'),
            'end_date' => $ad->end_date?->format('Y-m-d'),
            'impressions_count' => $ad->impressions_count,
            'clicks_count' => $ad->clicks_count,
            'ctr' => $ad->ctr,
            'display_order' => $ad->display_order,
            'is_active' => $ad->is_active,
            'is_default' => $ad->is_default,
            'status' => $ad->status,
            'created_at' => $ad->created_at?->toISOString(),
            'updated_at' => $ad->updated_at?->toISOString(),
        ];
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
            'message' => __('advertisement.impression_recorded'),
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
     *         description="Clic enregistré, avec la destination à ouvrir",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="redirect",
     *                 type="object",
     *                 @OA\Property(property="type", type="string", enum={"none","internal_route","external_url","deeplink","whatsapp"}),
     *                 @OA\Property(property="target", type="string", nullable=true),
     *                 @OA\Property(property="params", type="object", nullable=true)
     *             )
     *         )
     *     )
     * )
     */
    public function recordClick($id): JsonResponse
    {
        $ad = Advertisement::with('translations')->findOrFail($id);
        $ad->increment('clicks_count');
        $ad->calculateCTR();

        return response()->json([
            'success' => true,
            'message' => __('advertisement.click_recorded'),
            // Destination renvoyée au client : il n'a pas à la déduire lui-même
            // ni à conserver une table de correspondance locale.
            'redirect' => $ad->redirect,
        ]);
    }
}
