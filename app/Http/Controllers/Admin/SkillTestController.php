<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecruiterSkillTest;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillTestController extends Controller
{
    public function index(Request $request): View
    {
        $query = RecruiterSkillTest::with(['company', 'job'])
            ->withCount('results');

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $tests = $query->latest()->paginate(20)->withQueryString();
        $companies = Company::select('id', 'name')->orderBy('name')->get();

        return view('admin.skill-tests.index', compact('tests', 'companies'));
    }

    public function create(Request $request): View
    {
        $companies = Company::orderBy('name')->get(['id', 'name']);
        $jobs = Job::with('company')->orderBy('created_at', 'desc')->get();

        $prefilledJob = null;
        $prefilledCompanyId = null;
        if ($request->filled('job_id')) {
            $prefilledJob = Job::with('company')->find($request->job_id);
            $prefilledCompanyId = $prefilledJob?->company_id;
        }

        return view('admin.skill-tests.create', compact(
            'companies', 'jobs', 'prefilledJob', 'prefilledCompanyId'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'job_id' => 'nullable|exists:jobs,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:5|max:180',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*' => 'required|string',
            'questions.*.correct_answer' => 'required|string',
        ]);

        // Si un job est lié, il doit appartenir à l'entreprise choisie
        if (!empty($validated['job_id'])) {
            $job = Job::find($validated['job_id']);
            if ($job && $job->company_id !== (int) $validated['company_id']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "L'offre sélectionnée n'appartient pas à cette entreprise.");
            }
        }

        // Normaliser les questions : ajouter type "multiple_choice" et trim
        $questions = collect($validated['questions'])->map(function ($q) {
            return [
                'question' => trim($q['question']),
                'type' => 'multiple_choice',
                'options' => array_values(array_map('trim', $q['options'])),
                'correct_answer' => trim($q['correct_answer']),
            ];
        })->values()->all();

        // Vérifier que la bonne réponse est bien dans les options
        foreach ($questions as $i => $q) {
            if (!in_array($q['correct_answer'], $q['options'], true)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Question " . ($i + 1) . " : la bonne réponse doit faire partie des options.");
            }
        }

        $test = RecruiterSkillTest::create([
            'company_id' => $validated['company_id'],
            'job_id' => $validated['job_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'questions' => $questions,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'passing_score' => $validated['passing_score'],
            // L'admin bypass le paiement : si "publier" coché, on active directement
            'is_active' => (bool) $request->boolean('is_active'),
        ]);

        $msg = $test->is_active
            ? 'Test créé et publié avec succès.'
            : 'Test créé en brouillon.';

        return redirect()->route('admin.skill-tests.show', $test)
            ->with('success', $msg);
    }

    public function show($id): View
    {
        $test = RecruiterSkillTest::with([
            'company',
            'job',
            'results.application.user',
            'results.application.job'
        ])->findOrFail($id);

        return view('admin.skill-tests.show', compact('test'));
    }

    public function edit($id): View
    {
        $test = RecruiterSkillTest::findOrFail($id);
        $companies = Company::orderBy('name')->get(['id', 'name']);
        $jobs = Job::with('company')->where('company_id', $test->company_id)->orderBy('created_at', 'desc')->get();

        return view('admin.skill-tests.edit', compact('test', 'companies', 'jobs'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $test = RecruiterSkillTest::findOrFail($id);

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'job_id' => 'nullable|exists:jobs,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:5|max:180',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*' => 'required|string',
            'questions.*.correct_answer' => 'required|string',
        ]);

        if (!empty($validated['job_id'])) {
            $job = Job::find($validated['job_id']);
            if ($job && $job->company_id !== (int) $validated['company_id']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "L'offre sélectionnée n'appartient pas à cette entreprise.");
            }
        }

        $questions = collect($validated['questions'])->map(function ($q) {
            return [
                'question' => trim($q['question']),
                'type' => 'multiple_choice',
                'options' => array_values(array_map('trim', $q['options'])),
                'correct_answer' => trim($q['correct_answer']),
            ];
        })->values()->all();

        foreach ($questions as $i => $q) {
            if (!in_array($q['correct_answer'], $q['options'], true)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Question " . ($i + 1) . " : la bonne réponse doit faire partie des options.");
            }
        }

        $test->update([
            'company_id' => $validated['company_id'],
            'job_id' => $validated['job_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'questions' => $questions,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'passing_score' => $validated['passing_score'],
            'is_active' => (bool) $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.skill-tests.show', $test)
            ->with('success', 'Test mis à jour avec succès.');
    }

    /**
     * Publier un test (activer is_active) — l'admin bypass le paiement,
     * contrairement au flux recruteur qui nécessite 2000 FCFA.
     */
    public function publish($id): RedirectResponse
    {
        $test = RecruiterSkillTest::findOrFail($id);

        if ($test->is_active) {
            return redirect()->back()->with('info', 'Ce test est déjà actif.');
        }

        $test->update(['is_active' => true]);

        return redirect()->back()->with('success', 'Test publié avec succès.');
    }

    public function destroy($id): RedirectResponse
    {
        $test = RecruiterSkillTest::findOrFail($id);
        $test->delete();

        return redirect()->route('admin.skill-tests.index')
            ->with('success', 'Test supprimé avec succès.');
    }
}
