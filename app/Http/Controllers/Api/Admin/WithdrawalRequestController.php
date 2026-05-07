<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WithdrawalRequestController extends Controller
{
    // ID du compte système "Estuaire Emploi"
    const SYSTEM_USER_ID = 1; // À ajuster selon votre DB

    /**
     * Liste toutes les demandes de retrait (Admin)
     *
     * GET /api/admin/withdrawal-requests
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 20);
            $status = $request->input('status');

            $query = WithdrawalRequest::with(['user', 'admin'])
                ->orderBy('created_at', 'desc');

            if ($status) {
                $query->where('status', $status);
            }

            $requests = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $requests->items(),
                'pagination' => [
                    'current_page' => $requests->currentPage(),
                    'last_page' => $requests->lastPage(),
                    'per_page' => $requests->perPage(),
                    'total' => $requests->total(),
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error("[Admin] Erreur récupération demandes: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des demandes',
            ], 500);
        }
    }

    /**
     * Voir une demande spécifique (Admin)
     *
     * GET /api/admin/withdrawal-requests/{id}
     */
    public function show(Request $request, $id)
    {
        try {
            $withdrawalRequest = WithdrawalRequest::with(['user', 'admin'])
                ->find($id);

            if (!$withdrawalRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Demande non trouvée',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $withdrawalRequest,
            ]);

        } catch (\Exception $e) {
            \Log::error("[Admin] Erreur récupération demande: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la demande',
            ], 500);
        }
    }

    /**
     * Approuver ou refuser une demande de retrait
     *
     * POST /api/admin/withdrawal-requests/{id}/respond
     */
    public function respond(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $admin = $request->user();
            $action = $request->action;
            $adminMessage = $request->message;

            $withdrawalRequest = WithdrawalRequest::with('user')->find($id);

            if (!$withdrawalRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Demande non trouvée',
                ], 404);
            }

            if ($withdrawalRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette demande a déjà été traitée',
                ], 400);
            }

            $user = $withdrawalRequest->user;

            // Mettre à jour la demande
            $newStatus = $action === 'approve' ? 'approved' : 'rejected';
            $withdrawalRequest->update([
                'status' => $newStatus,
                'admin_message' => $adminMessage,
                'admin_id' => $admin->id,
                'processed_at' => now(),
            ]);

            // Si approuvé, effectuer le retrait via PayPal
            if ($action === 'approve') {
                // Déduire le montant du wallet
                $user->decrement('paypal_wallet_balance', $withdrawalRequest->amount);

                // Créer une transaction wallet
                \App\Models\WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => -$withdrawalRequest->amount,
                    'balance_before' => $user->paypal_wallet_balance + $withdrawalRequest->amount,
                    'balance_after' => $user->paypal_wallet_balance,
                    'description' => "Retrait PayPal approuvé - {$withdrawalRequest->paypal_email}",
                    'reference_type' => 'withdrawal_request',
                    'reference_id' => $withdrawalRequest->id,
                    'provider' => 'paypal',
                    'status' => 'completed',
                ]);

                \Log::info("[Admin] Retrait approuvé et traité", [
                    'request_id' => $withdrawalRequest->id,
                    'user_id' => $user->id,
                    'amount' => $withdrawalRequest->amount,
                    'admin_id' => $admin->id,
                ]);
            }

            // Envoyer message via système de messagerie "Estuaire Emploi"
            $this->sendSystemMessage($user, $withdrawalRequest, $action, $adminMessage);

            // Envoyer notification FCM
            $this->sendFCMNotification($user, $withdrawalRequest, $action, $adminMessage);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $action === 'approve'
                    ? 'Demande approuvée et retrait effectué avec succès'
                    : 'Demande refusée',
                'data' => $withdrawalRequest->fresh(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("[Admin] Erreur traitement demande: " . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement de la demande',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Envoie un message système à l'utilisateur
     */
    protected function sendSystemMessage(User $user, WithdrawalRequest $request, string $action, ?string $adminMessage)
    {
        try {
            // Trouver ou créer une conversation avec le système
            $conversation = Conversation::where(function ($query) use ($user) {
                $query->where('user_one', self::SYSTEM_USER_ID)
                    ->where('user_two', $user->id);
            })->orWhere(function ($query) use ($user) {
                $query->where('user_one', $user->id)
                    ->where('user_two', self::SYSTEM_USER_ID);
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'user_one' => self::SYSTEM_USER_ID,
                    'user_two' => $user->id,
                ]);
            }

            // Préparer le message
            if ($action === 'approve') {
                $messageText = "✅ Votre demande de retrait de " . number_format($request->amount, 0, ',', ' ') . " FCFA vers {$request->paypal_email} a été approuvée.";

                if ($adminMessage) {
                    $messageText .= "\n\nMessage de l'administrateur : {$adminMessage}";
                }

                $messageText .= "\n\nLe montant a été envoyé à votre compte PayPal.";
            } else {
                $messageText = "❌ Votre demande de retrait de " . number_format($request->amount, 0, ',', ' ') . " FCFA a été refusée.";

                if ($adminMessage) {
                    $messageText .= "\n\nRaison : {$adminMessage}";
                }
            }

            // Créer le message
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => self::SYSTEM_USER_ID,
                'message' => $messageText,
                'status' => 'sent',
            ]);

            \Log::info("[Admin] Message système envoyé", [
                'user_id' => $user->id,
                'conversation_id' => $conversation->id,
                'action' => $action,
            ]);

        } catch (\Exception $e) {
            \Log::error("[Admin] Erreur envoi message système: " . $e->getMessage());
        }
    }

    /**
     * Envoie une notification FCM à l'utilisateur
     */
    protected function sendFCMNotification(User $user, WithdrawalRequest $request, string $action, ?string $adminMessage)
    {
        try {
            if (!$user->fcm_token) {
                return;
            }

            if ($action === 'approve') {
                $title = "Retrait approuvé";
                $body = "Votre retrait de " . number_format($request->amount, 0, ',', ' ') . " FCFA a été approuvé et envoyé vers {$request->paypal_email}";
            } else {
                $title = "Retrait refusé";
                $body = "Votre retrait de " . number_format($request->amount, 0, ',', ' ') . " FCFA a été refusé";

                if ($adminMessage) {
                    $body .= ". Raison : " . substr($adminMessage, 0, 50);
                }
            }

            // Créer la notification
            \App\Models\Notification::create([
                'type' => 'withdrawal_request_' . ($action === 'approve' ? 'approved' : 'rejected'),
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'withdrawal_request_id' => $request->id,
                    'amount' => $request->amount,
                    'status' => $action === 'approve' ? 'approved' : 'rejected',
                    'admin_message' => $adminMessage,
                ],
            ]);

            // Envoyer via FCM
            \Illuminate\Support\Facades\Http::withToken(config('services.fcm.server_key'))
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $user->fcm_token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'sound' => 'default',
                    ],
                    'data' => [
                        'type' => 'withdrawal_request_response',
                        'withdrawal_request_id' => $request->id,
                        'action' => $action,
                    ],
                ]);

            \Log::info("[Admin] FCM notification envoyée", [
                'user_id' => $user->id,
                'action' => $action,
            ]);

        } catch (\Exception $e) {
            \Log::error("[Admin] Erreur envoi FCM: " . $e->getMessage());
        }
    }
}
