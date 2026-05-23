<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContractType;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="API Endpoints pour les catégories et types de contrats"
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
        $categories = Category::with('translations')->withCount('jobs')->get();

        return response()->json([
            'data' => $categories->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->t('name'),
                'slug' => $category->slug,
                'description' => $category->t('description'),
                'jobs_count' => $category->jobs_count,
            ]),
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
        $contractTypes = ContractType::with('translations')->withCount('jobs')->get();

        return response()->json([
            'data' => $contractTypes->map(fn ($type) => [
                'id' => $type->id,
                'name' => $type->t('name'),
                'slug' => $type->slug,
                'jobs_count' => $type->jobs_count,
            ]),
        ]);
    }
}
