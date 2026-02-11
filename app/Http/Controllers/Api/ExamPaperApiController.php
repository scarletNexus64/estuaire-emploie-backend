<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamPaper;
use App\Models\UserPremiumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExamPaperApiController extends Controller
{
    /**
     * Vérifie si l'utilisateur a accès au Mode Étudiant
     */
    private function hasStudentMode(): bool
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur a le service "student_mode" actif
        return UserPremiumService::where('user_id', $user->id)
            ->whereHas('config', function ($query) {
                $query->where('slug', 'student_mode');
            })
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->exists();
    }

    /**
     * Liste des épreuves disponibles
     * GET /api/exam-papers
     */
    public function index(Request $request)
    {
        // Vérifier l'accès
        if (!$this->hasStudentMode()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Vous devez activer le Mode Étudiant pour accéder aux épreuves.',
                'requires_student_mode' => true,
            ], 403);
        }

        $query = ExamPaper::active();

        // Filtres
        if ($request->filled('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('is_correction')) {
            $query->where('is_correction', $request->is_correction);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $examPapers = $query->orderBy('display_order')
                           ->orderBy('created_at', 'desc')
                           ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $examPapers->items(),
            'pagination' => [
                'current_page' => $examPapers->currentPage(),
                'last_page' => $examPapers->lastPage(),
                'per_page' => $examPapers->perPage(),
                'total' => $examPapers->total(),
            ],
        ]);
    }

    /**
     * Récupérer les filtres disponibles (spécialités, matières, niveaux)
     * GET /api/exam-papers/filters
     */
    public function filters()
    {
        // Vérifier l'accès
        if (!$this->hasStudentMode()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Vous devez activer le Mode Étudiant.',
                'requires_student_mode' => true,
            ], 403);
        }

        // Récupérer les spécialités disponibles dans les épreuves actives
        $specialties = ExamPaper::active()
            ->select('specialty')
            ->distinct()
            ->pluck('specialty')
            ->toArray();

        // Récupérer les matières disponibles
        $subjects = ExamPaper::active()
            ->select('subject')
            ->distinct()
            ->pluck('subject')
            ->toArray();

        // Récupérer les années disponibles
        $years = ExamPaper::active()
            ->whereNotNull('year')
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'specialties' => $specialties,
                'subjects' => $subjects,
                'levels' => ExamPaper::getLevels(),
                'years' => $years,
            ],
        ]);
    }

    /**
     * Détails d'une épreuve
     * GET /api/exam-papers/{id}
     */
    public function show($id)
    {
        // Vérifier l'accès
        if (!$this->hasStudentMode()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Vous devez activer le Mode Étudiant.',
                'requires_student_mode' => true,
            ], 403);
        }

        $examPaper = ExamPaper::active()->find($id);

        if (!$examPaper) {
            return response()->json([
                'success' => false,
                'message' => 'Épreuve introuvable',
            ], 404);
        }

        // Incrémenter le compteur de vues
        $examPaper->incrementViews();

        return response()->json([
            'success' => true,
            'data' => $examPaper,
        ]);
    }

    /**
     * Télécharger une épreuve
     * GET /api/exam-papers/{id}/download
     */
    public function download($id)
    {
        // Vérifier l'accès
        if (!$this->hasStudentMode()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Vous devez activer le Mode Étudiant.',
                'requires_student_mode' => true,
            ], 403);
        }

        $examPaper = ExamPaper::active()->find($id);

        if (!$examPaper) {
            return response()->json([
                'success' => false,
                'message' => 'Épreuve introuvable',
            ], 404);
        }

        if (!$examPaper->fileExists()) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier introuvable',
            ], 404);
        }

        // Incrémenter le compteur de téléchargements
        $examPaper->incrementDownloads();

        return Storage::disk('public')->download($examPaper->file_path, $examPaper->file_name);
    }

    /**
     * Obtenir l'URL du PDF pour visualisation
     * GET /api/exam-papers/{id}/view
     */
    public function viewPdf($id)
    {
        // Vérifier l'accès
        if (!$this->hasStudentMode()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Vous devez activer le Mode Étudiant.',
                'requires_student_mode' => true,
            ], 403);
        }

        $examPaper = ExamPaper::active()->find($id);

        if (!$examPaper) {
            return response()->json([
                'success' => false,
                'message' => 'Épreuve introuvable',
            ], 404);
        }

        if (!$examPaper->fileExists()) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier introuvable',
            ], 404);
        }

        // Incrémenter le compteur de vues
        $examPaper->incrementViews();

        // Retourner l'URL du fichier
        return response()->json([
            'success' => true,
            'data' => [
                'file_url' => asset('storage/' . $examPaper->file_path),
                'file_name' => $examPaper->file_name,
                'file_size' => $examPaper->file_size,
            ],
        ]);
    }

    /**
     * Statistiques des épreuves
     * GET /api/exam-papers/stats
     */
    public function stats()
    {
        // Vérifier l'accès
        if (!$this->hasStudentMode()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Vous devez activer le Mode Étudiant.',
                'requires_student_mode' => true,
            ], 403);
        }

        $totalPapers = ExamPaper::active()->count();
        $totalSubjects = ExamPaper::active()->where('is_correction', false)->count();
        $totalCorrections = ExamPaper::active()->where('is_correction', true)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_papers' => $totalPapers,
                'total_subjects' => $totalSubjects,
                'total_corrections' => $totalCorrections,
            ],
        ]);
    }
}
