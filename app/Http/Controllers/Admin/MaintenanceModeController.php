<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendMaintenanceNotificationJob;
use App\Jobs\SendMaintenanceTopicNotification;
use App\Models\MaintenanceMode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceModeController extends Controller
{
    public function index()
    {
        $maintenanceMode = MaintenanceMode::latest()->first();

        return view('admin.maintenance.index', compact('maintenanceMode'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
            'message' => 'nullable|string|max:1000',
        ]);

        $isActive = $request->boolean('is_active');
        $message = $request->input('message', '');

        $maintenanceMode = new MaintenanceMode();
        $maintenanceMode->is_active = $isActive;
        $maintenanceMode->message = $message;
        $maintenanceMode->activated_by = Auth::id();

        if ($isActive) {
            $maintenanceMode->activated_at = now();
            $maintenanceMode->deactivated_at = null;
        } else {
            $maintenanceMode->deactivated_at = now();
        }

        $maintenanceMode->save();

        // 🔥 Envoyer notification FCM instantanée au topic 'maintenance'
        // Plus efficace: 1 seule requête au lieu de N requêtes (où N = nombre d'users)
        if ($isActive) {
            SendMaintenanceTopicNotification::dispatch($isActive, $message);
        }

        // Optionnel: Garder l'ancien système pour notifs individuelles (backup)
        // $users = User::all();
        // foreach ($users as $user) {
        //     SendMaintenanceNotificationJob::dispatch($user, $isActive, $message);
        // }

        $status = $isActive ? 'activé' : 'désactivé';
        return redirect()->route('admin.maintenance.index')
            ->with('success', "Mode maintenance {$status} avec succès. Notification FCM envoyée instantanément à tous les utilisateurs.");
    }
}
