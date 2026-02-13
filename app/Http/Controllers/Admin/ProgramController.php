<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProgramController extends Controller
{
    /**
     * Display a listing of the programs.
     */
    public function index(): View
    {
        $programs = Program::withCount('steps')->orderBy('order')->get();

        return view('admin.programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a new program.
     */
    public function create(): View
    {
        return view('admin.programs.create');
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:immersion_professionnelle,entreprenariat,transformation_professionnelle',
            'description' => 'required|string',
            'objectives' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
            'duration_weeks' => 'nullable|integer|min:1',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'required_packs' => 'nullable|array',
            'required_packs.*' => 'in:C1,C2,C3',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');
        $validated['icon'] = $validated['icon'] ?? '📚';
        $validated['required_packs'] = $request->input('required_packs', []);

        $program = Program::create($validated);

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Programme créé avec succès');
    }

    /**
     * Display the specified program.
     */
    public function show(Program $program): View
    {
        $program->load('steps');

        return view('admin.programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified program.
     */
    public function edit(Program $program): View
    {
        return view('admin.programs.edit', compact('program'));
    }

    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:immersion_professionnelle,entreprenariat,transformation_professionnelle',
            'description' => 'required|string',
            'objectives' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
            'duration_weeks' => 'nullable|integer|min:1',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'required_packs' => 'nullable|array',
            'required_packs.*' => 'in:C1,C2,C3',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');
        $validated['required_packs'] = $request->input('required_packs', []);

        $program->update($validated);

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Programme modifié avec succès');
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy(Program $program): RedirectResponse
    {
        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Programme supprimé avec succès');
    }

    /**
     * Show the form for managing program steps.
     */
    public function manageSteps(Program $program): View
    {
        $program->load('steps');

        return view('admin.programs.manage-steps', compact('program'));
    }

    /**
     * Get a specific step for editing (AJAX).
     */
    public function getStep(Program $program, ProgramStep $step)
    {
        \Log::info('getStep called', [
            'program_id' => $program->id,
            'step_id' => $step->id,
            'user' => auth()->user()?->id,
        ]);

        if ($step->program_id !== $program->id) {
            \Log::warning('Step does not belong to program', [
                'step_program_id' => $step->program_id,
                'requested_program_id' => $program->id,
            ]);
            abort(404);
        }

        return response()->json($step);
    }

    /**
     * Store a new step for the program.
     */
    public function storeStep(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'resources' => 'nullable|array',
            'resources.*.title' => 'required|string|max:255',
            'resources.*.url' => 'required|url',
            'resources.*.type' => 'required|in:document,video,link,article',
            'order' => 'nullable|integer|min:0',
            'estimated_duration_days' => 'nullable|integer|min:1',
            'is_required' => 'nullable|boolean',
        ]);

        $validated['program_id'] = $program->id;
        $validated['is_required'] = $request->has('is_required') && $request->input('is_required') == '1';
        $validated['order'] = $validated['order'] ?? $program->steps()->max('order') + 1;

        ProgramStep::create($validated);

        return redirect()->route('admin.programs.manage-steps', $program)
            ->with('success', 'Étape ajoutée avec succès');
    }

    /**
     * Update the specified step.
     */
    public function updateStep(Request $request, Program $program, ProgramStep $step): RedirectResponse
    {
        if ($step->program_id !== $program->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'resources' => 'nullable|array',
            'resources.*.title' => 'required|string|max:255',
            'resources.*.url' => 'required|url',
            'resources.*.type' => 'required|in:document,video,link,article',
            'order' => 'nullable|integer|min:0',
            'estimated_duration_days' => 'nullable|integer|min:1',
            'is_required' => 'nullable|boolean',
        ]);

        $validated['is_required'] = $request->has('is_required') && $request->input('is_required') == '1';

        $step->update($validated);

        return redirect()->route('admin.programs.manage-steps', $program)
            ->with('success', 'Étape modifiée avec succès');
    }

    /**
     * Remove the specified step from storage.
     */
    public function destroyStep(Program $program, ProgramStep $step): RedirectResponse
    {
        if ($step->program_id !== $program->id) {
            abort(404);
        }

        $step->delete();

        return redirect()->route('admin.programs.manage-steps', $program)
            ->with('success', 'Étape supprimée avec succès');
    }
}
