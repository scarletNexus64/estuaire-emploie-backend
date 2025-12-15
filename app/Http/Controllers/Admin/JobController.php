<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use App\Models\Category;
use App\Models\Location;
use App\Models\ContractType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(Request $request): View
    {
        $query = Job::with(['company', 'category', 'location'])
            ->withCount('applications');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $jobs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        $companies = Company::where('status', 'verified')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $contractTypes = ContractType::orderBy('name')->get();

        return view('admin.jobs.create', compact('companies', 'categories', 'locations', 'contractTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'contract_type_id' => 'required|exists:contract_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
            'salary_negotiable' => 'boolean',
            'experience_level' => 'nullable|string|max:50',
            'status' => 'required|in:draft,pending,published,closed,expired',
            'is_featured' => 'boolean',
            'application_deadline' => 'nullable|date|after:today',
        ]);

        $validated['posted_by'] = auth()->id();
        $validated['salary_negotiable'] = $request->has('salary_negotiable');
        $validated['is_featured'] = $request->has('is_featured');

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Job::create($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Offre créée avec succès');
    }

    public function show(Job $job): View
    {
        $job->load(['company', 'category', 'location', 'contractType', 'postedBy', 'applications.user']);

        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job): View
    {
        $companies = Company::where('status', 'verified')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $contractTypes = ContractType::orderBy('name')->get();

        return view('admin.jobs.edit', compact('job', 'companies', 'categories', 'locations', 'contractTypes'));
    }

    public function update(Request $request, Job $job): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'contract_type_id' => 'required|exists:contract_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
            'salary_negotiable' => 'boolean',
            'experience_level' => 'nullable|string|max:50',
            'status' => 'required|in:draft,pending,published,closed,expired',
            'is_featured' => 'boolean',
            'application_deadline' => 'nullable|date',
        ]);

        $validated['salary_negotiable'] = $request->has('salary_negotiable');
        $validated['is_featured'] = $request->has('is_featured');

        if ($validated['status'] === 'published' && !$job->published_at) {
            $validated['published_at'] = now();
        }

        $job->update($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Offre mise à jour avec succès');
    }

    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Offre supprimée avec succès');
    }

    public function publish(Job $job): RedirectResponse
    {
        $job->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Offre publiée avec succès');
    }

    public function feature(Job $job): RedirectResponse
    {
        $job->update([
            'is_featured' => !$job->is_featured,
        ]);

        $message = $job->is_featured
            ? 'Offre mise en avant avec succès'
            : 'Offre retirée de la mise en avant';

        return redirect()->back()
            ->with('success', $message);
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->input('ids'), true);

            if (!is_array($ids) || empty($ids)) {
                return redirect()->back()->with('error', 'Aucun élément sélectionné');
            }

            $count = Job::whereIn('id', $ids)->delete();

            return redirect()->back()->with('success', "$count élément(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}
