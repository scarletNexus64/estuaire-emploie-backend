<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DigitalizationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DigitalizationRequestController extends Controller
{
    /**
     * Soumettre une demande de digitalisation depuis l'app mobile.
     * Formulaire court : nom du projet, contact, description (≤ 50 mots),
     * + entreprise / ville / quartier optionnels (pré-remplis côté app).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
        ]);

        // Limite de 50 mots sur la description.
        if (str_word_count($validated['description']) > 50) {
            return response()->json([
                'message' => 'La description ne doit pas dépasser 50 mots.',
                'errors' => ['description' => ['La description ne doit pas dépasser 50 mots.']],
            ], 422);
        }

        $digitalizationRequest = DigitalizationRequest::create([
            'user_id' => $request->user()->id,
            'project_name' => $validated['project_name'],
            'company_name' => $validated['company_name'] ?? null,
            'city' => $validated['city'] ?? null,
            'district' => $validated['district'] ?? null,
            'contact' => $validated['contact'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return response()->json([
            'data' => $digitalizationRequest,
            'message' => 'Votre demande de digitalisation a été envoyée avec succès.',
        ], 201);
    }

    /**
     * Liste des demandes de l'utilisateur connecté.
     */
    public function myRequests(Request $request): JsonResponse
    {
        $requests = DigitalizationRequest::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json($requests);
    }

    /**
     * Supprime une demande de l'utilisateur connecté.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $deleted = DigitalizationRequest::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'message' => 'Demande introuvable.',
            ], 404);
        }

        return response()->json([
            'message' => 'Demande supprimée avec succès.',
        ]);
    }

    /**
     * Supprime plusieurs demandes de l'utilisateur connecté.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $count = DigitalizationRequest::where('user_id', $request->user()->id)
            ->whereIn('id', $validated['ids'])
            ->delete();

        return response()->json([
            'message' => "{$count} demande(s) supprimée(s).",
            'deleted_count' => $count,
        ]);
    }
}
