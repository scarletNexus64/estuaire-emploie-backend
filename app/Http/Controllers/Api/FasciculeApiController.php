<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamPaper;
use App\Models\Fascicule;
use App\Support\FasciculeCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * Fascicules (supports de cours, banques d'épreuves, annales) — espace étudiant.
 *
 * La consultation du catalogue est libre pour tout utilisateur connecté
 * (paywall côté app) ; seul le fichier PDF est réservé au Pack Étudiant
 * (service premium "student_mode", même règle que l'Épreuvethèque).
 */
class FasciculeApiController extends Controller
{
    /**
     * Même vérification que l'Épreuvethèque / Bibliothèque (User::hasStudentMode()).
     */
    private function hasStudentMode(): bool
    {
        $user = Auth::user();

        return $user ? $user->hasStudentMode() : false;
    }

    private function transform(Fascicule $fascicule, bool $hasAccess): array
    {
        return [
            'id' => $fascicule->id,
            'title' => $fascicule->title,
            'description' => $fascicule->description,
            'domain' => $fascicule->domain,
            'filiere_code' => $fascicule->filiere_code,
            'kind' => $fascicule->kind,
            'file_type' => $fascicule->file_type ?: Fascicule::TYPE_PDF,
            'filiere' => $fascicule->filiere,
            'level' => $fascicule->level,
            'subject' => $fascicule->subject,
            'author' => $fascicule->author,
            'pages_count' => $fascicule->pages_count,
            'file_size' => $fascicule->file_size,
            'cover_url' => $fascicule->cover_url,
            'is_new' => $fascicule->is_new,
            'published_at' => $fascicule->published_at?->toIso8601String(),
            'file_url' => $hasAccess ? $this->signedFileUrl($fascicule) : null,
        ];
    }

    /**
     * Lien temporaire (2 h) vers le PDF : le lecteur PDF de l'app ne peut pas
     * envoyer de jeton Bearer. Signature relative (chemin seul) pour rester
     * valide derrière un proxy http/https.
     */
    private function signedFileUrl(Fascicule $fascicule): string
    {
        return url(URL::temporarySignedRoute(
            'fascicules.stream',
            now()->addHours(2),
            ['id' => $fascicule->id],
            false
        ));
    }

    /**
     * GET /api/fascicules
     */
    public function index(Request $request)
    {
        $hasAccess = $this->hasStudentMode();

        $query = Fascicule::published();

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('filiere')) {
            $query->where('filiere', $request->filiere);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 20);
        $perPage = max(1, min($perPage, 50));

        $fascicules = $query->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($perPage);

        $levels = Fascicule::published()
            ->whereNotNull('level')->where('level', '!=', '')
            ->distinct()->orderBy('level')->pluck('level')->values();

        $filieres = Fascicule::published()
            ->whereNotNull('filiere')->where('filiere', '!=', '')
            ->distinct()->orderBy('filiere')->pluck('filiere')->values();

        return response()->json([
            'success' => true,
            'has_access' => $hasAccess,
            'data' => collect($fascicules->items())
                ->map(fn (Fascicule $f) => $this->transform($f, $hasAccess))
                ->values(),
            'meta' => [
                'current_page' => $fascicules->currentPage(),
                'last_page' => $fascicules->lastPage(),
                'per_page' => $fascicules->perPage(),
                'total' => $fascicules->total(),
            ],
            'filters' => [
                'levels' => $levels,
                'filieres' => $filieres,
            ],
        ]);
    }

    /**
     * GET /api/fascicules/{id}
     */
    public function show($id)
    {
        $fascicule = Fascicule::published()->find($id);

        if (!$fascicule) {
            return response()->json([
                'success' => false,
                'message' => __('fascicule.not_found'),
            ], 404);
        }

        $hasAccess = $this->hasStudentMode();

        return response()->json([
            'success' => true,
            'has_access' => $hasAccess,
            'data' => $this->transform($fascicule, $hasAccess),
        ]);
    }

    /**
     * Fichier PDF (stream authentifié, comme GET /api/exam-papers/{id}/download).
     * GET /api/fascicules/{id}/file
     */
    public function file($id)
    {
        $fascicule = Fascicule::published()->find($id);

        if (!$fascicule) {
            return response()->json([
                'success' => false,
                'message' => __('fascicule.not_found'),
            ], 404);
        }

        if (!$this->hasStudentMode()) {
            return response()->json([
                'success' => false,
                'requires_student_pack' => true,
                'requires_student_mode' => true,
                'message' => __('fascicule.student_pack_required'),
            ], 403);
        }

        if (!$fascicule->fileExists()) {
            return response()->json([
                'success' => false,
                'message' => __('fascicule.file_not_found'),
            ], 404);
        }

        $fascicule->incrementDownloads();

        return Storage::disk(Fascicule::FILE_DISK)->response(
            $fascicule->file_path,
            $fascicule->downloadName(),
            ['Content-Type' => $fascicule->mimeType()]
        );
    }

    /**
     * PDF via lien signé (généré seulement pour un utilisateur ayant le Pack Étudiant).
     * GET /api/fascicules/{id}/stream?expires=...&signature=...
     */
    public function stream(Request $request, $id)
    {
        if (!$request->hasValidRelativeSignature()) {
            return response()->json([
                'success' => false,
                'message' => __('fascicule.not_found'),
            ], 403);
        }

        $fascicule = Fascicule::published()->find($id);

        if (!$fascicule || !$fascicule->fileExists()) {
            return response()->json([
                'success' => false,
                'message' => __('fascicule.file_not_found'),
            ], 404);
        }

        $fascicule->incrementDownloads();

        return Storage::disk(Fascicule::FILE_DISK)->response(
            $fascicule->file_path,
            $fascicule->downloadName(),
            ['Content-Type' => $fascicule->mimeType()]
        );
    }
}
