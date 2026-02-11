<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecruiterSkillTest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillTestController extends Controller
{
    public function index(Request $request): View
    {
        $query = RecruiterSkillTest::with(['company', 'job', 'results'])
            ->withCount('results');

        // Filter by company
        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Filter by status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $tests = $query->latest()->paginate(20);
        $companies = Company::select('id', 'name')->get();

        return view('admin.skill-tests.index', compact('tests', 'companies'));
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

    public function destroy($id)
    {
        $test = RecruiterSkillTest::findOrFail($id);
        $test->delete();

        return redirect()
            ->route('admin.skill-tests.index')
            ->with('success', 'Test supprimé avec succès');
    }
}
