<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(): View
    {
        $sections = Section::orderBy('order')->get();

        return view('admin.sections.index', compact('sections'));
    }

    public function create(): View
    {
        return view('admin.sections.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        Section::create($validated);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section créée avec succès');
    }

    public function show(Section $section): View
    {
        return view('admin.sections.show', compact('section'));
    }

    public function edit(Section $section): View
    {
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, Section $section): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $section->update($validated);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section modifiée avec succès');
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section supprimée avec succès');
    }
}
