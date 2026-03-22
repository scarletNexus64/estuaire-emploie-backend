<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Services\Resume\ResumePdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResumeController extends Controller
{
    protected $pdfService;

    public function __construct(ResumePdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Récupère tous les CVs de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $resumes = $user->resumes()
            ->orderBy('is_default', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($resume) {
                return $resume->getSummary();
            });

        return response()->json([
            'success' => true,
            'data' => $resumes,
        ]);
    }

    /**
     * Récupère les templates disponibles
     */
    public function templates()
    {
        $templates = Resume::getAvailableTemplates();

        return response()->json([
            'success' => true,
            'data' => $templates,
        ]);
    }

    /**
     * Crée un nouveau CV
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'template_type' => 'required|string|in:modern,classic,creative,professional,minimalist',
            'personal_info' => 'required|array',
            'personal_info.name' => 'required|string',
            'personal_info.email' => 'required|email',
            'personal_info.phone' => 'nullable|string',
            'personal_info.address' => 'nullable|string',
            'personal_info.linkedin' => 'nullable|string',
            'personal_info.github' => 'nullable|string',
            'personal_info.website' => 'nullable|string',
            'professional_summary' => 'nullable|string',
            'experiences' => 'nullable|array',
            'education' => 'nullable|array',
            'skills' => 'nullable|array',
            'certifications' => 'nullable|array',
            'projects' => 'nullable|array',
            'references' => 'nullable|array',
            'hobbies' => 'nullable|array',
            'customization' => 'nullable|array',
            'is_public' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        // Si c'est le premier CV, le définir comme défaut
        $isFirstResume = $user->resumes()->count() === 0;

        $resume = $user->resumes()->create([
            'title' => $request->title,
            'template_type' => $request->template_type,
            'personal_info' => $request->personal_info,
            'professional_summary' => $request->professional_summary,
            'experiences' => $request->experiences,
            'education' => $request->education,
            'skills' => $request->skills,
            'certifications' => $request->certifications,
            'projects' => $request->projects,
            'references' => $request->references,
            'hobbies' => $request->hobbies,
            'customization' => $request->customization ?? [],
            'is_public' => $request->is_public ?? false,
            'is_default' => $isFirstResume ? true : ($request->is_default ?? false),
        ]);

        // Si défini comme défaut, retirer le flag des autres
        if ($resume->is_default) {
            $resume->setAsDefault();
        }

        // Générer automatiquement le PDF
        try {
            $this->pdfService->generatePdf($resume);
            $resume->refresh(); // Recharger pour avoir le pdf_path mis à jour
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas la création
            \Log::error('Erreur génération PDF lors création CV: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'CV créé avec succès',
            'data' => $resume->fresh(), // Retourner le CV complet avec pdf_path
        ], 201);
    }

    /**
     * Affiche un CV spécifique
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $resume = Resume::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$resume) {
            return response()->json([
                'success' => false,
                'message' => 'CV non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $resume,
        ]);
    }

    /**
     * Met à jour un CV
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        $resume = Resume::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$resume) {
            return response()->json([
                'success' => false,
                'message' => 'CV non trouvé',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'template_type' => 'sometimes|required|string|in:modern,classic,creative,professional,minimalist',
            'personal_info' => 'sometimes|required|array',
            'professional_summary' => 'nullable|string',
            'experiences' => 'nullable|array',
            'education' => 'nullable|array',
            'skills' => 'nullable|array',
            'certifications' => 'nullable|array',
            'projects' => 'nullable|array',
            'references' => 'nullable|array',
            'hobbies' => 'nullable|array',
            'customization' => 'nullable|array',
            'is_public' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        $resume->update($request->only([
            'title',
            'template_type',
            'personal_info',
            'professional_summary',
            'experiences',
            'education',
            'skills',
            'certifications',
            'projects',
            'references',
            'hobbies',
            'customization',
            'is_public',
            'is_default',
        ]));

        // Si défini comme défaut, retirer le flag des autres
        if ($request->has('is_default') && $request->is_default) {
            $resume->setAsDefault();
        }

        // Régénérer automatiquement le PDF après mise à jour
        try {
            $this->pdfService->generatePdf($resume);
            $resume->refresh(); // Recharger pour avoir le pdf_path mis à jour
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas la mise à jour
            \Log::error('Erreur génération PDF lors mise à jour CV: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'CV mis à jour avec succès',
            'data' => $resume->fresh(), // Retourner le CV complet avec pdf_path
        ]);
    }

    /**
     * Supprime un CV
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $resume = Resume::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$resume) {
            return response()->json([
                'success' => false,
                'message' => 'CV non trouvé',
            ], 404);
        }

        // Si c'est le CV par défaut, définir un autre CV comme défaut
        if ($resume->is_default) {
            $nextResume = $user->resumes()
                ->where('id', '!=', $resume->id)
                ->first();

            if ($nextResume) {
                $nextResume->setAsDefault();
            }
        }

        $resume->delete();

        return response()->json([
            'success' => true,
            'message' => 'CV supprimé avec succès',
        ]);
    }

    /**
     * Génère le PDF d'un CV
     */
    public function generatePdf(Request $request, $id)
    {
        $user = $request->user();

        $resume = Resume::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$resume) {
            return response()->json([
                'success' => false,
                'message' => 'CV non trouvé',
            ], 404);
        }

        try {
            // Générer le PDF
            $pdfPath = $this->pdfService->generatePdf($resume);

            $resume->refresh();

            return response()->json([
                'success' => true,
                'message' => 'PDF généré avec succès',
                'data' => [
                    'pdf_url' => $resume->pdf_url,
                    'generated_at' => $resume->pdf_generated_at->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du PDF',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Définit un CV comme CV par défaut
     */
    public function setDefault(Request $request, $id)
    {
        $user = $request->user();

        $resume = Resume::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$resume) {
            return response()->json([
                'success' => false,
                'message' => 'CV non trouvé',
            ], 404);
        }

        $resume->setAsDefault();

        return response()->json([
            'success' => true,
            'message' => 'CV défini comme CV par défaut',
            'data' => $resume->getSummary(),
        ]);
    }

    /**
     * Récupère le CV par défaut de l'utilisateur
     */
    public function getDefault(Request $request)
    {
        $user = $request->user();

        $resume = Resume::getUserDefaultResume($user->id);

        if (!$resume) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun CV par défaut trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $resume,
        ]);
    }

    /**
     * Duplique un CV
     */
    public function duplicate(Request $request, $id)
    {
        $user = $request->user();

        $resume = Resume::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$resume) {
            return response()->json([
                'success' => false,
                'message' => 'CV non trouvé',
            ], 404);
        }

        $newResume = $resume->replicate();
        $newResume->title = $resume->title . ' (Copie)';
        $newResume->is_default = false;
        $newResume->pdf_path = null;
        $newResume->pdf_generated_at = null;
        $newResume->save();

        return response()->json([
            'success' => true,
            'message' => 'CV dupliqué avec succès',
            'data' => $newResume->getSummary(),
        ], 201);
    }
}
