<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PortfolioController extends Controller
{
    /**
     * Get authenticated user's portfolio
     */
    public function show(Request $request): JsonResponse
    {
        $portfolio = Portfolio::where('user_id', $request->user()->id)->first();

        if (!$portfolio) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $portfolio,
        ]);
    }

    /**
     * Create or update portfolio
     */
    public function store(Request $request): JsonResponse
    {
        // Decode JSON fields from multipart form data
        $jsonFields = ['skills', 'experiences', 'education', 'projects', 'certifications', 'languages', 'social_links'];
        foreach ($jsonFields as $field) {
            if ($request->has($field) && is_string($request->input($field))) {
                $decoded = json_decode($request->input($field), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge([$field => $decoded]);
                }
            }
        }

        // Convert string boolean to actual boolean
        if ($request->has('is_public') && is_string($request->input('is_public'))) {
            $request->merge(['is_public' => filter_var($request->input('is_public'), FILTER_VALIDATE_BOOLEAN)]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // 2MB max
            'cv' => 'nullable|file|mimes:pdf|max:5120', // 5MB max
            'skills' => 'nullable|array',
            'skills.*.name' => 'required|string',
            'skills.*.level' => 'required|in:Débutant,Intermédiaire,Avancé,Expert',
            'experiences' => 'nullable|array',
            'experiences.*.title' => 'required|string',
            'experiences.*.company' => 'required|string',
            'experiences.*.duration' => 'required|string',
            'experiences.*.description' => 'nullable|string',
            'education' => 'nullable|array',
            'education.*.degree' => 'required|string',
            'education.*.school' => 'required|string',
            'education.*.year' => 'required|string',
            'education.*.description' => 'nullable|string',
            'projects' => 'nullable|array',
            'projects.*.name' => 'required|string',
            'projects.*.description' => 'required|string',
            'projects.*.url' => 'nullable|url',
            'projects.*.image' => 'nullable|url',
            'projects.*.technologies' => 'nullable|array',
            'certifications' => 'nullable|array',
            'certifications.*.name' => 'required|string',
            'certifications.*.issuer' => 'required|string',
            'certifications.*.date' => 'required|string',
            'certifications.*.credential_url' => 'nullable|url',
            'languages' => 'nullable|array',
            'languages.*.language' => 'required|string',
            'languages.*.level' => 'required|in:Débutant,Intermédiaire,Courant,Langue maternelle',
            'social_links' => 'nullable|array',
            'social_links.linkedin' => 'nullable|url',
            'social_links.github' => 'nullable|url',
            'social_links.twitter' => 'nullable|url',
            'social_links.website' => 'nullable|url',
            'template_id' => 'nullable|in:professional,creative,tech',
            'theme_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->except(['photo', 'cv']);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('portfolios/photos', 'public');
            $data['photo_url'] = url(Storage::url($photoPath));
        }

        // Handle CV upload
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('portfolios/cvs', 'public');
            $data['cv_url'] = url(Storage::url($cvPath));
        }

        // Create or update portfolio
        $portfolio = Portfolio::updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Portfolio enregistré avec succès',
            'data' => $portfolio,
        ]);
    }

    /**
     * Delete portfolio
     */
    public function destroy(Request $request): JsonResponse
    {
        $portfolio = Portfolio::where('user_id', $request->user()->id)->first();

        if (!$portfolio) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio non trouvé',
            ], 404);
        }

        // Delete files
        if ($portfolio->photo_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $portfolio->photo_url));
        }
        if ($portfolio->cv_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $portfolio->cv_url));
        }

        $portfolio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Portfolio supprimé avec succès',
        ]);
    }

    /**
     * Get portfolio by slug (public access)
     */
    public function showBySlug(Request $request, string $slug): JsonResponse
    {
        $portfolio = Portfolio::with('user:id,name,email')
            ->where('slug', $slug)
            ->where('is_public', true)
            ->first();

        if (!$portfolio) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio non trouvé ou privé',
            ], 404);
        }

        // Record view
        $portfolio->recordView(
            $request->user()?->id,
            $request->ip(),
            $request->userAgent(),
            $request->header('referer')
        );

        return response()->json([
            'success' => true,
            'data' => $portfolio,
        ]);
    }

    /**
     * Toggle portfolio visibility
     */
    public function toggleVisibility(Request $request): JsonResponse
    {
        $portfolio = Portfolio::where('user_id', $request->user()->id)->first();

        if (!$portfolio) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio non trouvé',
            ], 404);
        }

        $portfolio->is_public = !$portfolio->is_public;
        $portfolio->save();

        return response()->json([
            'success' => true,
            'message' => 'Visibilité du portfolio mise à jour',
            'data' => [
                'is_public' => $portfolio->is_public,
            ],
        ]);
    }

    /**
     * Get portfolio stats
     */
    public function stats(Request $request): JsonResponse
    {
        $portfolio = Portfolio::where('user_id', $request->user()->id)->first();

        if (!$portfolio) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_views' => $portfolio->view_count,
                'views_last_7_days' => $portfolio->getViewsInLastDays(7),
                'views_last_30_days' => $portfolio->getViewsInLastDays(30),
                'unique_viewers' => $portfolio->getUniqueViewersCount(),
            ],
        ]);
    }
}
