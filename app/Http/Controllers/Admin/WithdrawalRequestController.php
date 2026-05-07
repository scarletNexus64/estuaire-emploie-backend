<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalRequestController extends Controller
{
    // ID du compte système "Estuaire Emploi"
    const SYSTEM_USER_ID = 1;

    protected $firebaseService;

    public function __construct(FirebaseNotificationService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Liste toutes les demandes de retrait
     */
    public function index(Request $request)
    {
        $query = WithdrawalRequest::with(['user', 'admin'])
            ->orderBy('created_at', 'desc');

        // Filtrer par statut
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Recherche par nom ou email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => WithdrawalRequest::count(),
            'pending' => WithdrawalRequest::where('status', 'pending')->count(),
            'approved' => WithdrawalRequest::where('status', 'approved')->count(),
            'rejected' => WithdrawalRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.withdrawal-requests.index', compact('requests', 'stats'));
    }

    /**
     * Affiche les détails d'une demande
     */
    public function show(WithdrawalRequest $request)
    {
        $request->load(['user', 'admin']);

        return view('admin.withdrawal-requests.show', [
            'request' => $request
        ]);
    }

    /**
     * Traite une demande (approuver/refuser)
     */
    public function respond(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'message' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $admin = auth()->user();
            $action = $validated['action'];
            $adminMessage = $validated['message'];

            $withdrawalRequest = WithdrawalRequest::with('user')->findOrFail($id);

            if ($withdrawalRequest->status !== 'pending') {
                return back()->with('error', 'Cette demande a déjà été traitée.');
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

            // Si approuvé, effectuer le retrait
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

                \Log::info("[Admin] Retrait approuvé", [
                    'request_id' => $withdrawalRequest->id,
                    'user_id' => $user->id,
                    'amount' => $withdrawalRequest->amount,
                    'admin_id' => $admin->id,
                ]);
            }

            // Envoyer message système
            $this->sendSystemMessage($user, $withdrawalRequest, $action, $adminMessage);

            // Envoyer notification FCM
            $this->sendFCMNotification($user, $withdrawalRequest, $action, $adminMessage);

            DB::commit();

            $successMessage = $action === 'approve'
                ? 'Demande approuvée et retrait effectué avec succès !'
                : 'Demande refusée.';

            return redirect()->route('admin.withdrawal-requests.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("[Admin] Erreur traitement demande: " . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return back()->with('error', 'Erreur lors du traitement: ' . $e->getMessage());
        }
    }

    /**
     * Envoie un message système
     */
    protected function sendSystemMessage(User $user, WithdrawalRequest $request, string $action, ?string $adminMessage)
    {
        try {
            // Trouver ou créer conversation avec le système
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

        } catch (\Exception $e) {
            \Log::error("[Admin] Erreur envoi message système: " . $e->getMessage());
        }
    }

    /**
     * Envoie une notification FCM
     */
    protected function sendFCMNotification(User $user, WithdrawalRequest $request, string $action, ?string $adminMessage)
    {
        try {
            if (!$user->fcm_token) {
                \Log::warning("[Admin] Pas de FCM token pour l'utilisateur {$user->id}");
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

            // Créer la notification en base de données
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

            // Envoyer via Firebase Notification Service
            $this->firebaseService->sendToToken(
                $user->fcm_token,
                $title,
                $body,
                [
                    'type' => 'withdrawal_request_response',
                    'withdrawal_request_id' => (string)$request->id,
                    'action' => $action,
                    'amount' => (string)$request->amount,
                ]
            );

            \Log::info("[Admin] Notification FCM envoyée avec succès", [
                'user_id' => $user->id,
                'withdrawal_request_id' => $request->id,
                'action' => $action,
            ]);

        } catch (\Exception $e) {
            \Log::error("[Admin] Erreur envoi FCM: " . $e->getMessage());
            \Log::error($e->getTraceAsString());

            // Supprimer le token si invalide
            if (str_contains($e->getMessage(), 'not found') ||
                str_contains($e->getMessage(), 'not valid') ||
                str_contains($e->getMessage(), 'Invalid registration') ||
                str_contains($e->getMessage(), 'NotRegistered')) {
                \Log::info("[Admin] Token FCM invalide supprimé pour l'utilisateur {$user->id}");
                $user->update(['fcm_token' => null]);
            }
        }
    }
}
