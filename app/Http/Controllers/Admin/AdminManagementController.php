<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    public function index(): View
    {
        $admins = User::where('role', 'admin')
            ->withCount(['postedJobs', 'applications'])
            ->latest()
            ->paginate(20);

        return view('admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        $permissionsByCategory = \App\Services\NavigationService::getPermissionsByCategory();

        return view('admin.admins.create', compact('permissionsByCategory'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'permissions' => 'nullable|array',
            'is_super_admin' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => 'admin',
            'permissions' => $validated['permissions'] ?? [],
            'is_active' => true,
        ];

        // Only super admins can create other super admins
        if (auth()->user()->isSuperAdmin() && $request->has('is_super_admin')) {
            $data['is_super_admin'] = (bool) $request->input('is_super_admin');
        } else {
            $data['is_super_admin'] = false;
        }

        $user = User::create($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrateur créé avec succès');
    }

    public function show(User $user): View
    {
        $user->load(['postedJobs', 'applications']);

        return view('admin.admins.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $permissionsByCategory = \App\Services\NavigationService::getPermissionsByCategory();

        return view('admin.admins.edit', compact('user', 'permissionsByCategory'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'permissions' => 'nullable|array',
            'is_active' => 'boolean',
            'is_super_admin' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'permissions' => $validated['permissions'] ?? [],
            'is_active' => $request->has('is_active'),
        ];

        // Only super admins can modify super admin status
        if (auth()->user()->isSuperAdmin()) {
            $data['is_super_admin'] = (bool) $request->input('is_super_admin', false);
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrateur modifié avec succès');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting super admin
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Impossible de supprimer le super administrateur');
        }

        $user->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrateur supprimé avec succès');
    }

    public function updatePermissions(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
        ]);

        $user->update([
            'permissions' => $validated['permissions'] ?? [],
        ]);

        return back()->with('success', 'Permissions mises à jour avec succès');
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->input('ids'), true);

            if (!is_array($ids) || empty($ids)) {
                return redirect()->back()->with('error', 'Aucun élément sélectionné');
            }

            $count = User::whereIn('id', $ids)->delete();

            return redirect()->back()->with('success', "$count élément(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}
