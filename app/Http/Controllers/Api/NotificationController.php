<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Notifications",
 *     description="API pour la gestion des notifications utilisateur"
 * )
 */
class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notifications",
     *     summary="Liste des notifications",
     *     description="Récupère la liste paginée de toutes les notifications de l'utilisateur connecté, triées par date (les plus récentes en premier)",
     *     operationId="getNotifications",
     *     tags={"Notifications"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de page",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filtrer par type de notification",
     *         required=false,
     *         @OA\Schema(type="string", enum={"application", "job", "system"})
     *     ),
     *     @OA\Parameter(
     *         name="is_read",
     *         in="query",
     *         description="Filtrer par statut lu/non lu (0=non lu, 1=lu)",
     *         required=false,
     *         @OA\Schema(type="integer", enum={0, 1})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des notifications récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=5),
     *                     @OA\Property(property="title", type="string", example="Nouvelle candidature"),
     *                     @OA\Property(property="message", type="string", example="Jean Dupont a postulé pour Développeur Full Stack"),
     *                     @OA\Property(property="type", type="string", enum={"application", "job", "system"}, example="application"),
     *                     @OA\Property(property="is_read", type="boolean", example=false),
     *                     @OA\Property(property="read_at", type="string", format="date-time", nullable=true, example=null),
     *                     @OA\Property(property="data", type="object", nullable=true, example={"job_id": 5, "application_id": 12}),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             ),
     *             @OA\Property(property="per_page", type="integer", example=50),
     *             @OA\Property(property="total", type="integer", example=123)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Auth::user()->notifications();

        // Filtrer par type si fourni
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filtrer par statut lu/non lu si fourni
        if ($request->has('is_read')) {
            if ($request->is_read == 1) {
                $query->whereNotNull('read_at'); // Lues
            } else {
                $query->whereNull('read_at'); // Non lues
            }
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(50);

        Log::info('Test not', [$notifications]);

        return response()->json($notifications);
    }

    /**
     * @OA\Get(
     *     path="/api/notifications/unread-count",
     *     summary="Nombre de notifications non lues",
     *     description="Récupère le nombre total de notifications non lues pour l'utilisateur connecté",
     *     operationId="getUnreadCount",
     *     tags={"Notifications"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Nombre de notifications non lues",
     *         @OA\JsonContent(
     *             @OA\Property(property="count", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */
    public function unreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();

        return response()->json(['count' => $count]);
    }

    /**
     * @OA\Put(
     *     path="/api/notifications/{id}/read",
     *     summary="Marquer une notification comme lue",
     *     description="Marque une notification spécifique comme lue",
     *     operationId="markAsRead",
     *     tags={"Notifications"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la notification",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification marquée comme lue",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Notification marquée comme lue"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="is_read", type="boolean", example=true),
     *                 @OA\Property(property="read_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé - Cette notification ne vous appartient pas"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notification non trouvée"
     *     )
     * )
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'message' => 'Notification marquée comme lue',
            // 'data' => $notification->fresh(),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/notifications/read-all",
     *     summary="Marquer toutes les notifications comme lues",
     *     description="Marque toutes les notifications non lues de l'utilisateur comme lues",
     *     operationId="markAllAsRead",
     *     tags={"Notifications"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Toutes les notifications marquées comme lues",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Toutes les notifications marquées comme lues"),
     *             @OA\Property(property="count", type="integer", example=5, description="Nombre de notifications marquées comme lues")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->each->markAsRead();

        return response()->json([
            'message' => 'Toutes les notifications marquées comme lues',
            // 'count' => $count,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/notifications/{id}",
     *     summary="Supprimer une notification",
     *     description="Supprime définitivement une notification",
     *     operationId="deleteNotification",
     *     tags={"Notifications"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la notification",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Notification supprimée")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé - Cette notification ne vous appartient pas"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notification non trouvée"
     *     )
     * )
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        $notification->delete();

        return response()->json([
            'message' => 'Notification supprimée',
        ]);
    }
}
