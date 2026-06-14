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
     *     path="/api/domains-sectors",
     *     summary="Liste des domaines et secteurs d'activité",
     *     tags={"Companies"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des domaines avec leurs secteurs",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getDomainsSectors(): JsonResponse
    {
        $domains = \App\Models\Domain::active()
            ->ordered()
            ->with(['sectors' => function ($q) {
                $q->active()->ordered()->with('translations');
            }, 'translations'])
            ->get();

        if ($domains->isEmpty()) {
            // Fallback to legacy config if the table hasn't been seeded yet.
            return response()->json([
                'data' => config('domains_sectors', []),
            ]);
        }

        $payload = [];
        foreach ($domains as $domain) {
            $domainName = $domain->t('name');
            $payload[$domainName] = $domain->sectors->map(fn ($sector) => $sector->t('name'))->values()->all();
        }

        return response()->json([
            'data' => $payload,
        ]);
    }

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
    public function index(Request $request): JsonResponse
    {
        $query = Company::where('status', 'verified')
            ->with('categories')
            ->withCount('jobs');

        // Filtre par domaine d'activité niveau 1 (CompanyCategory.level_1)
        if ($request->filled('level_1')) {
            $level1 = $request->input('level_1');
            $query->whereHas('categories', function ($q) use ($level1) {
                $q->where('level_1', $level1);
            });
        }

        $companies = $query->latest()->paginate(20);

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
                'message' => __('company.company_unavailable'),
            ], 404);
        }

        $jobs = $company->jobs()
            ->where('status', 'published')
            ->with(['category'])
            ->latest()
            ->take(20)
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
        try {
            // 🎁 Quota gratuit : un user peut créer 1 seule entreprise sans
            // abonnement recruteur actif. Au-delà, abonnement requis.
            $existingCompaniesCount = Recruiter::where('user_id', auth()->id())->count();
            if ($existingCompaniesCount >= 1) {
                $authUser = auth()->user();
                $activeSub = $authUser?->activeSubscription('recruiter');
                if (!$activeSub || $activeSub->isExpired()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Un abonnement recruteur actif est requis pour créer plusieurs entreprises.',
                        'error_code' => 'COMPANY_LIMIT_REACHED',
                        'limit' => 1,
                        'used' => $existingCompaniesCount,
                        'upgrade_required' => true,
                        'redirect_to' => '/subscription-plans',
                    ], 403);
                }
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:companies,email',
                'phone' => 'required|string|max:20',
                'description' => 'required|string|min:30',
                'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // Max 2MB
                'photos' => 'nullable|array|max:4', // 0 à 4 photos (optionnel)
                'photos.*' => 'required|image|mimes:png,jpg,jpeg|max:2048', // Chaque photo max 2MB
                'domain' => 'nullable|string|max:255', // Optional now (for backward compatibility)
                'sector' => 'nullable|string|max:255',
                'category_ids' => 'required|array|min:1', // Required: at least 1 category
                'category_ids.*' => 'exists:company_categories,id', // Validate each ID exists
                // Pas de max ici : l'adresse vient du reverse geocoding
                // (peut être longue). Tronquée proprement plus bas pour
                // ne jamais rejeter une création à cause de sa longueur.
                'address' => 'nullable|string',
                'city' => 'nullable|string',
                'website' => 'nullable|url|max:255',
                'latitude' => 'required|numeric|between:-90,90', // Required now
                'longitude' => 'required|numeric|between:-180,180', // Required now
            ]);

            // Tronquer address/city à la taille de colonne (VARCHAR 255).
            if (isset($validated['address'])) {
                $validated['address'] = mb_substr($validated['address'], 0, 255);
            }
            if (isset($validated['city'])) {
                $validated['city'] = mb_substr($validated['city'], 0, 255);
            }

            // Multi-entreprises : un user peut créer plusieurs entreprises.
            // On garde simplement le compte existant pour décider si celle-ci
            // doit devenir l'entreprise active par défaut (1ère création).
            $isFirstCompany = Recruiter::where('user_id', auth()->id())->doesntExist();

            // Normaliser l'email en minuscules
            $validated['email'] = strtolower($validated['email']);

            // Upload du logo si fourni
            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo')->store('logos', 'public');
            }

            // Upload des photos (0 à 4 photos, optionnel)
            $photoPaths = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPaths[] = $photo->store('company_photos', 'public');
                }
                $validated['photos'] = $photoPaths;
            }

            // Set default values for backward compatibility
            if (empty($validated['domain'])) {
                $validated['domain'] = 'Général';
            }
            if (empty($validated['sector'])) {
                $validated['sector'] = 'Divers';
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

            // Attach categories if provided
            if (!empty($validated['category_ids'])) {
                $company->categories()->attach($validated['category_ids']);
                \Log::info("[CompanyController] Attached categories to company {$company->id}: " . implode(', ', $validated['category_ids']));
            }

            // 🎯 Changer automatiquement le rôle de l'utilisateur à "recruiter"
            // et définir l'entreprise active si c'est la première créée.
            $user = auth()->user();
            $user->role = 'recruiter';
            if ($isFirstCompany) {
                $user->current_company_id = $company->id;
            }
            $user->save();

            \Log::info("[CompanyController] User {$user->id} role changed to 'recruiter' after company creation (first_company={$isFirstCompany}, current_company_id={$user->current_company_id})");

            // Load categories relationship for response
            $company->load('categories');

            return response()->json([
                'message' => __('company.created'),
                'data' => $company,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => __('common.validation_error'),
                'errors' => $e->errors(),
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            // Gérer spécifiquement les erreurs de duplication
            if ($e->getCode() === '23000') {
                \Log::warning('Tentative de création d\'entreprise avec email existant', [
                    'user_id' => auth()->id(),
                    'email' => $request->email,
                ]);

                return response()->json([
                    'message' => __('company.email_already_used'),
                    'errors' => [
                        'email' => ['Cet email est déjà utilisé par une autre entreprise']
                    ]
                ], 422);
            }

            throw $e;

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création d\'entreprise', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => __('company.create_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
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
        $user = auth()->user();
        $company = $user->currentCompany;

        // Fallback : si current_company_id n'est pas défini mais l'user a
        // au moins une entreprise, on prend la première et on la persiste.
        if (! $company) {
            $firstRecruiter = $user->recruiters()->with('company')->first();
            if ($firstRecruiter && $firstRecruiter->company) {
                $company = $firstRecruiter->company;
                $user->current_company_id = $company->id;
                $user->save();
            }
        }

        if (! $company) {
            return response()->json([
                'message' => __('company.no_company_associated'),
            ], 404);
        }

        // Charger les catégories pour que le front puisse pré-remplir
        // le sélecteur de catégories à l'édition de l'entreprise.
        $company->load('categories');

        // Récupérer la liste des offres actives avec le compteur de candidatures
        // Filtre: uniquement les offres avec au moins 1 candidature
        $activeJobsList = $company->jobs()
            ->where('status', 'published')
            ->withCount('applications')
            ->has('applications', '>=', 1)
            ->with(['category', 'contractType'])
            ->latest()
            ->get();

        // Charger les statistiques
        $activeJobs = $activeJobsList->count();
        $totalJobs = $company->jobs()->count();
        $totalApplications = $company->jobs()->withCount('applications')->get()->sum('applications_count');
        $totalViews = $company->jobs()->sum('views_count');

        // Statistiques des candidatures par statut
        $companyJobIds = $company->jobs()->pluck('id');
        $acceptedApplications = \App\Models\Application::whereIn('job_id', $companyJobIds)
            ->where('status', 'accepted')
            ->count();
        $rejectedApplications = \App\Models\Application::whereIn('job_id', $companyJobIds)
            ->where('status', 'rejected')
            ->count();
        $newApplications = \App\Models\Application::whereIn('job_id', $companyJobIds)
            ->where('status', 'pending')
            ->whereNull('viewed_at')
            ->count();

        return response()->json([
            'data' => $company,
            'active_jobs' => $activeJobsList,
            'statistics' => [
                'active_jobs' => $activeJobs,
                'total_jobs' => $totalJobs,
                'total_applications' => $totalApplications,
                'total_views' => $totalViews,
                'accepted_applications' => $acceptedApplications,
                'rejected_applications' => $rejectedApplications,
                'new_applications' => $newApplications,
            ],
        ]);
    }

    /**
     * Récupérer les secteurs niveau 3 disponibles pour mon entreprise.
     *
     * L'entreprise stocke ses catégories au niveau 2 (table pivot). On
     * retourne toutes les company_categories ayant un level_3 non nul dont
     * le level_2 fait partie des niveaux 2 de l'entreprise. Le front s'en
     * sert pour proposer le secteur niveau 3 d'un produit/service.
     *
     * GET /api/my-company/level3-sectors
     */
    public function myCompanyLevel3Sectors(): JsonResponse
    {
        $company = auth()->user()->currentCompany;

        if (! $company) {
            return response()->json([
                'success' => false,
                'message' => __('company.no_company_associated'),
            ], 404);
        }
        // load() doit inclure les translations pour que ->t('level_3') marche
        // sans N+1 (en complément du hook retrieved auto-localizer).
        $company->load(['categories.translations']);

        // Niveaux 2 (canoniques) des catégories de l'entreprise. On garde la
        // colonne `level_2` brute pour faire le whereIn DB — comparer sur le
        // canonique évite que la traduction casse le filtrage.
        $companyLevel2 = $company->categories
            ->map(fn ($cat) => $cat->getRawOriginal('level_2'))
            ->filter()
            ->unique()
            ->values();

        // Fallback : si l'entreprise n'a aucune catégorie level_2 enregistrée,
        // on renvoie l'ensemble des catégories level_3 actives (au lieu d'une
        // liste vide). Le front peut ainsi laisser le user choisir librement
        // pendant l'onboarding ou si la company n'a pas finalisé ses
        // catégories.
        $query = \App\Models\CompanyCategory::active()
            ->with('translations')
            ->whereNotNull('level_3');

        if ($companyLevel2->isNotEmpty()) {
            $query->whereIn('level_2', $companyLevel2);
        }

        $level3 = $query
            ->orderBy('level_2')
            ->orderBy('level_3')
            ->get(['id', 'code', 'level_1', 'level_2', 'level_3'])
            ->map(function ($category) {
                // ->t() : utilise la locale courante (middleware SetLocale)
                // avec fallback automatique vers fr / colonne canonique.
                return [
                    'id' => $category->id,
                    'code' => $category->code,
                    'level_1' => $category->t('level_1'),
                    'level_2' => $category->t('level_2'),
                    'level_3' => $category->t('level_3'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $level3,
            'filtered_by_company' => $companyLevel2->isNotEmpty(),
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
        try {
            $user = auth()->user();
            $company = $user->currentCompany;

            if (! $company) {
                return response()->json([
                    'message' => __('company.no_company_associated'),
                ], 404);
            }

            $recruiter = $user->recruiterFor($company->id);

            if (! $recruiter || ! $recruiter->can_modify_company) {
                return response()->json([
                    'message' => __('company.not_authorized_modify'),
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|max:255|unique:companies,email,'.$company->id,
                'phone' => 'sometimes|string|max:20',
                'description' => 'sometimes|string',
                'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // Max 2MB
                'photos' => 'sometimes|array|max:4', // Nouvelles photos (0 à 4)
                'photos.*' => 'required|image|mimes:png,jpg,jpeg|max:2048', // Chaque photo max 2MB
                'keep_photos' => 'sometimes|array|max:4', // URLs des photos existantes à conserver
                'keep_photos.*' => 'string', // Chaque URL est une string
                'clear_photos' => 'sometimes|in:true,false,1,0', // Accepter string ou int
                'domain' => 'sometimes|string|max:255',
                'sector' => 'nullable|string|max:255',
                'category_ids' => 'nullable|array', // New: Multiple category IDs
                'category_ids.*' => 'exists:company_categories,id', // Validate each ID exists
                // address/city : pas de max ici. La valeur vient du
                // reverse geocoding (peut être longue). On tronque
                // proprement à 255 plus bas pour ne JAMAIS rejeter une
                // mise à jour à cause d'une adresse géocodée longue.
                'address' => 'nullable|string',
                'city' => 'nullable|string',
                'website' => 'nullable|url|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ]);

            // Tronquer address/city à la taille de colonne (VARCHAR 255)
            // pour éviter une erreur SQL "Data too long".
            if (isset($validated['address'])) {
                $validated['address'] = mb_substr($validated['address'], 0, 255);
            }
            if (isset($validated['city'])) {
                $validated['city'] = mb_substr($validated['city'], 0, 255);
            }

            // Normaliser l'email en minuscules si présent
            if (isset($validated['email'])) {
                $validated['email'] = strtolower($validated['email']);
            }

            // Upload du nouveau logo si fourni
            if ($request->hasFile('logo')) {
                // Supprimer l'ancien logo
                if ($company->logo) {
                    Storage::disk('public')->delete($company->logo);
                }
                $validated['logo'] = $request->file('logo')->store('logos', 'public');
            }

            // Gestion des photos
            // 1. Si clear_photos = true, supprimer toutes les photos
            // Accepter 'true' (string), true (booléen), 1, '1'
            $clearPhotos = $request->input('clear_photos');
            if ($request->has('clear_photos') && ($clearPhotos === true || $clearPhotos === 'true' || $clearPhotos === 1 || $clearPhotos === '1')) {
                // Supprimer les anciennes photos du stockage
                if ($company->photos && is_array($company->photos)) {
                    foreach ($company->photos as $oldPhoto) {
                        Storage::disk('public')->delete($oldPhoto);
                    }
                }
                $validated['photos'] = []; // Vider le tableau de photos
            }
            // 2. Si keep_photos est fourni (avec ou sans nouvelles photos)
            elseif ($request->has('keep_photos')) {
                $keepPhotosUrls = $request->input('keep_photos', []);
                $currentPhotos = $company->photos ?? [];

                // Convertir les URLs complètes en chemins de stockage pour comparaison
                $keepPhotosPaths = [];
                foreach ($keepPhotosUrls as $url) {
                    // Extraire le chemin de stockage depuis l'URL
                    // Format URL: http://domain/storage/company_photos/xxx.jpg
                    // On veut: company_photos/xxx.jpg
                    if (strpos($url, '/storage/') !== false) {
                        $path = substr($url, strpos($url, '/storage/') + 9);
                        $keepPhotosPaths[] = $path;
                    } else {
                        // Si pas d'URL mais directement un chemin (fallback)
                        $keepPhotosPaths[] = $url;
                    }
                }

                // Supprimer les photos qui ne sont pas dans keep_photos
                $photosToKeep = [];
                foreach ($currentPhotos as $currentPhoto) {
                    if (in_array($currentPhoto, $keepPhotosPaths)) {
                        $photosToKeep[] = $currentPhoto;
                    } else {
                        // Supprimer la photo du stockage
                        Storage::disk('public')->delete($currentPhoto);
                    }
                }

                // Ajouter les nouvelles photos si présentes
                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $photo) {
                        $photosToKeep[] = $photo->store('company_photos', 'public');
                    }
                }

                // Valider que le total est entre 2 et 4 (ou 0 si on veut permettre de tout supprimer)
                $totalPhotos = count($photosToKeep);
                if ($totalPhotos > 0 && ($totalPhotos < 2 || $totalPhotos > 4)) {
                    return response()->json([
                        'message' => __('company.photos_count_invalid'),
                        'errors' => ['photos' => ['Vous devez avoir entre 2 et 4 photos']],
                    ], 422);
                }

                $validated['photos'] = $photosToKeep;
            }
            // 3. Si seulement de nouvelles photos sont fournies (remplacement complet)
            elseif ($request->hasFile('photos')) {
                // Supprimer les anciennes photos
                if ($company->photos && is_array($company->photos)) {
                    foreach ($company->photos as $oldPhoto) {
                        Storage::disk('public')->delete($oldPhoto);
                    }
                }

                // Uploader les nouvelles photos
                $photoPaths = [];
                foreach ($request->file('photos') as $photo) {
                    $photoPaths[] = $photo->store('company_photos', 'public');
                }
                $validated['photos'] = $photoPaths;
            }
            // 4. Sinon, garder les photos actuelles (ne rien faire)

            $company->update($validated);

            // Sync categories if provided
            if ($request->has('category_ids')) {
                $company->categories()->sync($validated['category_ids'] ?? []);
                \Log::info("[CompanyController] Synced categories for company {$company->id}: " . implode(', ', $validated['category_ids'] ?? []));
            }

            // Load categories relationship for response
            $company->load('categories');

            return response()->json([
                'message' => __('company.updated'),
                'data' => $company->fresh(['categories']),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => __('common.validation_error'),
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour d\'entreprise', [
                'user_id' => auth()->id(),
                'company_id' => $company->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => __('company.update_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Liste toutes les entreprises de l'utilisateur authentifié.
     * Inclut les permissions du pivot recruiters pour chaque entreprise.
     *
     * GET /api/my-companies
     */
    public function myCompanies(): JsonResponse
    {
        $user = auth()->user();

        $companies = $user->companies()
            ->with('categories')
            ->withCount('jobs')
            ->get()
            ->map(function (Company $company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'email' => $company->email,
                    'phone' => $company->phone,
                    'logo' => $company->logo,
                    'photos' => $company->photos,
                    'description' => $company->description,
                    'sector' => $company->sector,
                    'domain' => $company->domain,
                    'website' => $company->website,
                    'address' => $company->address,
                    'city' => $company->city,
                    'country' => $company->country,
                    'status' => $company->status,
                    'subscription_plan' => $company->subscription_plan,
                    'jobs_count' => $company->jobs_count,
                    'categories' => $company->categories,
                    'permissions' => [
                        'position' => $company->pivot->position,
                        'can_publish' => (bool) $company->pivot->can_publish,
                        'can_view_applications' => (bool) $company->pivot->can_view_applications,
                        'can_modify_company' => (bool) $company->pivot->can_modify_company,
                    ],
                ];
            });

        return response()->json([
            'data' => $companies,
            'current_company_id' => $user->current_company_id,
        ]);
    }

    /**
     * Bascule l'entreprise active de l'utilisateur authentifié.
     * Valide que l'user est bien recruiter dans cette entreprise.
     *
     * POST /api/companies/{company}/switch
     */
    public function switchCompany(Company $company): JsonResponse
    {
        $user = auth()->user();
        $recruiter = $user->recruiterFor($company->id);

        if (! $recruiter) {
            return response()->json([
                'message' => __('company.not_a_member'),
            ], 403);
        }

        $user->current_company_id = $company->id;
        $user->save();

        $company->load('categories');

        return response()->json([
            'message' => __('company.active_company_updated'),
            'current_company_id' => $company->id,
            'company' => $company,
            'permissions' => [
                'position' => $recruiter->position,
                'can_publish' => (bool) $recruiter->can_publish,
                'can_view_applications' => (bool) $recruiter->can_view_applications,
                'can_modify_company' => (bool) $recruiter->can_modify_company,
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/companies/nearby",
     *     summary="Récupérer les entreprises à proximité",
     *     description="Récupère toutes les entreprises (vérifiées et non vérifiées) dans un rayon donné autour des coordonnées GPS fournies. Les entreprises vérifiées ont status='verified'.",
     *     operationId="getNearbyCompanies",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         description="Latitude de la position actuelle",
     *         required=true,
     *         @OA\Schema(type="number", format="float", example=4.0511)
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         description="Longitude de la position actuelle",
     *         required=true,
     *         @OA\Schema(type="number", format="float", example=9.7679)
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         description="Rayon de recherche en kilomètres (par défaut: 50km)",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=50)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des entreprises à proximité avec leur distance",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string"),
     *                 @OA\Property(property="logo_url", type="string", nullable=true),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="sector", type="string"),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="city", type="string"),
     *                 @OA\Property(property="latitude", type="number"),
     *                 @OA\Property(property="longitude", type="number"),
     *                 @OA\Property(property="status", type="string", enum={"pending", "verified", "suspended"}),
     *                 @OA\Property(property="is_verified", type="boolean", description="true si status='verified'"),
     *                 @OA\Property(property="distance", type="number", format="float", description="Distance en km"),
     *                 @OA\Property(property="jobs_count", type="integer")
     *             )),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="radius_km", type="number"),
     *                 @OA\Property(property="center", type="object",
     *                     @OA\Property(property="latitude", type="number"),
     *                     @OA\Property(property="longitude", type="number")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    /**
     * Recherche d'entreprises (autocomplete annuaire).
     *
     * Recherche un mot-clé `q` sur l'ensemble des champs pertinents :
     *  - Entreprise : name, description, domain, sector, city, address
     *  - Catégories liées : level_1, level_2, level_3, description
     *
     * Si latitude/longitude sont fournis, la distance (km) est calculée et les
     * résultats sont triés du plus proche au plus loin. Sinon, on priorise les
     * correspondances sur le nom, puis l'ordre alphabétique.
     *
     * Seules les entreprises `verified` sont renvoyées.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'q' => 'required|string|min:2|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'limit' => 'nullable|integer|min:1|max:50',
            ]);

            $term = trim($validated['q']);
            $limit = $validated['limit'] ?? 25;
            $lat = $validated['latitude'] ?? null;
            $lng = $validated['longitude'] ?? null;
            $hasGeo = $lat !== null && $lng !== null;

            // Échapper les jokers LIKE pour éviter les faux positifs (% / _).
            $escaped = addcslashes($term, '%_\\');
            $like = '%' . $escaped . '%';

            $query = Company::query()
                ->where('status', 'verified')
                ->with('categories')
                ->withCount('jobs');

            // Correspondance sur les champs de l'entreprise OU de ses catégories.
            $query->where(function ($outer) use ($like) {
                $companyFields = ['name', 'description', 'domain', 'sector', 'city', 'address'];
                foreach ($companyFields as $field) {
                    $outer->orWhere($field, 'LIKE', $like);
                }

                // Niveaux 1/2/3 + description de catégorie via la relation pivot.
                $outer->orWhereHas('categories', function ($q) use ($like) {
                    $q->where('level_1', 'LIKE', $like)
                        ->orWhere('level_2', 'LIKE', $like)
                        ->orWhere('level_3', 'LIKE', $like)
                        ->orWhere('description', 'LIKE', $like);
                });
            });

            // Distance : calculée en SQL si position fournie (entreprises géolocalisées).
            if ($hasGeo) {
                $query->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->selectRaw(
                        'companies.*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                        [$lat, $lng, $lat]
                    )
                    ->orderBy('distance');
            } else {
                // Sans position : priorité aux entreprises dont le nom matche, puis alpha.
                $query->orderByRaw('CASE WHEN name LIKE ? THEN 0 ELSE 1 END', [$like])
                    ->orderBy('name');
            }

            $companies = $query->limit($limit)->get();

            return response()->json([
                'data' => $companies,
                'meta' => [
                    'total' => $companies->count(),
                    'query' => $term,
                    'sorted_by' => $hasGeo ? 'distance' : 'relevance',
                ],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => __('company.invalid_params'),
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la recherche d\'entreprises', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => __('company.fetch_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getNearbyCompanies(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius' => 'nullable|numeric|min:1|max:500', // Max 500km
                'level_1' => 'nullable|string|max:255',
            ]);

            $latitude = $validated['latitude'];
            $longitude = $validated['longitude'];
            $radius = $validated['radius'] ?? 50; // Par défaut 50km

            // Utiliser le scope nearby du modèle Company
            $query = Company::nearby($latitude, $longitude, $radius)
                ->with('categories')
                ->withCount('jobs');

            // Filtre par domaine d'activité niveau 1 (CompanyCategory.level_1)
            if (! empty($validated['level_1'])) {
                $level1 = $validated['level_1'];
                $query->whereHas('categories', function ($q) use ($level1) {
                    $q->where('level_1', $level1);
                });
            }

            $companies = $query->get();

            return response()->json([
                'data' => $companies,
                'meta' => [
                    'total' => $companies->count(),
                    'radius_km' => $radius,
                    'center' => [
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ],
                ],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => __('company.invalid_params'),
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des entreprises à proximité', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => __('company.fetch_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
