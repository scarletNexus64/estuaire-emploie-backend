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
        // Gérer les données de formulaire multipart (avec photo) ou JSON standard
        $data = $request->has('resume_data')
            ? json_decode($request->input('resume_data'), true)
            : $request->all();

        $validator = Validator::make($data, [
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

        // Valider la photo de profil si elle est présente
        if ($request->hasFile('profile_photo')) {
            $photoValidator = Validator::make($request->all(), [
                'profile_photo' => 'image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
            ]);

            if ($photoValidator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photo invalide',
                    'errors' => $photoValidator->errors(),
                ], 422);
            }
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        // Gérer l'upload de la photo de profil
        $personalInfo = $data['personal_info'];

        \Log::info('📸 Upload photo - hasFile: ' . ($request->hasFile('profile_photo') ? 'OUI' : 'NON'));

        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            \Log::info('📸 Upload photo - Taille: ' . $photo->getSize() . ' bytes');
            \Log::info('📸 Upload photo - MIME: ' . $photo->getMimeType());

            $photoPath = $photo->store('resumes/photos', 'public');
            $personalInfo['photo'] = $photoPath;

            \Log::info('📸 Upload photo - Sauvegardée: ' . $photoPath);
            \Log::info('📸 Personal info après ajout photo: ' . json_encode($personalInfo));
        } else {
            \Log::info('📸 Aucune photo uploadée dans cette requête');
        }

        // Si c'est le premier CV, le définir comme défaut
        $isFirstResume = $user->resumes()->count() === 0;

        $resume = $user->resumes()->create([
            'title' => $data['title'],
            'template_type' => $data['template_type'],
            'personal_info' => $personalInfo,
            'professional_summary' => $data['professional_summary'] ?? null,
            'experiences' => $data['experiences'] ?? null,
            'education' => $data['education'] ?? null,
            'skills' => $data['skills'] ?? null,
            'certifications' => $data['certifications'] ?? null,
            'projects' => $data['projects'] ?? null,
            'references' => $data['references'] ?? null,
            'hobbies' => $data['hobbies'] ?? null,
            'customization' => $data['customization'] ?? [],
            'is_public' => $data['is_public'] ?? false,
            'is_default' => $isFirstResume ? true : ($data['is_default'] ?? false),
        ]);

        \Log::info('✅ CV créé - ID: ' . $resume->id);
        \Log::info('✅ CV créé - Personal info sauvegardé: ' . json_encode($resume->personal_info));

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

        // Gérer les données de formulaire multipart (avec photo) ou JSON standard
        $data = $request->has('resume_data')
            ? json_decode($request->input('resume_data'), true)
            : $request->all();

        $validator = Validator::make($data, [
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

        // Valider la photo de profil si elle est présente
        if ($request->hasFile('profile_photo')) {
            $photoValidator = Validator::make($request->all(), [
                'profile_photo' => 'image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
            ]);

            if ($photoValidator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photo invalide',
                    'errors' => $photoValidator->errors(),
                ], 422);
            }
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Gérer l'upload de la photo de profil
        \Log::info('🔄 Update photo - hasFile: ' . ($request->hasFile('profile_photo') ? 'OUI' : 'NON'));

        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            \Log::info('🔄 Update photo - Taille: ' . $photo->getSize() . ' bytes');
            \Log::info('🔄 Update photo - MIME: ' . $photo->getMimeType());

            // IMPORTANT: Récupérer les données brutes sans passer par l'accessor
            $currentPersonalInfo = json_decode($resume->getAttributes()['personal_info'] ?? '[]', true);

            \Log::info('🔄 Update photo - Ancienne photo: ' . ($currentPersonalInfo['photo'] ?? 'AUCUNE'));

            // Supprimer l'ancienne photo si elle existe
            if (isset($currentPersonalInfo['photo']) && \Storage::disk('public')->exists($currentPersonalInfo['photo'])) {
                \Storage::disk('public')->delete($currentPersonalInfo['photo']);
                \Log::info('🔄 Update photo - Ancienne photo supprimée');
            }

            // Uploader la nouvelle photo
            $photoPath = $photo->store('resumes/photos', 'public');
            \Log::info('🔄 Update photo - Nouvelle photo sauvegardée: ' . $photoPath);

            // Mettre à jour personal_info avec la nouvelle photo
            if (isset($data['personal_info'])) {
                $data['personal_info']['photo'] = $photoPath;
            } else {
                $currentPersonalInfo['photo'] = $photoPath;
                $data['personal_info'] = $currentPersonalInfo;
            }

            \Log::info('🔄 Update photo - Personal info après ajout: ' . json_encode($data['personal_info']));
        } else {
            \Log::info('🔄 Aucune nouvelle photo dans cette mise à jour');
        }

        $resume->update(array_filter([
            'title' => $data['title'] ?? null,
            'template_type' => $data['template_type'] ?? null,
            'personal_info' => $data['personal_info'] ?? null,
            'professional_summary' => $data['professional_summary'] ?? null,
            'experiences' => $data['experiences'] ?? null,
            'education' => $data['education'] ?? null,
            'skills' => $data['skills'] ?? null,
            'certifications' => $data['certifications'] ?? null,
            'projects' => $data['projects'] ?? null,
            'references' => $data['references'] ?? null,
            'hobbies' => $data['hobbies'] ?? null,
            'customization' => $data['customization'] ?? null,
            'is_public' => $data['is_public'] ?? null,
            'is_default' => $data['is_default'] ?? null,
        ], function ($value) {
            return $value !== null;
        }));

        // Recharger pour avoir les nouvelles données
        $resume->refresh();
        \Log::info('✅ CV mis à jour - ID: ' . $resume->id);
        // Récupérer les données brutes pour le log
        $rawPersonalInfo = json_decode($resume->getAttributes()['personal_info'] ?? '[]', true);
        \Log::info('✅ CV mis à jour - Personal info sauvegardé (brut): ' . json_encode($rawPersonalInfo));

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
