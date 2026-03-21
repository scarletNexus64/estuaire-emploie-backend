<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter (for candidates)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Experience level filter (for candidates)
        if ($request->filled('experience')) {
            $query->where('experience_level', $request->experience);
        }

        // Get users with counts
        $users = $query->withCount('applications')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Get statistics
        $stats = [
            'total' => User::count(),
            'candidates' => User::where('role', 'candidate')->count(),
            'recruiters' => User::where('role', 'recruiter')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'active_today' => User::whereDate('last_login_at', today())->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user): View
    {
        $user->load(['applications.job']);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)->whereNull('deleted_at')
            ],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:candidate,recruiter,admin',
            'status' => 'nullable|in:active,looking,employed',
            'experience_level' => 'nullable|in:junior,intermediate,senior',
            'bio' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'skills' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'name',
            'email',
            'phone',
            'role',
            'status',
            'experience_level',
            'bio',
            'location',
            'skills'
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && \Storage::exists('public/' . $user->profile_photo)) {
                \Storage::delete('public/' . $user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer un administrateur');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }

    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user(); // utilisateur connecté

        \Log::info('📲 [SEND-FCM-TOKEN] Réception du FCM token', [
            'user_id' => $user->id,
            'fcm_token' => $request->fcm_token
        ]);

        try {
            $user->fcm_token = $request->fcm_token;
            $user->save();

            \Log::info('✅ [SEND-FCM-TOKEN] FCM token sauvegardé avec succès', [
                'user_id' => $user->id
            ]);

            return response()->json([
                'message' => 'FCM token sauvegardé avec succès',
            ]);
        } catch (\Throwable $e) {
            \Log::error('❌ [SEND-FCM-TOKEN] Erreur lors de la sauvegarde', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Erreur lors de la sauvegarde du token',
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->input('ids'), true);

            if (!is_array($ids) || empty($ids)) {
                return redirect()->back()->with('error', 'Aucun élément sélectionné');
            }

            // Exclure l'admin connecté et les autres admins de la suppression
            $currentUserId = auth()->id();
            $ids = array_filter($ids, fn($id) => (int) $id !== $currentUserId);

            if (empty($ids)) {
                return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
            }

            $count = User::whereIn('id', $ids)
                ->where('role', '!=', 'admin')
                ->delete();

            return redirect()->back()->with('success', "$count élément(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}
