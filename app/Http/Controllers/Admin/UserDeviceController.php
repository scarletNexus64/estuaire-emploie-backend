<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserDeviceController extends Controller
{
    /**
     * Display a listing of users with their devices
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filter = $request->get('filter', 'all'); // all, with_device, without_device

        $query = User::query()
            ->select('id', 'name', 'email', 'phone', 'role', 'device_id', 'created_at', 'updated_at')
            ->orderBy('updated_at', 'desc');

        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('device_id', 'like', "%{$search}%");
            });
        }

        // Apply filter
        if ($filter === 'with_device') {
            $query->whereNotNull('device_id');
        } elseif ($filter === 'without_device') {
            $query->whereNull('device_id');
        }

        $users = $query->paginate(50);

        // Statistics
        $stats = [
            'total' => User::count(),
            'with_device' => User::whereNotNull('device_id')->count(),
            'without_device' => User::whereNull('device_id')->count(),
        ];

        return view('admin.users-devices.index', compact('users', 'stats', 'search', 'filter'));
    }

    /**
     * Reset device_id for a user (disconnect device)
     */
    public function resetDevice(int $userId)
    {
        $user = User::findOrFail($userId);

        $oldDeviceId = $user->device_id;
        $user->update(['device_id' => null]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'old_device_id' => $oldDeviceId,
                'action' => 'device_reset',
            ])
            ->log("Device déconnecté pour {$user->name}");

        return back()->with('success', "Appareil déconnecté pour {$user->name}. L'utilisateur devra se reconnecter.");
    }
}
