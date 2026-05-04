<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Job;
use App\Models\QuickService;
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
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche par mots-clés (titre, description, entreprise, catégorie)",
     *         required=false,
     *         @OA\Schema(type="string")
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
    public function index(Request $request)
    {
        $query = auth()->user()
            ->favoriteJobs()
            ->with(['company', 'category', 'location', 'contractType'])
            ->orderBy('favorites.created_at', 'desc');

        // Recherche par mots-clés
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $normalizedSearch = $this->normalizeString($search);

            $query->where(function ($q) use ($normalizedSearch) {
                $q->whereRaw('LOWER(jobs.title) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                    ->orWhereRaw('LOWER(jobs.description) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                    ->orWhereRaw('LOWER(jobs.requirements) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                    ->orWhereHas('company', function ($companyQuery) use ($normalizedSearch) {
                        $companyQuery->whereRaw('LOWER(name) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"]);
                    })
                    ->orWhereHas('category', function ($categoryQuery) use ($normalizedSearch) {
                        $categoryQuery->whereRaw('LOWER(name) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"]);
                    });
            });
        }

        $favorites = $query->paginate(20);

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
            'favoriteable_type' => Job::class,
            'favoriteable_id' => $job->id,
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
            'favoriteable_type' => Job::class,
            'favoriteable_id' => $job->id,
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
            'favoriteable_type' => Job::class,
            'favoriteable_id' => $job->id,
        ])->exists();

        return response()->json([
            'is_favorite' => $isFavorite,
        ]);
    }

    // ==================== QUICK SERVICES FAVORITES ====================

    /**
     * @OA\Get(
     *     path="/api/quick-services/favorites",
     *     summary="Liste des services rapides favoris",
     *     description="Récupère la liste paginée de tous les services rapides mis en favori par l'utilisateur connecté",
     *     operationId="getFavoriteQuickServices",
     *     tags={"Favorites"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de page",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche par mots-clés (titre, description, catégorie)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des services favoris récupérée avec succès"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */
    public function getFavoriteQuickServices(Request $request)
    {
        $query = Favorite::where('user_id', auth()->id())
            ->where('favoriteable_type', QuickService::class)
            ->with(['favoriteable.user', 'favoriteable.category'])
            ->orderBy('created_at', 'desc');

        // Recherche par mots-clés
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $normalizedSearch = $this->normalizeString($search);

            $query->whereHas('favoriteable', function ($serviceQuery) use ($normalizedSearch) {
                $serviceQuery->where(function ($q) use ($normalizedSearch) {
                    // Recherche dans le titre du service
                    $q->whereRaw('LOWER(quick_services.title) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                        // Recherche dans la description du service
                        ->orWhereRaw('LOWER(quick_services.description) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                        // Recherche dans la catégorie du service
                        ->orWhereHas('category', function ($categoryQuery) use ($normalizedSearch) {
                            $categoryQuery->whereRaw('LOWER(name) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"]);
                        });
                });
            });
        }

        $favorites = $query->paginate(20);

        // Transformer les favoris pour retourner directement les services
        $services = $favorites->map(function ($favorite) {
            return $favorite->favoriteable;
        });

        return response()->json([
            'current_page' => $favorites->currentPage(),
            'data' => $services,
            'per_page' => $favorites->perPage(),
            'total' => $favorites->total(),
            'last_page' => $favorites->lastPage(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/quick-services/{service}/favorite",
     *     summary="Ajouter/Retirer un service rapide des favoris",
     *     description="Toggle le statut favori d'un service rapide. Si le service est déjà en favori, il sera retiré. Sinon, il sera ajouté.",
     *     operationId="toggleQuickServiceFavorite",
     *     tags={"Favorites"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="service",
     *         in="path",
     *         description="ID du service rapide",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut favori mis à jour avec succès"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service rapide non trouvé"
     *     )
     * )
     */
    public function toggleQuickServiceFavorite(QuickService $service)
    {
        $favorite = Favorite::where([
            'user_id' => auth()->id(),
            'favoriteable_type' => QuickService::class,
            'favoriteable_id' => $service->id,
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
            'favoriteable_type' => QuickService::class,
            'favoriteable_id' => $service->id,
        ]);

        return response()->json([
            'message' => 'Ajouté aux favoris',
            'is_favorite' => true,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/quick-services/{service}/is-favorite",
     *     summary="Vérifier si un service rapide est en favori",
     *     description="Vérifie si un service rapide spécifique est dans les favoris de l'utilisateur connecté",
     *     operationId="isQuickServiceFavorite",
     *     tags={"Favorites"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="service",
     *         in="path",
     *         description="ID du service rapide",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut favori du service"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service rapide non trouvé"
     *     )
     * )
     */
    public function isQuickServiceFavorite(QuickService $service)
    {
        $isFavorite = Favorite::where([
            'user_id' => auth()->id(),
            'favoriteable_type' => QuickService::class,
            'favoriteable_id' => $service->id,
        ])->exists();

        return response()->json([
            'is_favorite' => $isFavorite,
        ]);
    }

    /**
     * Normalise une chaîne pour la recherche (retire les accents, convertit en minuscules)
     */
    private function normalizeString(string $str): string
    {
        // Convertir en minuscules
        $str = mb_strtolower($str, 'UTF-8');

        // Tableau de correspondance des caractères accentués (majuscules et minuscules)
        $unwanted = [
            // Minuscules
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'œ' => 'oe',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            'ý' => 'y', 'ÿ' => 'y',
            'ñ' => 'n', 'ç' => 'c',
            // Majuscules (au cas où)
            'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ã' => 'a', 'Ä' => 'a', 'Å' => 'a', 'Æ' => 'ae',
            'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e',
            'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i',
            'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'o', 'Ø' => 'o', 'Œ' => 'oe',
            'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u',
            'Ý' => 'y', 'Ÿ' => 'y',
            'Ñ' => 'n', 'Ç' => 'c',
        ];

        $str = strtr($str, $unwanted);

        // Utiliser iconv pour retirer les accents restants
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);

        // Nettoyer les caractères non alphanumériques sauf espaces
        $str = preg_replace('/[^a-z0-9\s]/i', '', $str);

        return $str;
    }
}
