<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Http\Request;

class CVLibraryController extends Controller
{
    public function index(Request $request)
    {
        $query = Resume::with(['user'])
            ->where('customization->source', 'INSAM_IMPORT');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('specialty')) {
            $query->where('customization->specialty', 'like', "%{$request->specialty}%");
        }

        if ($request->filled('level')) {
            $query->where('customization->level', 'like', "%{$request->level}%");
        }

        $resumes = $query->latest()->paginate(20);

        return view('admin.cv-library.index', compact('resumes'));
    }

    public function show($id)
    {
        $resume = Resume::with('user')->findOrFail($id);
        return view('admin.cv-library.show', compact('resume'));
    }
}
