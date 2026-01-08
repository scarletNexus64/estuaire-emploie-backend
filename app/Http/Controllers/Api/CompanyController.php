<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Recruiter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    /**
     * @OA\Post(
     *     path="/api/companies",
     *     summary="Créer une nouvelle entreprise",
     *     description="Permet à un recruteur de créer le profil de son entreprise avec logo. L'entreprise sera en attente de vérification par l'administrateur.",
     *     operationId="createCompany",
     *     tags={"Companies"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","email","phone","description"},
     *                 @OA\Property(property="name", type="string", example="Tech Solutions SARL"),
     *                 @OA\Property(property="email", type="string", format="email", example="contact@techsolutions.cm"),
     *                 @OA\Property(property="phone", type="string", example="+237 690 123 456"),
     *                 @OA\Property(property="description", type="string", example="Entreprise spécialisée dans le développement web et mobile"),
     *                 @OA\Property(property="logo", type="string", format="binary", description="Logo de l'entreprise (PNG, JPG, JPEG - max 2MB)"),
     *                 @OA\Property(property="sector", type="string", example="Technologie & IT"),
     *                 @OA\Property(property="address", type="string", example="Bonanjo, Rue des Cocotiers"),
     *                 @OA\Property(property="city", type="string", example="Douala"),
     *                 @OA\Property(property="website", type="string", example="https://techsolutions.cm")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Entreprise créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Entreprise créée avec succès. En attente de vérification."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'required|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // Max 2MB
            'sector' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        // Vérifier si l'utilisateur n'a pas déjà une entreprise
        $existingRecruiter = Recruiter::where('user_id', auth()->id())->first();
        if ($existingRecruiter) {
            return response()->json([
                'message' => 'Vous avez déjà une entreprise associée à votre compte',
            ], 422);
        }

        // Upload du logo si fourni
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company = Company::create(array_merge($validated, [
            'status' => 'pending', // Admin doit vérifier
            'country' => 'Cameroun',
        ]));

        // Créer la relation recruiter
        Recruiter::create([
            'user_id' => auth()->id(),
            'company_id' => $company->id,
            'position' => 'Directeur',
            'can_publish' => true,
            'can_view_applications' => true,
            'can_modify_company' => true,
        ]);

        return response()->json([
            'message' => 'Entreprise créée avec succès. En attente de vérification.',
            'data' => $company,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/my-company",
     *     summary="Récupérer mon entreprise",
     *     description="Récupère les informations de l'entreprise associée au recruteur connecté",
     *     operationId="getMyCompany",
     *     tags={"Companies"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informations de l'entreprise",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string"),
     *                 @OA\Property(property="logo", type="string", nullable=true),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="sector", type="string"),
     *                 @OA\Property(property="website", type="string", nullable=true),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="city", type="string"),
     *                 @OA\Property(property="status", type="string", enum={"pending", "verified", "suspended"})
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(
     *         response=404,
     *         description="Aucune entreprise associée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'avez pas d'entreprise associée")
     *         )
     *     )
     * )
     */
    public function myCompany(): JsonResponse
    {
        $recruiter = auth()->user()->recruiter;

        if (! $recruiter) {
            return response()->json([
                'message' => 'Vous n\'avez pas d\'entreprise associée',
            ], 404);
        }

        $company = $recruiter->company;

        // Récupérer la liste des offres actives avec le compteur de candidatures
        // Filtre: uniquement les offres avec au moins 1 candidature
        $activeJobsList = $company->jobs()
            ->where('status', 'published')
            ->withCount('applications')
            ->has('applications', '>=', 1)
            ->with(['category', 'location', 'contractType'])
            ->latest()
            ->get();

        // Charger les statistiques
        $activeJobs = $activeJobsList->count();
        $totalJobs = $company->jobs()->count();
        $totalApplications = $company->jobs()->withCount('applications')->get()->sum('applications_count');
        $totalViews = $company->jobs()->sum('views_count');

        return response()->json([
            'data' => $company,
            'active_jobs' => $activeJobsList,
            'statistics' => [
                'active_jobs' => $activeJobs,
                'total_jobs' => $totalJobs,
                'total_applications' => $totalApplications,
                'total_views' => $totalViews,
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/my-company",
     *     summary="Mettre à jour mon entreprise",
     *     description="Permet au recruteur de mettre à jour les informations de son entreprise avec logo. Utiliser POST au lieu de PUT pour supporter le multipart/form-data.",
     *     operationId="updateMyCompany",
     *     tags={"Companies"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="_method", type="string", example="PUT", description="Méthode HTTP (pour Laravel)"),
     *                 @OA\Property(property="name", type="string", example="Tech Solutions SARL"),
     *                 @OA\Property(property="email", type="string", format="email", example="contact@techsolutions.cm"),
     *                 @OA\Property(property="phone", type="string", example="+237 690 123 456"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="logo", type="string", format="binary", description="Nouveau logo (PNG, JPG, JPEG - max 2MB)"),
     *                 @OA\Property(property="sector", type="string", example="Technologie & IT"),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="city", type="string", example="Douala"),
     *                 @OA\Property(property="website", type="string", example="https://techsolutions.cm")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Entreprise mise à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Entreprise mise à jour avec succès"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à modifier cette entreprise",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'êtes pas autorisé à modifier cette entreprise")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Aucune entreprise associée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function updateMyCompany(Request $request): JsonResponse
    {
        $recruiter = auth()->user()->recruiter;

        if (! $recruiter) {
            return response()->json([
                'message' => 'Vous n\'avez pas d\'entreprise associée',
            ], 404);
        }

        if (! $recruiter->can_modify_company) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à modifier cette entreprise',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'description' => 'sometimes|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // Max 2MB
            'sector' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        // Upload du nouveau logo si fourni
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo
            if ($recruiter->company->logo) {
                Storage::disk('public')->delete($recruiter->company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $recruiter->company->update($validated);

        return response()->json([
            'message' => 'Entreprise mise à jour avec succès',
            'data' => $recruiter->company->fresh(),
        ]);
    }
}
