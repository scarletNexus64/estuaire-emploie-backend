<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExamPaperController extends Controller
{
    /**
     * Liste des épreuves
     */
    public function index(Request $request)
    {
        $query = ExamPaper::query();

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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $examPapers = $query->orderBy('display_order')
                           ->orderBy('created_at', 'desc')
                           ->paginate(20);

        $specialties = ExamPaper::getSpecialties();
        $subjects = ExamPaper::getSubjectsBySpecialty();
        $levels = ExamPaper::getLevels();

        return view('admin.exam-papers.index', compact(
            'examPapers',
            'specialties',
            'subjects',
            'levels'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $specialties = ExamPaper::getSpecialties();
        $subjects = ExamPaper::getSubjectsBySpecialty();
        $levels = ExamPaper::getLevels();

        return view('admin.exam-papers.form', compact('specialties', 'subjects', 'levels'));
    }

    /**
     * Enregistrer une nouvelle épreuve
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:5',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'is_correction' => 'boolean',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf|max:20480', // Max 20MB
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        // Upload du fichier PDF
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            // Générer un nom de fichier unique
            $uniqueName = time() . '_' . Str::slug(pathinfo($fileName, PATHINFO_FILENAME)) . '.pdf';
            $filePath = $file->storeAs('exam_papers', $uniqueName, 'public');

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $fileName;
            $validated['file_size'] = $fileSize;
        }

        $validated['is_correction'] = $request->has('is_correction');
        $validated['is_active'] = $request->has('is_active');

        ExamPaper::create($validated);

        return redirect()->route('admin.exam-papers.index')
                        ->with('success', 'Épreuve ajoutée avec succès');
    }

    /**
     * Afficher les détails d'une épreuve
     */
    public function show(ExamPaper $examPaper)
    {
        return view('admin.exam-papers.show', compact('examPaper'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(ExamPaper $examPaper)
    {
        $specialties = ExamPaper::getSpecialties();
        $subjects = ExamPaper::getSubjectsBySpecialty();
        $levels = ExamPaper::getLevels();

        return view('admin.exam-papers.form', compact('examPaper', 'specialties', 'subjects', 'levels'));
    }

    /**
     * Mettre à jour une épreuve
     */
    public function update(Request $request, ExamPaper $examPaper)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:5',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'is_correction' => 'boolean',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:20480', // Max 20MB
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        // Upload d'un nouveau fichier si fourni
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            $examPaper->deleteFile();

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            // Générer un nom de fichier unique
            $uniqueName = time() . '_' . Str::slug(pathinfo($fileName, PATHINFO_FILENAME)) . '.pdf';
            $filePath = $file->storeAs('exam_papers', $uniqueName, 'public');

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $fileName;
            $validated['file_size'] = $fileSize;
        }

        $validated['is_correction'] = $request->has('is_correction');
        $validated['is_active'] = $request->has('is_active');

        $examPaper->update($validated);

        return redirect()->route('admin.exam-papers.index')
                        ->with('success', 'Épreuve mise à jour avec succès');
    }

    /**
     * Supprimer une épreuve
     */
    public function destroy(ExamPaper $examPaper)
    {
        $examPaper->delete();

        return redirect()->route('admin.exam-papers.index')
                        ->with('success', 'Épreuve supprimée avec succès');
    }

    /**
     * Activer/Désactiver une épreuve
     */
    public function toggle(ExamPaper $examPaper)
    {
        $examPaper->update(['is_active' => !$examPaper->is_active]);

        $status = $examPaper->is_active ? 'activée' : 'désactivée';

        return redirect()->back()
                        ->with('success', "Épreuve {$status} avec succès");
    }

    /**
     * Télécharger une épreuve
     */
    public function download(ExamPaper $examPaper)
    {
        if (!$examPaper->fileExists()) {
            abort(404, 'Fichier introuvable');
        }

        $examPaper->incrementDownloads();

        return Storage::disk('public')->download($examPaper->file_path, $examPaper->file_name);
    }
}
