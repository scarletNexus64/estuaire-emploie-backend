<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::where('role', 'candidate')
            ->withCount('applications')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['applications.job']);

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user): RedirectResponse
    {
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

            $count = User::whereIn('id', $ids)->delete();

            return redirect()->back()->with('success', "$count élément(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}
