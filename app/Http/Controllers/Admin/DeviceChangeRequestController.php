<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceChangeRequest;
use App\Services\Notifications\NexahService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeviceChangeRequestController extends Controller
{
    /**
     * Display a listing of device change requests
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = DeviceChangeRequest::with(['user', 'reviewer'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->paginate(20);

        // Count pending requests for badge
        $pendingCount = DeviceChangeRequest::where('status', 'pending')->count();

        return view('admin.device-change-requests.index', compact('requests', 'status', 'pendingCount'));
    }

    /**
     * Approve a device change request
     */
    public function approve(Request $request, int $requestId)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $deviceChangeRequest = DeviceChangeRequest::with('user')->findOrFail($requestId);

        if ($deviceChangeRequest->status !== 'pending') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        // Approve the request
        $deviceChangeRequest->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        // Update user's device_id
        $deviceChangeRequest->user->update([
            'device_id' => $deviceChangeRequest->new_device_id,
        ]);

        Log::info('✅ [ADMIN DEVICE_CHANGE] Demande approuvée', [
            'request_id' => $deviceChangeRequest->id,
            'user_id' => $deviceChangeRequest->user_id,
            'admin_id' => auth()->id(),
        ]);

        // Send SMS notification
        $this->sendApprovalSms($deviceChangeRequest->user);

        return back()->with('success', 'Demande approuvée avec succès. L\'utilisateur a été notifié par SMS.');
    }

    /**
     * Reject a device change request
     */
    public function reject(Request $request, int $requestId)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $deviceChangeRequest = DeviceChangeRequest::with('user')->findOrFail($requestId);

        if ($deviceChangeRequest->status !== 'pending') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        // Reject the request
        $deviceChangeRequest->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        Log::info('❌ [ADMIN DEVICE_CHANGE] Demande rejetée', [
            'request_id' => $deviceChangeRequest->id,
            'user_id' => $deviceChangeRequest->user_id,
            'admin_id' => auth()->id(),
            'reason' => $validated['admin_notes'],
        ]);

        // Send SMS notification
        $this->sendRejectionSms($deviceChangeRequest->user, $validated['admin_notes']);

        return back()->with('success', 'Demande rejetée. L\'utilisateur a été notifié par SMS.');
    }

    /**
     * Send approval SMS notification
     */
    private function sendApprovalSms($user): void
    {
        if (!$user->phone) {
            return;
        }

        try {
            $nexahService = new NexahService();
            $message = "Estuaire Emploi : Votre demande de changement d'appareil a été approuvée. Vous pouvez maintenant vous connecter avec votre nouvel appareil.";

            $nexahService->sendSms($user->phone, $message, 'infos');
            $nexahService->sendSms($user->phone, $message);

            Log::info('[ADMIN DEVICE_CHANGE_SMS] SMS d\'approbation envoyé', [
                'user_id' => $user->id,
                'phone' => $user->phone,
            ]);
        } catch (\Exception $e) {
            Log::error('[ADMIN DEVICE_CHANGE_SMS] Erreur lors de l\'envoi du SMS', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send rejection SMS notification
     */
    private function sendRejectionSms($user, string $reason): void
    {
        if (!$user->phone) {
            return;
        }

        try {
            $nexahService = new NexahService();
            $message = "Estuaire Emploi : Votre demande de changement d'appareil a été rejetée. Raison : " . substr($reason, 0, 100);

            $nexahService->sendSms($user->phone, $message, 'infos');
            $nexahService->sendSms($user->phone, $message);

            Log::info('[ADMIN DEVICE_CHANGE_SMS] SMS de rejet envoyé', [
                'user_id' => $user->id,
                'phone' => $user->phone,
            ]);
        } catch (\Exception $e) {
            Log::error('[ADMIN DEVICE_CHANGE_SMS] Erreur lors de l\'envoi du SMS', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
