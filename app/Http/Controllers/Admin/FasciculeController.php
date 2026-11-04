<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fascicule;
use App\Support\FasciculeCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FasciculeController extends Controller
{
    /**
     * Liste des fascicules
     */
    public function index(Request $request)
    {
        $query = Fascicule::query();

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('filiere')) {
            $query->where('filiere', $request->filiere);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published);
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

        $fascicules = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.fascicules.index', array_merge(
            compact('fascicules'),
            $this->suggestions()
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.fascicules.form', $this->suggestions());
    }

    /**
     * Enregistrer un nouveau fascicule
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request, true);

        $validated['file_path'] = $this->storePdf($request);
        $validated['file_type'] = $this->uploadedType($request);
        $validated['file_size'] = $request->file('file')->getSize();
        $validated = $this->withClassification($validated);

        if ($request->hasFile('cover')) {
            $validated['cover_path'] = $this->storeCover($request);
        }

        $validated['is_published'] = $request->has('is_published');
        unset($validated['file'], $validated['cover']);

        Fascicule::create($validated);

        return redirect()->route('admin.fascicules.index')
                        ->with('success', 'Fascicule ajouté avec succès');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Fascicule $fascicule)
    {
        return view('admin.fascicules.form', array_merge(
            compact('fascicule'),
            $this->suggestions()
        ));
    }

    /**
     * Mettre à jour un fascicule
     */
    public function update(Request $request, Fascicule $fascicule)
    {
        $validated = $this->validateRequest($request, false);

        if ($request->hasFile('file')) {
            if ($fascicule->file_path && Storage::disk(Fascicule::FILE_DISK)->exists($fascicule->file_path)) {
                Storage::disk(Fascicule::FILE_DISK)->delete($fascicule->file_path);
            }
            $validated['file_path'] = $this->storePdf($request);
            $validated['file_type'] = $this->uploadedType($request);
            $validated['file_size'] = $request->file('file')->getSize();
        }
        $validated = $this->withClassification($validated);

        if ($request->hasFile('cover')) {
            $this->deletePublicFile($fascicule->cover_path);
            $validated['cover_path'] = $this->storeCover($request);
        } elseif ($request->boolean('remove_cover')) {
            $this->deletePublicFile($fascicule->cover_path);
            $validated['cover_path'] = null;
        }

        $validated['is_published'] = $request->has('is_published');
        unset($validated['file'], $validated['cover'], $validated['remove_cover']);

        $fascicule->update($validated);

        return redirect()->route('admin.fascicules.index')
                        ->with('success', 'Fascicule mis à jour avec succès');
    }

    /**
     * Supprimer un fascicule
     */
    public function destroy(Fascicule $fascicule)
    {
        $fascicule->delete();

        return redirect()->route('admin.fascicules.index')
                        ->with('success', 'Fascicule supprimé avec succès');
    }

    /**
     * Publier / dépublier un fascicule
     */
    public function toggle(Fascicule $fascicule)
    {
        $fascicule->update(['is_published' => !$fascicule->is_published]);

        $status = $fascicule->is_published ? 'publié' : 'dépublié';

        return redirect()->back()
                        ->with('success', "Fascicule {$status} avec succès");
    }

    /**
     * Télécharger le PDF (admin)
     */
    public function download(Fascicule $fascicule)
    {
        if (!$fascicule->fileExists()) {
            abort(404, 'Fichier introuvable');
        }

        return Storage::disk(Fascicule::FILE_DISK)->download($fascicule->file_path, $fascicule->downloadName());
    }

    private function validateRequest(Request $request, bool $creating): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'filiere' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'kind' => 'nullable|in:' . implode(',', [FasciculeCatalog::KIND_SUPPORT, FasciculeCatalog::KIND_BANQUE, FasciculeCatalog::KIND_OTHER]),
            'filiere_code' => 'nullable|in:' . implode(',', array_keys(FasciculeCatalog::FILIERES)),
            'domain' => 'nullable|in:' . implode(',', array_keys(FasciculeCatalog::DOMAINS)),
            'pages_count' => 'nullable|integer|min:1|max:100000',
            'file' => ($creating ? 'required' : 'nullable') . '|file|mimes:pdf,docx|max:51200', // PDF ou Word, 50 Mo max
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'remove_cover' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);
    }

    private function storePdf(Request $request): string
    {
        $file = $request->file('file');
        $extension = $this->uploadedType($request) === Fascicule::TYPE_DOCX ? 'docx' : 'pdf';
        $uniqueName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;

        return $file->storeAs('fascicules', $uniqueName, Fascicule::FILE_DISK);
    }

    private function storeCover(Request $request): string
    {
        return $request->file('cover')->store('fascicules/covers', 'public');
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Valeurs déjà utilisées (pour les datalists des formulaires / filtres).
     */
    private function suggestions(): array
    {
        $distinct = fn (string $column) => Fascicule::whereNotNull($column)
            ->where($column, '!=', '')
            ->distinct()
            ->orderBy($column)
            ->pluck($column);

        return [
            'levels' => $distinct('level'),
            'filieres' => $distinct('filiere'),
            'subjects' => $distinct('subject'),
        ];
    }

    private function uploadedType(Request $request): string
    {
        $extension = strtolower((string) $request->file('file')?->getClientOriginalExtension());

        return $extension === 'docx' ? Fascicule::TYPE_DOCX : Fascicule::TYPE_PDF;
    }

    /**
     * Type et dossier de rangement : le domaine suit la filière quand elle est choisie.
     */
    private function withClassification(array $validated): array
    {
        $validated['kind'] = $validated['kind'] ?? FasciculeCatalog::KIND_OTHER;
        $code = $validated['filiere_code'] ?? null;
        if (FasciculeCatalog::hasFiliere($code)) {
            $validated['domain'] = FasciculeCatalog::domainOf($code);
            $validated['filiere'] = $validated['filiere'] ?? FasciculeCatalog::filiereName($code);
        }

        return $validated;
    }
}
