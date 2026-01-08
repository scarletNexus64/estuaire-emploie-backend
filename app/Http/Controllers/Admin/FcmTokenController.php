<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    /**
     * Display a listing of users with FCM tokens
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter: Show only users with FCM tokens or all users
        $filter = $request->get('filter', 'with_token');

        if ($filter === 'with_token') {
            $query->whereNotNull('fcm_token');
        } elseif ($filter === 'without_token') {
            $query->whereNull('fcm_token');
        }
        // 'all' shows all users

        // Search by name, email, or phone
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        // Order by most recent
        $query->orderBy('updated_at', 'desc');

        // Paginate results
        $users = $query->paginate(20);

        // Statistics
        $stats = [
            'total_users' => User::count(),
            'users_with_token' => User::whereNotNull('fcm_token')->count(),
            'users_without_token' => User::whereNull('fcm_token')->count(),
            'candidates_with_token' => User::where('role', 'candidate')->whereNotNull('fcm_token')->count(),
            'recruiters_with_token' => User::where('role', 'recruiter')->whereNotNull('fcm_token')->count(),
        ];

        return view('admin.fcm-tokens.index', compact('users', 'stats', 'filter'));
    }

    /**
     * Show the details of a specific user's FCM token
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.fcm-tokens.show', compact('user'));
    }

    /**
     * Remove FCM token from a user
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->fcm_token = null;
        $user->save();

        return redirect()
            ->route('admin.fcm-tokens.index')
            ->with('success', "Token FCM supprimé pour {$user->name}");
    }

    /**
     * Bulk remove FCM tokens
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDestroy(Request $request)
    {
        $ids = json_decode($request->input('ids'), true);

        if (!is_array($ids) || empty($ids)) {
            return redirect()
                ->route('admin.fcm-tokens.index')
                ->with('error', 'Aucun utilisateur sélectionné');
        }

        $count = User::whereIn('id', $ids)->update(['fcm_token' => null]);

        return redirect()
            ->route('admin.fcm-tokens.index')
            ->with('success', "{$count} token(s) FCM supprimé(s)");
    }

    /**
     * Export users with FCM tokens to CSV
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $query = User::query();

        $filter = $request->get('filter', 'with_token');
        if ($filter === 'with_token') {
            $query->whereNotNull('fcm_token');
        } elseif ($filter === 'without_token') {
            $query->whereNull('fcm_token');
        }

        $users = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="fcm_tokens_' . date('Y-m-d_His') . '.csv"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, ['ID', 'Nom', 'Email', 'Téléphone', 'Rôle', 'FCM Token', 'Dernière mise à jour'], ';');

            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->role,
                    $user->fcm_token ?? 'N/A',
                    $user->updated_at->format('Y-m-d H:i:s'),
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
