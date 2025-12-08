<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Job;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Favorites",
 *     description="API pour la gestion des favoris (offres d'emploi sauvegardées)"
 * )
 */
class FavoriteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     summary="Liste des offres favorites",
     *     description="Récupère la liste paginée de toutes les offres d'emploi mises en favori par l'utilisateur connecté",
     *     operationId="getFavorites",
     *     tags={"Favorites"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de page",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des favoris récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Développeur Full Stack"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="salary_min", type="string", example="500000"),
     *                     @OA\Property(property="salary_max", type="string", example="800000"),
     *                     @OA\Property(property="salary_negotiable", type="boolean", example=false),
     *                     @OA\Property(property="experience_level", type="string", example="intermediaire"),
     *                     @OA\Property(property="is_featured", type="boolean", example=true),
     *                     @OA\Property(property="views_count", type="integer", example=150),
     *                     @OA\Property(property="published_at", type="string", format="date-time"),
     *                     @OA\Property(property="company", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="logo", type="string"),
     *                         @OA\Property(property="is_verified", type="boolean")
     *                     ),
     *                     @OA\Property(property="category", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     ),
     *                     @OA\Property(property="location", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     ),
     *                     @OA\Property(property="contract_type", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="per_page", type="integer", example=20),
     *             @OA\Property(property="total", type="integer", example=45)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with(['company', 'category', 'location', 'contractType'])
            ->orderBy('favorites.created_at', 'desc')
            ->paginate(20);

        return response()->json($favorites);
    }

    /**
     * @OA\Post(
     *     path="/api/jobs/{job}/favorite",
     *     summary="Ajouter/Retirer une offre des favoris",
     *     description="Toggle le statut favori d'une offre d'emploi. Si l'offre est déjà en favori, elle sera retirée. Sinon, elle sera ajoutée.",
     *     operationId="toggleFavorite",
     *     tags={"Favorites"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="job",
     *         in="path",
     *         description="ID de l'offre d'emploi",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut favori mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ajouté aux favoris"),
     *             @OA\Property(property="is_favorite", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Offre d'emploi non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Job not found")
     *         )
     *     )
     * )
     */
    public function toggle(Job $job)
    {
        $favorite = Favorite::where([
            'user_id' => auth()->id(),
            'job_id' => $job->id,
        ])->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'message' => 'Retiré des favoris',
                'is_favorite' => false,
            ]);
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'job_id' => $job->id,
        ]);

        return response()->json([
            'message' => 'Ajouté aux favoris',
            'is_favorite' => true,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/jobs/{job}/is-favorite",
     *     summary="Vérifier si une offre est en favori",
     *     description="Vérifie si une offre d'emploi spécifique est dans les favoris de l'utilisateur connecté",
     *     operationId="isFavorite",
     *     tags={"Favorites"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="job",
     *         in="path",
     *         description="ID de l'offre d'emploi",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut favori de l'offre",
     *         @OA\JsonContent(
     *             @OA\Property(property="is_favorite", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Offre d'emploi non trouvée"
     *     )
     * )
     */
    public function isFavorite(Job $job)
    {
        $isFavorite = Favorite::where([
            'user_id' => auth()->id(),
            'job_id' => $job->id,
        ])->exists();

        return response()->json([
            'is_favorite' => $isFavorite,
        ]);
    }
}
