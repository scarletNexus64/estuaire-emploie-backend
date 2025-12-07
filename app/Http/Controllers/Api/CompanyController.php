<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Companies",
 *     description="API Endpoints pour les entreprises"
 * )
 */
class CompanyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/companies",
     *     summary="Liste des entreprises vérifiées",
     *     tags={"Companies"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des entreprises",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $companies = Company::where('status', 'verified')
            ->withCount('jobs')
            ->latest()
            ->paginate(20);

        return response()->json($companies);
    }

    /**
     * @OA\Get(
     *     path="/api/companies/{id}",
     *     summary="Détails d'une entreprise",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'entreprise",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'entreprise",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="jobs", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Entreprise non trouvée")
     * )
     */
    public function show(Company $company): JsonResponse
    {
        if ($company->status !== 'verified') {
            return response()->json([
                'message' => 'Entreprise non disponible',
            ], 404);
        }

        $jobs = $company->jobs()
            ->where('status', 'published')
            ->with(['category', 'location'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $company,
            'jobs' => $jobs,
        ]);
    }
}
