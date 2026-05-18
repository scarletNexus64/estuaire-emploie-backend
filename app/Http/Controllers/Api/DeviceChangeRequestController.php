<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceChangeRequest;
use App\Models\User;
use App\Services\Notifications\NexahService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeviceChangeRequestController extends Controller
{
    /**
     * Créer une demande de changement d'appareil
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'identifier' => 'required|string', // Email ou téléphone
            'password' => 'required|string',
            'new_device_id' => 'required|string',
            'device_name' => 'nullable|string',
            'device_model' => 'nullable|string',
            'reason' => 'nullable|string|max:500',
        ]);

        $identifier = $validated['identifier'];

        // Trouver l'utilisateur
        $user = null;
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $identifier)->first();
        } else {
            $cleanPhone = preg_replace('/[^0-9+]/', '', $identifier);
            $user = User::where('phone', $cleanPhone)
                ->orWhere('phone', $identifier)
                ->first();
        }

        // Vérifier que l'utilisateur existe
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Compte introuvable.',
            ], 404);
        }

        // Vérifier le mot de passe
        if (!\Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect.',
            ], 401);
        }

        // Vérifier s'il y a déjà une demande en attente
        if ($user->hasPendingDeviceChangeRequest()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà une demande de changement d\'appareil en cours de traitement.',
            ], 422);
        }

        // Créer la demande
        $deviceChangeRequest = DeviceChangeRequest::create([
            'user_id' => $user->id,
            'old_device_id' => $user->device_id,
            'new_device_id' => $validated['new_device_id'],
            'device_name' => $validated['device_name'] ?? null,
            'device_model' => $validated['device_model'] ?? null,
            'reason' => $validated['reason'] ?? 'Changement d\'appareil',
            'status' => 'pending',
        ]);

        Log::info('📱 [DEVICE_CHANGE] Nouvelle demande de changement d\'appareil', [
            'user_id' => $user->id,
            'request_id' => $deviceChangeRequest->id,
            'old_device' => $user->device_id,
            'new_device' => $validated['new_device_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Demande de changement d\'appareil soumise avec succès. Un administrateur la traitera prochainement.',
            'request' => $deviceChangeRequest,
        ], 201);
    }

    /**
     * Récupérer le statut de la demande de changement d'appareil
     */
    public function status(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $validated['identifier'];

        // Trouver l'utilisateur
        $user = null;
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $identifier)->first();
        } else {
            $cleanPhone = preg_replace('/[^0-9+]/', '', $identifier);
            $user = User::where('phone', $cleanPhone)
                ->orWhere('phone', $identifier)
                ->first();
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Compte introuvable.',
            ], 404);
        }

        $pendingRequest = $user->pendingDeviceChangeRequest();

        return response()->json([
            'success' => true,
            'has_pending_request' => $pendingRequest !== null,
            'request' => $pendingRequest,
        ]);
    }

    /**
     * Liste de toutes les demandes (Admin seulement)
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Vérifier que l'utilisateur est admin
        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.',
            ], 403);
        }

        $status = $request->query('status', 'pending');

        $requests = DeviceChangeRequest::with(['user', 'reviewer'])
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'requests' => $requests,
        ]);
    }

    /**
     * Approuver une demande de changement d'appareil (Admin seulement)
     */
    public function approve(Request $request, int $requestId): JsonResponse
    {
        $admin = $request->user();

        // Vérifier que l'utilisateur est admin
        if (!$admin->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.',
            ], 403);
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $deviceChangeRequest = DeviceChangeRequest::with('user')->find($requestId);

        if (!$deviceChangeRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Demande introuvable.',
            ], 404);
        }

        if ($deviceChangeRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande a déjà été traitée.',
            ], 422);
        }

        // Approuver la demande
        $deviceChangeRequest->update([
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        // Mettre à jour le device_id de l'utilisateur
        $deviceChangeRequest->user->update([
            'device_id' => $deviceChangeRequest->new_device_id,
        ]);

        Log::info('✅ [DEVICE_CHANGE] Demande approuvée', [
            'request_id' => $deviceChangeRequest->id,
            'user_id' => $deviceChangeRequest->user_id,
            'admin_id' => $admin->id,
            'new_device_id' => $deviceChangeRequest->new_device_id,
        ]);

        // Envoyer un SMS de notification si l'utilisateur a un numéro de téléphone
        $this->sendApprovalSms($deviceChangeRequest->user);

        return response()->json([
            'success' => true,
            'message' => 'Demande approuvée avec succès. L\'utilisateur peut maintenant se connecter avec son nouvel appareil.',
            'request' => $deviceChangeRequest->fresh(['user', 'reviewer']),
        ]);
    }

    /**
     * Rejeter une demande de changement d'appareil (Admin seulement)
     */
    public function reject(Request $request, int $requestId): JsonResponse
    {
        $admin = $request->user();

        // Vérifier que l'utilisateur est admin
        if (!$admin->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.',
            ], 403);
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $deviceChangeRequest = DeviceChangeRequest::with('user')->find($requestId);

        if (!$deviceChangeRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Demande introuvable.',
            ], 404);
        }

        if ($deviceChangeRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande a déjà été traitée.',
            ], 422);
        }

        // Rejeter la demande
        $deviceChangeRequest->update([
            'status' => 'rejected',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        Log::info('❌ [DEVICE_CHANGE] Demande rejetée', [
            'request_id' => $deviceChangeRequest->id,
            'user_id' => $deviceChangeRequest->user_id,
            'admin_id' => $admin->id,
            'reason' => $validated['admin_notes'],
        ]);

        // Envoyer un SMS de notification si l'utilisateur a un numéro de téléphone
        $this->sendRejectionSms($deviceChangeRequest->user, $validated['admin_notes']);

        return response()->json([
            'success' => true,
            'message' => 'Demande rejetée.',
            'request' => $deviceChangeRequest->fresh(['user', 'reviewer']),
        ]);
    }

    /**
     * Envoyer un SMS de notification d'approbation
     */
    private function sendApprovalSms(User $user): void
    {
        if (!$user->phone) {
            Log::warning('[DEVICE_CHANGE_SMS] Impossible d\'envoyer le SMS : utilisateur sans numéro de téléphone', [
                'user_id' => $user->id,
            ]);
            return;
        }

        try {
            $nexahService = new NexahService();
            $message = "Estuaire Emploi : Votre demande de changement d'appareil a été approuvée. Vous pouvez maintenant vous connecter avec votre nouvel appareil.";

            // Envoyer le SMS avec deux senderID différents
            $result1 = $nexahService->sendSms($user->phone, $message, 'infos');
            $result2 = $nexahService->sendSms($user->phone, $message);

            Log::info('[DEVICE_CHANGE_SMS] SMS d\'approbation envoyé', [
                'user_id' => $user->id,
                'phone' => $user->phone,
                'result1' => $result1['success'],
                'result2' => $result2['success'],
            ]);
        } catch (\Exception $e) {
            Log::error('[DEVICE_CHANGE_SMS] Erreur lors de l\'envoi du SMS d\'approbation', [
                'user_id' => $user->id,
                'phone' => $user->phone,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envoyer un SMS de notification de rejet
     */
    private function sendRejectionSms(User $user, string $reason): void
    {
        if (!$user->phone) {
            Log::warning('[DEVICE_CHANGE_SMS] Impossible d\'envoyer le SMS : utilisateur sans numéro de téléphone', [
                'user_id' => $user->id,
            ]);
            return;
        }

        try {
            $nexahService = new NexahService();
            $message = "Estuaire Emploi : Votre demande de changement d'appareil a été rejetée. Raison : " . substr($reason, 0, 100);

            // Envoyer le SMS avec deux senderID différents
            $result1 = $nexahService->sendSms($user->phone, $message, 'infos');
            $result2 = $nexahService->sendSms($user->phone, $message);

            Log::info('[DEVICE_CHANGE_SMS] SMS de rejet envoyé', [
                'user_id' => $user->id,
                'phone' => $user->phone,
                'result1' => $result1['success'],
                'result2' => $result2['success'],
            ]);
        } catch (\Exception $e) {
            Log::error('[DEVICE_CHANGE_SMS] Erreur lors de l\'envoi du SMS de rejet', [
                'user_id' => $user->id,
                'phone' => $user->phone,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
