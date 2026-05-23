<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\ManualSubscriptionAssignment;
use App\Models\Payment;
use App\Models\Recruiter;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Company::withCount(['jobs', 'recruiters']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('sector', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Plan filter
        if ($request->filled('plan')) {
            $query->where('subscription_plan', $request->plan);
        }

        $companies = $query->latest()->paginate(20)->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }

    public function create(): View
    {
        $categories = CompanyCategory::where('is_active', true)
            ->orderBy('level_1')
            ->orderBy('level_2')
            ->orderBy('level_3')
            ->get()
            ->groupBy('level_1');

        $recruiterPlans = SubscriptionPlan::recruiter()
            ->active()
            ->ordered()
            ->get();

        return view('admin.companies.create', compact('categories', 'recruiterPlans'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:companies,email',
                'phone' => 'required|string|max:20',
                'description' => 'required|string|min:30',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'photos' => 'nullable|array|max:4',
                'photos.*' => 'image|mimes:png,jpg,jpeg|max:2048',
                'category_ids' => 'required|array|min:1',
                'category_ids.*' => 'exists:company_categories,id',
                'sector' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'status' => 'required|in:pending,verified,suspended',
                // Plan recruteur à attribuer (issu de la table subscription_plans, type=recruiter).
                // 'free' = pas d'attribution de plan payant ; on garde la valeur sur companies.subscription_plan
                // pour rétrocompat avec l'ancien champ.
                'recruiter_plan_id' => 'nullable|exists:subscription_plans,id',
                'subscription_plan' => 'required|in:free,premium',
            ]);

            // L'admin peut posséder plusieurs entreprises et plusieurs abonnements
            // (différent du flux Flutter recruteur où 1 user = 1 entreprise).
            // On valide juste que le plan demandé existe et est bien recruteur.
            $plan = null;
            if (!empty($validated['recruiter_plan_id'])) {
                $plan = SubscriptionPlan::find($validated['recruiter_plan_id']);
                if (!$plan || $plan->plan_type !== 'recruiter') {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Le plan choisi n\'est pas un plan recruteur.');
                }
            }

            if (isset($validated['address'])) {
                $validated['address'] = mb_substr($validated['address'], 0, 255);
            }
            if (isset($validated['city'])) {
                $validated['city'] = mb_substr($validated['city'], 0, 255);
            }
            $validated['email'] = strtolower($validated['email']);

            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo')->store('logos', 'public');
            }

            if ($request->hasFile('photos')) {
                $photoPaths = [];
                foreach ($request->file('photos') as $photo) {
                    $photoPaths[] = $photo->store('company_photos', 'public');
                }
                $validated['photos'] = $photoPaths;
            }

            $validated['domain'] = $validated['domain'] ?? 'Général';
            $validated['sector'] = $validated['sector'] ?? 'Divers';
            $validated['country'] = $validated['country'] ?? 'Cameroun';

            if ($validated['status'] === 'verified') {
                $validated['verified_at'] = now();
            }

            $categoryIds = $validated['category_ids'];
            $planId = $validated['recruiter_plan_id'] ?? null;
            unset($validated['category_ids'], $validated['recruiter_plan_id']);

            // Préserver le rôle original de l'admin : UserSubscriptionPlan::activate()
            // et Api/CompanyController force role='recruiter' sur l'utilisateur, ce qui
            // ferait perdre les droits admin (isSuperAdmin() exige role==='admin').
            $user = Auth::user();
            $originalRole = $user->role;
            $shouldPreserveRole = ($originalRole === 'admin');

            $company = DB::transaction(function () use ($validated, $categoryIds, $plan) {
                $company = Company::create($validated);
                $company->categories()->attach($categoryIds);

                Recruiter::create([
                    'user_id' => Auth::id(),
                    'company_id' => $company->id,
                    'position' => 'Administrateur',
                    'can_publish' => true,
                    'can_view_applications' => true,
                    'can_modify_company' => true,
                ]);

                if ($plan) {
                    $this->assignRecruiterPlan($plan);
                }

                return $company;
            });

            // Restaurer le rôle admin si une opération l'a écrasé pendant la transaction
            if ($shouldPreserveRole) {
                $fresh = $user->fresh();
                if ($fresh && $fresh->role !== $originalRole) {
                    $fresh->role = $originalRole;
                    $fresh->save();
                    \Log::info("[Admin/CompanyController] Restored admin role for user {$user->id} after company/plan creation");
                }
            }

            $msg = 'Entreprise créée avec succès. Vous pouvez maintenant publier une offre.';
            if ($plan) {
                $msg .= " Plan « {$plan->name} » attribué à votre compte.";
            }

            return redirect()->route('admin.companies.show', $company)->with('success', $msg);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création d\'entreprise', [
                'error' => $e->getMessage(),
                'request' => $request->except(['logo', 'photos'])
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Attribue un plan recruteur à l'admin courant en réutilisant le flux
     * "manual subscription" (Payment + UserSubscriptionPlan + traçabilité).
     */
    private function assignRecruiterPlan(SubscriptionPlan $plan): void
    {
        $user = Auth::user();

        // Note: 'payment_method' est un enum strict en base. On utilise 'promotional_free'
        // pour les attributions manuelles par admin (la valeur 'manual_assignment' n'existe
        // pas dans l'enum, malgré ce que fait ManualSubscriptionController — bug connu).
        $payment = Payment::create([
            'user_id' => $user->id,
            'payable_type' => SubscriptionPlan::class,
            'payable_id' => $plan->id,
            'amount' => $plan->price,
            'fees' => 0,
            'total' => $plan->price,
            'payment_method' => 'promotional_free',
            'provider' => 'admin',
            'external_id' => 'MANUAL-COMPANY-' . now()->format('YmdHis') . '-' . $user->id,
            'status' => 'completed',
            'paid_at' => now(),
            'description' => "Plan {$plan->name} attribué automatiquement à la création de l'entreprise par {$user->name}",
        ]);

        $subscription = UserSubscriptionPlan::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'payment_id' => $payment->id,
        ]);
        $subscription->activate();

        ManualSubscriptionAssignment::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'payment_id' => $payment->id,
            'user_subscription_plan_id' => $subscription->id,
            'assigned_by_admin_id' => $user->id,
            'reason' => 'Attribution lors de la création de l\'entreprise',
        ]);
    }

    public function show(Company $company): View
    {
        $company->load([
            'jobs',
            'recruiters.user',
            'categories',
            'products' => fn ($q) => $q->with('category')->latest(),
            'productPurchases' => fn ($q) => $q->with(['product', 'buyer'])->latest(),
        ]);

        $showcaseStats = [
            'total'    => $company->products->count(),
            'products' => $company->products->where('type', 'product')->count(),
            'services' => $company->products->where('type', 'service')->count(),
            'active'   => $company->products->where('is_active', true)->count(),
            'sales'    => $company->productPurchases->where('status', 'paid')->count(),
            'revenue'  => $company->productPurchases->where('status', 'paid')->sum('amount'),
        ];

        return view('admin.companies.show', compact('company', 'showcaseStats'));
    }

    public function edit(Company $company): View
    {
        $company->load('categories');

        $categories = CompanyCategory::where('is_active', true)
            ->orderBy('level_1')
            ->orderBy('level_2')
            ->orderBy('level_3')
            ->get()
            ->groupBy('level_1');

        $recruiterPlans = SubscriptionPlan::recruiter()
            ->active()
            ->ordered()
            ->get();

        return view('admin.companies.edit', compact('company', 'categories', 'recruiterPlans'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'description' => 'required|string|min:30',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'photos' => 'nullable|array|max:4',
                'photos.*' => 'image|mimes:png,jpg,jpeg|max:2048',
                'keep_photos' => 'nullable|array|max:4',
                'keep_photos.*' => 'string',
                'category_ids' => 'required|array|min:1',
                'category_ids.*' => 'exists:company_categories,id',
                'sector' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'status' => 'required|in:pending,verified,suspended',
                'subscription_plan' => 'required|in:free,premium',
            ]);

            if (isset($validated['address'])) {
                $validated['address'] = mb_substr($validated['address'], 0, 255);
            }
            if (isset($validated['city'])) {
                $validated['city'] = mb_substr($validated['city'], 0, 255);
            }

            // Logo : remplacer si nouveau fourni
            if ($request->hasFile('logo')) {
                if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                    Storage::disk('public')->delete($company->logo);
                }
                $validated['logo'] = $request->file('logo')->store('logos', 'public');
            }

            // Photos : conserver celles cochées dans keep_photos + ajouter les nouvelles
            $currentPhotos = $company->photos ?? [];
            $keepRelative = [];
            $appUrl = rtrim(config('app.url'), '/');
            foreach ((array) $request->input('keep_photos', []) as $url) {
                // Le formulaire renvoie soit le chemin relatif (company_photos/xxx.jpg)
                // soit l'URL complète. On normalise vers le chemin relatif stocké.
                $path = $url;
                if (str_starts_with($url, 'http')) {
                    $path = ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/');
                    $path = preg_replace('#^storage/#', '', $path);
                }
                $keepRelative[] = $path;
            }
            // Supprimer du disque les photos non gardées
            foreach ($currentPhotos as $oldPhoto) {
                if (!in_array($oldPhoto, $keepRelative, true) && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }
            $finalPhotos = array_values(array_intersect($currentPhotos, $keepRelative));
            // Ajouter les nouveaux uploads
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    if (count($finalPhotos) >= 4) {
                        break;
                    }
                    $finalPhotos[] = $photo->store('company_photos', 'public');
                }
            }
            $validated['photos'] = $finalPhotos;

            if ($validated['status'] === 'verified' && !$company->verified_at) {
                $validated['verified_at'] = now();
            }

            $categoryIds = $validated['category_ids'];
            unset($validated['category_ids'], $validated['keep_photos']);

            $company->update($validated);

            // Sync = remplace toutes les catégories (comportement aligné API updateMyCompany)
            $company->categories()->sync($categoryIds);

            return redirect()->route('admin.companies.show', $company)
                ->with('success', 'Entreprise mise à jour avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour d\'entreprise', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
                'request' => $request->except(['logo', 'photos'])
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function destroy(Company $company): RedirectResponse
    {
        try {
            \DB::transaction(function () use ($company) {
                // Supprimer les recruteurs associés pour libérer les utilisateurs
                // (la vérification API se base sur la présence d'un Recruiter)
                $company->recruiters()->delete();

                // forceDelete car Company utilise SoftDeletes : un simple delete()
                // laisserait l'entreprise en base et l'utilisateur resterait bloqué
                $company->forceDelete();
            });

            return redirect()->route('admin.companies.index')
                ->with('success', 'Entreprise supprimée avec succès');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression d\'entreprise', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function verify(Company $company): RedirectResponse
    {
        try {
            $company->update([
                'status' => 'verified',
                'verified_at' => now(),
            ]);

            // Dispatcher le job asynchrone pour envoyer les notifications en arrière-plan
            \App\Jobs\SendCompanyVerifiedNotification::dispatch($company);

            return redirect()->back()
                ->with('success', 'Entreprise vérifiée avec succès ! Les notifications sont en cours d\'envoi en arrière-plan.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la vérification d\'entreprise', [
                'company_id' => $company->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la vérification: ' . $e->getMessage());
        }
    }

    public function suspend(Company $company): RedirectResponse
    {
        $company->update([
            'status' => 'suspended',
        ]);

        return redirect()->back()
            ->with('success', 'Entreprise suspendue avec succès');
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->input('ids'), true);

            if (!is_array($ids) || empty($ids)) {
                return redirect()->back()->with('error', 'Aucun élément sélectionné');
            }

            $count = 0;
            \DB::transaction(function () use ($ids, &$count) {
                $companies = Company::whereIn('id', $ids)->get();
                foreach ($companies as $company) {
                    // Libérer les utilisateurs en supprimant les recruteurs associés
                    $company->recruiters()->delete();
                    // forceDelete pour ne pas laisser l'entreprise en soft delete
                    $company->forceDelete();
                    $count++;
                }
            });

            return redirect()->back()->with('success', "$count élément(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier une adresse via Google Geocoding API et retourner les coordonnées GPS
     */
    public function verifyAddress(Request $request)
    {
        try {
            $request->validate([
                'address' => 'required|string|min:3',
            ]);

            $address = $request->input('address');

            // Appel à Google Geocoding API
            $apiKey = 'AIzaSyAffUHSFli6kMnjkfJOKBGO6AN828ixJPo';
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query([
                'address' => $address,
                'key' => $apiKey,
            ]);

            // Utiliser cURL pour une meilleure gestion des erreurs
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Vérifier les erreurs cURL
            if ($curlError) {
                \Log::error('Erreur cURL lors de la vérification d\'adresse', [
                    'address' => $address,
                    'curl_error' => $curlError,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => __('admin_company.maps_connection_error'),
                ], 500);
            }

            if ($httpCode !== 200) {
                \Log::error('Erreur HTTP lors de la vérification d\'adresse', [
                    'address' => $address,
                    'http_code' => $httpCode,
                    'response' => $response,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => __('admin_company.maps_http_error', ['code' => $httpCode]),
                ], 500);
            }

            $data = json_decode($response, true);

            // Logger la réponse pour debug
            \Log::info('Réponse Google Geocoding API', [
                'address' => $address,
                'status' => $data['status'] ?? 'UNKNOWN',
                'results_count' => isset($data['results']) ? count($data['results']) : 0,
            ]);

            // Gérer les différents statuts de l'API Google
            if (!isset($data['status'])) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin_company.maps_invalid_response'),
                ], 500);
            }

            switch ($data['status']) {
                case 'OK':
                    if (!empty($data['results'])) {
                        $result = $data['results'][0];
                        $location = $result['geometry']['location'];

                        return response()->json([
                            'success' => true,
                            'latitude' => $location['lat'],
                            'longitude' => $location['lng'],
                            'formatted_address' => $result['formatted_address'],
                        ]);
                    }
                    break;

                case 'ZERO_RESULTS':
                    return response()->json([
                        'success' => false,
                        'message' => __('admin_company.address_not_found'),
                    ], 404);

                case 'OVER_QUERY_LIMIT':
                    \Log::warning('Quota Google Geocoding API dépassé');
                    return response()->json([
                        'success' => false,
                        'message' => __('admin_company.rate_limit'),
                    ], 429);

                case 'REQUEST_DENIED':
                    \Log::error('Clé API Google Maps refusée', [
                        'error_message' => $data['error_message'] ?? 'Pas de message d\'erreur',
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => __('admin_company.maps_api_config_error'),
                    ], 500);

                case 'INVALID_REQUEST':
                    return response()->json([
                        'success' => false,
                        'message' => __('admin_company.invalid_address'),
                    ], 400);

                default:
                    \Log::error('Statut Google Geocoding API inconnu', [
                        'status' => $data['status'],
                        'error_message' => $data['error_message'] ?? null,
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => __('admin_company.geolocation_error', ['error' => $data['error_message'] ?? $data['status']]),
                    ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => __('admin_company.address_unverifiable'),
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_company.enter_valid_address'),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Exception lors de la vérification d\'adresse', [
                'address' => $request->input('address'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('common.unexpected_error'),
            ], 500);
        }
    }
}
