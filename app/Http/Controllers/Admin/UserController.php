<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackPurchase;
use App\Models\User;
use App\Models\UserAddonService;
use App\Models\UserPremiumService;
use App\Models\UserStoragePack;
use App\Models\UserSubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $user->load([
            'applications.job.company',
            'userSubscriptionPlans.subscriptionPlan',
            'userSubscriptionPlans.payment',
            'premiumServices.config',
            'premiumServices.payment',
            'postedJobs',
            'companies',
            'referrer',
            'referrals',
        ]);

        $subscriptionPlans = $user->userSubscriptionPlans->sortByDesc('created_at');

        $premiumServices = $user->premiumServices->sortByDesc('created_at');

        $addonServices = UserAddonService::with(['addonServiceConfig', 'payment', 'relatedJob', 'relatedUser'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $storagePacks = UserStoragePack::with('storagePack')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $packPurchases = PackPurchase::with(['examPack', 'trainingPack'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('admin.users.show', compact(
            'user',
            'subscriptionPlans',
            'premiumServices',
            'addonServices',
            'storagePacks',
            'packPurchases'
        ));
    }

    /**
     * Désactive (soft) un abonnement (UserSubscriptionPlan).
     * expires_at = maintenant pour le rendre invalide immédiatement.
     */
    public function revokeSubscription(User $user, UserSubscriptionPlan $subscription): RedirectResponse
    {
        if ($subscription->user_id !== $user->id) {
            return back()->with('error', __('admin_user.revoke_mismatch') ?: 'Abonnement non associé à cet utilisateur');
        }

        $subscription->expires_at = now();
        $subscription->save();

        Log::info('[Admin] Subscription revoked', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'plan' => $subscription->subscriptionPlan?->name,
        ]);

        return back()->with('success', 'Abonnement désactivé avec succès');
    }

    /**
     * Désactive un service premium (UserPremiumService) — ex: Mode Étudiant.
     */
    public function revokePremiumService(User $user, UserPremiumService $service): RedirectResponse
    {
        if ($service->user_id !== $user->id) {
            return back()->with('error', 'Service non associé à cet utilisateur');
        }

        $service->is_active = false;
        $service->expires_at = now();
        $service->save();

        Log::info('[Admin] Premium service revoked', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_premium_service_id' => $service->id,
            'service' => $service->config?->name,
        ]);

        return back()->with('success', 'Service premium désactivé avec succès');
    }

    /**
     * Désactive un service additionnel (UserAddonService).
     */
    public function revokeAddonService(User $user, UserAddonService $addon): RedirectResponse
    {
        if ($addon->user_id !== $user->id) {
            return back()->with('error', 'Service non associé à cet utilisateur');
        }

        $addon->deactivate();

        Log::info('[Admin] Addon service revoked', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_addon_service_id' => $addon->id,
            'service' => $addon->addonServiceConfig?->name,
        ]);

        return back()->with('success', 'Service additionnel désactivé avec succès');
    }

    /**
     * Désactive un pack de stockage (UserStoragePack).
     */
    public function revokeStoragePack(User $user, UserStoragePack $pack): RedirectResponse
    {
        if ($pack->user_id !== $user->id) {
            return back()->with('error', 'Pack non associé à cet utilisateur');
        }

        $pack->is_active = false;
        $pack->expires_at = now();
        $pack->save();

        Log::info('[Admin] Storage pack revoked', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_storage_pack_id' => $pack->id,
        ]);

        return back()->with('success', 'Pack de stockage désactivé avec succès');
    }

    /**
     * Désactive un achat de pack (PackPurchase: ExamPack / TrainingPack).
     */
    public function revokePackPurchase(User $user, PackPurchase $purchase): RedirectResponse
    {
        if ($purchase->user_id !== $user->id) {
            return back()->with('error', 'Pack non associé à cet utilisateur');
        }

        $purchase->status = 'revoked';
        $purchase->expires_at = now();
        $purchase->save();

        Log::info('[Admin] Pack purchase revoked', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'pack_purchase_id' => $purchase->id,
            'pack_type' => $purchase->pack_type,
        ]);

        return back()->with('success', 'Pack désactivé avec succès');
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
                'message' => __('admin_user.fcm_saved'),
            ]);
        } catch (\Throwable $e) {
            \Log::error('❌ [SEND-FCM-TOKEN] Erreur lors de la sauvegarde', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => __('admin_user.fcm_save_error'),
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
