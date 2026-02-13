<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return view('admin.companies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'sector' => 'required|string|max:255',
                'website' => 'nullable|url',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'description' => 'nullable|string',
                'status' => 'required|in:pending,verified,suspended',
                'subscription_plan' => 'required|in:free,premium',
            ]);

            // Gérer l'upload du logo
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $validated['logo'] = $logoPath;
            }

            if ($validated['status'] === 'verified') {
                $validated['verified_at'] = now();
            }

            Company::create($validated);

            return redirect()->route('admin.companies.index')
                ->with('success', 'Entreprise créée avec succès');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création d\'entreprise', [
                'error' => $e->getMessage(),
                'request' => $request->except(['logo'])
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    public function show(Company $company): View
    {
        $company->load(['jobs', 'recruiters.user']);

        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company): View
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'sector' => 'required|string|max:255',
                'website' => 'nullable|url',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'description' => 'nullable|string',
                'status' => 'required|in:pending,verified,suspended',
                'subscription_plan' => 'required|in:free,premium',
            ]);

            // Gérer l'upload du logo
            if ($request->hasFile('logo')) {
                // Supprimer l'ancien logo si existant
                if ($company->logo && \Storage::disk('public')->exists($company->logo)) {
                    \Storage::disk('public')->delete($company->logo);
                }

                // Sauvegarder le nouveau logo
                $logoPath = $request->file('logo')->store('logos', 'public');
                $validated['logo'] = $logoPath;
            }

            if ($validated['status'] === 'verified' && !$company->verified_at) {
                $validated['verified_at'] = now();
            }

            $company->update($validated);

            return redirect()->route('admin.companies.index')
                ->with('success', 'Entreprise mise à jour avec succès');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour d\'entreprise', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
                'request' => $request->except(['logo'])
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Entreprise supprimée avec succès');
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

            $count = Company::whereIn('id', $ids)->delete();

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
                    'message' => 'Erreur de connexion à Google Maps. Veuillez réessayer.',
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
                    'message' => 'Erreur de communication avec Google Maps (HTTP ' . $httpCode . ').',
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
                    'message' => 'Réponse invalide de Google Maps.',
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
                        'message' => 'Aucune adresse trouvée. Essayez avec une adresse plus précise (ex: "Rue, Ville, Pays").',
                    ], 404);

                case 'OVER_QUERY_LIMIT':
                    \Log::warning('Quota Google Geocoding API dépassé');
                    return response()->json([
                        'success' => false,
                        'message' => 'Limite de requêtes atteinte. Veuillez réessayer dans quelques minutes.',
                    ], 429);

                case 'REQUEST_DENIED':
                    \Log::error('Clé API Google Maps refusée', [
                        'error_message' => $data['error_message'] ?? 'Pas de message d\'erreur',
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur de configuration de l\'API Google Maps. Contactez l\'administrateur.',
                    ], 500);

                case 'INVALID_REQUEST':
                    return response()->json([
                        'success' => false,
                        'message' => 'Adresse invalide. Veuillez saisir une adresse valide.',
                    ], 400);

                default:
                    \Log::error('Statut Google Geocoding API inconnu', [
                        'status' => $data['status'],
                        'error_message' => $data['error_message'] ?? null,
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la géolocalisation: ' . ($data['error_message'] ?? $data['status']),
                    ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'Adresse introuvable. Veuillez vérifier l\'adresse saisie.',
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez saisir une adresse valide (minimum 3 caractères).',
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Exception lors de la vérification d\'adresse', [
                'address' => $request->input('address'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur inattendue est survenue. Veuillez réessayer.',
            ], 500);
        }
    }
}
