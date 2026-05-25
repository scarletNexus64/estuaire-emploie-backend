<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="User Roles & Features",
 *     description="API Endpoints pour la gestion des rôles et fonctionnalités utilisateur"
 * )
 */
class UserRoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/me/roles",
     *     summary="Récupérer les rôles disponibles de l'utilisateur",
     *     description="Retourne tous les rôles que l'utilisateur peut utiliser (candidat, recruteur, etc.)",
     *     tags={"User Roles & Features"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Rôles disponibles",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_role", type="string", example="recruiter"),
     *                 @OA\Property(property="available_roles", type="array", @OA\Items(type="string"), example={"candidate", "recruiter"}),
     *                 @OA\Property(property="can_switch", type="boolean", example=true)
     *             )
     *         )
     *     )
     * )
     */
    public function getAvailableRoles(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'current_role' => $user->role,
                'available_roles' => $user->getAvailableRoles(),
                'can_switch' => count($user->getAvailableRoles()) > 1,
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/me/switch-role",
     *     summary="Changer de rôle actif",
     *     description="Permet à un utilisateur de basculer entre ses rôles disponibles (ex: candidat ↔ recruteur)",
     *     tags={"User Roles & Features"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", enum={"candidate", "recruiter"}, example="recruiter")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rôle changé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rôle changé vers recruiter avec succès"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_role", type="string", example="recruiter"),
     *                 @OA\Property(property="available_roles", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="features", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Rôle non disponible",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ce rôle n'est pas disponible pour votre compte")
     *         )
     *     )
     * )
     */
    public function switchRole(Request $request): JsonResponse
    {
        $request->validate([
            'role' => 'required|in:candidate,recruiter',
        ]);

        $user = $request->user();
        $newRole = $request->role;

        if (!$user->hasRole($newRole)) {
            return response()->json([
                'success' => false,
                'message' => "Ce rôle n'est pas disponible pour votre compte",
                'available_roles' => $user->getAvailableRoles(),
            ], 400);
        }

        $success = $user->switchToRole($newRole);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de changer de rôle',
            ], 500);
        }

        Log::info("[UserRoleController] User {$user->id} switched to role '{$newRole}'");

        return response()->json([
            'success' => true,
            'message' => "Rôle changé vers {$newRole} avec succès",
            'data' => [
                'current_role' => $user->role,
                'available_roles' => $user->getAvailableRoles(),
                'features' => $user->getFeaturesInfo($newRole),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/me/features",
     *     summary="Récupérer les fonctionnalités actives",
     *     description="Retourne toutes les features actives pour le rôle actuel de l'utilisateur",
     *     tags={"User Roles & Features"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Rôle pour lequel récupérer les features (optionnel, par défaut: rôle actuel)",
     *         @OA\Schema(type="string", enum={"candidate", "recruiter"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Features actives",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="role", type="string", example="recruiter"),
     *                 @OA\Property(property="available_roles", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="active_features", type="object",
     *                     @OA\Property(property="push_notifications", type="object",
     *                         @OA\Property(property="enabled", type="boolean", example=true),
     *                         @OA\Property(property="expires_at", type="string", nullable=true),
     *                         @OA\Property(property="uses_remaining", type="integer", nullable=true),
     *                         @OA\Property(property="source", type="string", example="subscription_plan:1")
     *                     ),
     *                     @OA\Property(property="boost_whatsapp", type="object",
     *                         @OA\Property(property="enabled", type="boolean", example=true),
     *                         @OA\Property(property="uses_remaining", type="integer", example=3)
     *                     )
     *                 ),
     *                 @OA\Property(property="features_count", type="integer", example=5)
     *             )
     *         )
     *     )
     * )
     */
    public function getFeatures(Request $request): JsonResponse
    {
        $user = $request->user();
        $role = $request->query('role', $user->role);

        if (!$user->hasRole($role)) {
            return response()->json([
                'success' => false,
                'message' => "Vous n'avez pas accès au rôle '{$role}'",
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $user->getFeaturesInfo($role),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/me/features/{featureKey}",
     *     summary="Vérifier si une feature est active",
     *     description="Vérifie si l'utilisateur a accès à une fonctionnalité spécifique",
     *     tags={"User Roles & Features"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="featureKey",
     *         in="path",
     *         required=true,
     *         description="Clé de la feature à vérifier",
     *         @OA\Schema(type="string", example="push_notifications")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut de la feature",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="feature_key", type="string", example="push_notifications"),
     *                 @OA\Property(property="has_feature", type="boolean", example=true),
     *                 @OA\Property(property="details", type="object", nullable=true)
     *             )
     *         )
     *     )
     * )
     */
    public function checkFeature(Request $request, string $featureKey): JsonResponse
    {
        $user = $request->user();
        $role = $request->query('role', $user->role);

        $hasFeature = $user->hasFeature($featureKey, $role);
        $features = $user->getFeaturesForRole($role);
        $featureDetails = $features[$featureKey] ?? null;

        return response()->json([
            'success' => true,
            'data' => [
                'feature_key' => $featureKey,
                'has_feature' => $hasFeature,
                'details' => $hasFeature ? $featureDetails : null,
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/me/sync-features",
     *     summary="Synchroniser toutes les features",
     *     description="Force la synchronisation de toutes les features depuis les plans et services actifs",
     *     tags={"User Roles & Features"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Features synchronisées",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Features synchronisées avec succès"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function syncFeatures(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->syncAllFeatures();

        return response()->json([
            'success' => true,
            'message' => 'Features synchronisées avec succès',
            'data' => [
                'recruiter_features' => $user->getFeaturesInfo('recruiter'),
                'candidate_features' => $user->getFeaturesInfo('candidate'),
            ],
        ]);
    }
}
