<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuickService;
use App\Models\ServiceCategory;
use App\Models\ServiceResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class QuickServiceController extends Controller
{
    /**
     * Décore un service (ou une collection) avec les prix d'affichage dans la
     * devise du user : `display_currency`, `display_price_min(_formatted)`,
     * `display_price_max(_formatted)`. Les `price_min`/`price_max` bruts (XAF)
     * restent inchangés — source de vérité.
     *
     * @param  \Illuminate\Support\Collection|\App\Models\QuickService  $services
     */
    private function decoratePrices($services)
    {
        $currency = app(\App\Services\CurrencyService::class);
        $target = $currency->resolveCurrency(auth('sanctum')->user());

        $decorate = function ($service) use ($currency, $target) {
            $service->base_currency = \App\Services\CurrencyService::BASE_CURRENCY;
            $service->display_currency = $target;

            if ($service->price_min !== null) {
                $min = $currency->displayFor((float) $service->price_min, $target);
                $service->display_price_min = $min['display_price'];
                $service->display_price_min_formatted = $min['display_price_formatted'];
            }
            if ($service->price_max !== null) {
                $max = $currency->displayFor((float) $service->price_max, $target);
                $service->display_price_max = $max['display_price'];
                $service->display_price_max_formatted = $max['display_price_formatted'];
            }

            return $service;
        };

        if ($services instanceof \Illuminate\Support\Collection
            || $services instanceof \Illuminate\Database\Eloquent\Collection) {
            return $services->map($decorate);
        }

        return $decorate($services);
    }

    /**
     * Liste des services rapides avec filtres
     */
    public function index(Request $request): JsonResponse
    {
        $query = QuickService::with(['user', 'category', 'responses'])
            ->active()
            ->approved() // Seulement les services approuvés par admin
            ->latest();

        // Recherche textuelle
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $normalizedSearch = $this->normalizeString($search);

            $query->where(function ($q) use ($normalizedSearch) {
                // Recherche dans le titre
                $q->whereRaw('LOWER(title) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                    // Recherche dans la description
                    ->orWhereRaw('LOWER(description) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                    // Recherche dans le nom du lieu
                    ->orWhereRaw('LOWER(location_name) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                    // Recherche dans la catégorie
                    ->orWhereHas('category', function ($categoryQuery) use ($normalizedSearch) {
                        $categoryQuery->whereRaw('LOWER(name) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"]);
                    });
            });
        }

        // Filtre par catégorie
        if ($request->has('category_id')) {
            $query->where('service_category_id', $request->category_id);
        }

        // Filtre par statut (open, in_progress, completed)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'open'); // Par défaut, seulement les services ouverts
        }

        // Filtre par urgence
        if ($request->has('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        // Filtre par proximité (latitude, longitude, radius en km)
        if ($request->has(['latitude', 'longitude'])) {
            $radius = $request->radius ?? 10; // 10km par défaut
            $query->nearby($request->latitude, $request->longitude, $radius);
        }

        $services = $query->paginate(15);
        $services->setCollection($this->decoratePrices($services->getCollection()));

        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }

    /**
     * Créer un nouveau service rapide
     */
    public function store(Request $request): JsonResponse
    {
        // Seuls les recruteurs peuvent publier un service rapide
        if (auth()->user()->role !== 'recruiter') {
            return response()->json([
                'success' => false,
                'message' => __('quick_service.only_recruiters_can_publish'),
            ], 403);
        }

        // 🎁 Quota gratuit : 1 seul service rapide sans abonnement actif.
        $existingCount = QuickService::where('user_id', auth()->id())->count();
        if ($existingCount >= 1) {
            $authUser = auth()->user();
            $activeSub = $authUser?->activeSubscription('recruiter');
            if (!$activeSub || $activeSub->isExpired()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Un abonnement recruteur actif est requis pour publier plusieurs services rapides.',
                    'error_code' => 'QUICK_SERVICE_LIMIT_REACHED',
                    'limit' => 1,
                    'used' => $existingCount,
                    'upgrade_required' => true,
                    'redirect_to' => '/subscription-plans',
                ], 403);
            }
        }

        $validator = Validator::make($request->all(), [
            'service_category_id' => 'required|exists:service_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price_type' => 'required|in:fixed,range,negotiable',
            'price_min' => 'required_if:price_type,fixed,range|nullable|numeric|min:0',
            'price_max' => 'required_if:price_type,range|nullable|numeric|min:0|gte:price_min',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location_name' => 'nullable|string|max:255',
            'urgency' => 'nullable|in:urgent,this_week,this_month,flexible',
            'desired_date' => 'nullable|date|after_or_equal:today',
            'estimated_duration' => 'nullable|string|max:100',
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('common.validation_error'),
                'errors' => $validator->errors(),
            ], 422);
        }

        // Upload des images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('quick_services', 'public');
                $imagePaths[] = $path;
            }
        }

        // Calculer la date d'expiration (30 jours par défaut)
        $expiresAt = now()->addDays(30);

        $service = QuickService::create([
            'user_id' => auth()->id(),
            'service_category_id' => $request->service_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price_type' => $request->price_type,
            'price_min' => $request->price_min,
            'price_max' => $request->price_max,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location_name' => $request->location_name,
            'urgency' => $request->urgency ?? 'flexible',
            'desired_date' => $request->desired_date,
            'estimated_duration' => $request->estimated_duration,
            'images' => $imagePaths,
            'expires_at' => $expiresAt,
            'status' => 'pending', // En attente d'approbation admin
        ]);

        $service->load(['user', 'category']);

        return response()->json([
            'success' => true,
            'message' => __('quick_service.created'),
            'data' => $service,
        ], 201);
    }

    /**
     * Afficher un service spécifique
     */
    public function show($id): JsonResponse
    {
        $service = QuickService::with(['user', 'category', 'responses.user'])
            ->findOrFail($id);

        // Incrémenter les vues
        $service->incrementViews();

        return response()->json([
            'success' => true,
            'data' => $this->decoratePrices($service),
        ]);
    }

    /**
     * Mettre à jour un service
     */
    public function update(Request $request, $id): JsonResponse
    {
        $service = QuickService::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($service->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => __('quick_service.not_authorized_modify'),
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'service_category_id' => 'sometimes|exists:service_categories,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price_type' => 'sometimes|in:fixed,range,negotiable',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0|gte:price_min',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'location_name' => 'nullable|string|max:255',
            'urgency' => 'sometimes|in:urgent,this_week,this_month,flexible',
            'desired_date' => 'nullable|date|after_or_equal:today',
            'estimated_duration' => 'nullable|string|max:100',
            'status' => 'sometimes|in:open,in_progress,completed,cancelled',
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('common.validation_error'),
                'errors' => $validator->errors(),
            ], 422);
        }

        // Upload des nouvelles images si fournies
        if ($request->hasFile('images')) {
            // Supprimer les anciennes images
            if ($service->images) {
                foreach ($service->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('quick_services', 'public');
                $imagePaths[] = $path;
            }
            $service->images = $imagePaths;
        }

        $service->update($request->except('images'));
        $service->load(['user', 'category']);

        return response()->json([
            'success' => true,
            'message' => __('quick_service.updated'),
            'data' => $service,
        ]);
    }

    /**
     * Supprimer un service
     */
    public function destroy($id): JsonResponse
    {
        $service = QuickService::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($service->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => __('quick_service.not_authorized_delete'),
            ], 403);
        }

        // Supprimer les images
        if ($service->images) {
            foreach ($service->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => __('quick_service.deleted'),
        ]);
    }

    /**
     * Répondre à un service
     */
    public function respond(Request $request, $id): JsonResponse
    {
        $service = QuickService::findOrFail($id);

        // Vérifier que le service est ouvert
        if (!$service->isOpen()) {
            return response()->json([
                'success' => false,
                'message' => __('quick_service.no_more_responses'),
            ], 422);
        }

        // Vérifier que l'utilisateur ne répond pas à son propre service
        if ($service->user_id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => __('quick_service.cannot_respond_own'),
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'proposed_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('common.validation_error'),
                'errors' => $validator->errors(),
            ], 422);
        }

        $response = ServiceResponse::create([
            'quick_service_id' => $service->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'proposed_price' => $request->proposed_price,
        ]);

        $response->load('user');

        return response()->json([
            'success' => true,
            'message' => __('quick_service.response_sent'),
            'data' => $response,
        ], 201);
    }

    /**
     * Accepter une réponse
     */
    public function acceptResponse($serviceId, $responseId): JsonResponse
    {
        $service = QuickService::findOrFail($serviceId);

        // Vérifier que l'utilisateur est le propriétaire du service
        if ($service->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => __('quick_service.not_authorized_accept'),
            ], 403);
        }

        $response = ServiceResponse::where('quick_service_id', $serviceId)
            ->where('id', $responseId)
            ->firstOrFail();

        $response->update(['status' => 'accepted']);
        $service->update(['status' => 'in_progress']);

        return response()->json([
            'success' => true,
            'message' => __('quick_service.response_accepted'),
            'data' => $response,
        ]);
    }

    /**
     * Rejeter une réponse
     */
    public function rejectResponse($serviceId, $responseId): JsonResponse
    {
        $service = QuickService::findOrFail($serviceId);

        // Vérifier que l'utilisateur est le propriétaire du service
        if ($service->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => __('quick_service.not_authorized_reject'),
            ], 403);
        }

        $response = ServiceResponse::where('quick_service_id', $serviceId)
            ->where('id', $responseId)
            ->firstOrFail();

        $response->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => __('quick_service.response_rejected'),
        ]);
    }

    /**
     * Mes services postés
     */
    public function myServices(): JsonResponse
    {
        $services = QuickService::with(['category'])
            ->withCount('responses')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);
        $services->setCollection($this->decoratePrices($services->getCollection()));

        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }

    /**
     * Mes réponses aux services
     */
    public function myResponses(): JsonResponse
    {
        $responses = ServiceResponse::with(['quickService.category', 'quickService.user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $responses,
        ]);
    }

    /**
     * Liste des catégories de services
     */
    public function categories(): JsonResponse
    {
        $categories = ServiceCategory::active()
            ->ordered()
            ->with('translations')
            ->withCount('quickServices')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->t('name'),
                'slug' => $category->slug,
                'description' => $category->t('description'),
                'icon' => $category->icon,
                'color' => $category->color,
                'display_order' => $category->display_order,
                'is_active' => $category->is_active,
                'quick_services_count' => $category->quick_services_count,
            ]),
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
