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
}
