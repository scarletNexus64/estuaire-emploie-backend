<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContractType;
use App\Models\Location;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="API Endpoints pour les catégories, localisations et types de contrats"
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Liste des catégories de métiers",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des catégories",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function categories(): JsonResponse
    {
        $categories = Category::withCount('jobs')->get();

        return response()->json([
            'data' => $categories,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/locations",
     *     summary="Liste des localisations",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des localisations",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function locations(): JsonResponse
    {
        $locations = Location::withCount('jobs')->get();

        return response()->json([
            'data' => $locations,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/contract-types",
     *     summary="Liste des types de contrats",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des types de contrats",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function contractTypes(): JsonResponse
    {
        $contractTypes = ContractType::withCount('jobs')->get();

        return response()->json([
            'data' => $contractTypes,
        ]);
    }
}
