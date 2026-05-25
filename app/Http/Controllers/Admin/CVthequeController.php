<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Request;

class CVthequeController extends Controller
{
    public function index(Request $request)
    {
        $query = Resume::with(['user' => function($q) {
            $q->select('id', 'name', 'email', 'phone', 'profile_photo', 'created_at');
        }]);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('title', 'like', "%{$search}%");
        }

        if ($request->filled('template_type')) {
            $query->where('template_type', $request->template_type);
        }

        if ($request->filled('is_public')) {
            $query->where('is_public', $request->is_public === '1');
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $resumes = $query->paginate(20);
        $templates = Resume::getAvailableTemplates();

        return view('admin.monetization.cvtheque.index', compact('resumes', 'templates'));
    }

    public function show($userId)
    {
        $user = User::with('resumes')->findOrFail($userId);

        return view('admin.monetization.cvtheque.show', compact('user'));
    }

    public function export()
    {
        return redirect()->route('admin.cvtheque.index')
            ->with('success', 'Export en cours...');
    }

    /**
     * Prévisualiser un CV
     */
    public function preview($id)
    {
        $resume = Resume::with('user')->findOrFail($id);

        return view('admin.monetization.cvtheque.preview', compact('resume'));
    }

    /**
     * Afficher le formulaire d'édition d'un CV
     */
    public function edit($id)
    {
        $resume = Resume::with('user')->findOrFail($id);

        return view('admin.monetization.cvtheque.edit', compact('resume'));
    }

    /**
     * Mettre à jour un CV
     */
    public function update(Request $request, $id)
    {
        $resume = Resume::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'template_type' => 'nullable|string',
            'professional_summary' => 'nullable|string',
            'personal_info' => 'nullable|array',
            'personal_info.name' => 'nullable|string|max:255',
            'personal_info.email' => 'nullable|email|max:255',
            'personal_info.phone' => 'nullable|string|max:50',
            'personal_info.address' => 'nullable|string|max:500',
            'is_public' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        // Préparer les données à mettre à jour
        $updateData = [
            'title' => $validated['title'],
            'template_type' => $validated['template_type'] ?? $resume->template_type,
            'professional_summary' => $validated['professional_summary'] ?? $resume->professional_summary,
            'is_public' => $request->has('is_public'),
        ];

        // Fusionner les informations personnelles existantes avec les nouvelles
        if (isset($validated['personal_info'])) {
            $currentPersonalInfo = $resume->personal_info ?? [];
            $updateData['personal_info'] = array_merge($currentPersonalInfo, array_filter($validated['personal_info']));
        }

        // Gérer le CV par défaut
        if ($request->has('is_default')) {
            $resume->setAsDefault();
        } else {
            $updateData['is_default'] = false;
        }

        $resume->update($updateData);

        return redirect()->route('admin.cvtheque.index')
            ->with('success', 'CV mis à jour avec succès');
    }

    /**
     * Supprimer un CV
     */
    public function destroy($id)
    {
        $resume = Resume::findOrFail($id);

        // Supprimer le fichier PDF si existant
        if ($resume->pdf_path) {
            $pdfFullPath = storage_path('app/public/' . $resume->pdf_path);
            if (file_exists($pdfFullPath)) {
                unlink($pdfFullPath);
            }
        }

        $resume->delete();

        return redirect()->route('admin.cvtheque.index')
            ->with('success', 'CV supprimé avec succès');
    }
}
