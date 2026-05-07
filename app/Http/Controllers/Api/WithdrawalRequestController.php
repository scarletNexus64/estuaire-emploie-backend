<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawalRequestController extends Controller
{
    /**
     * Soumettre une demande de retrait PayPal
     *
     * POST /api/withdrawal-requests
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'paypal_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = $request->user();
            $amount = $request->amount;
            $paypalEmail = $request->paypal_email;

            // Vérifier le solde disponible
            $availableBalance = $user->paypal_wallet_balance ?? 0;

            if ($amount > $availableBalance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solde insuffisant. Disponible: ' . number_format($availableBalance, 0, ',', ' ') . ' FCFA',
                ], 400);
            }

            // Créer la demande de retrait
            $withdrawalRequest = WithdrawalRequest::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'paypal_email' => $paypalEmail,
                'status' => 'pending',
            ]);

            \Log::info("[WithdrawalRequest] Nouvelle demande créée", [
                'request_id' => $withdrawalRequest->id,
                'user_id' => $user->id,
                'amount' => $amount,
                'email' => substr($paypalEmail, 0, 3) . '***',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Votre demande de retrait a été soumise avec succès. Un administrateur la traitera bientôt.',
                'data' => [
                    'id' => $withdrawalRequest->id,
                    'amount' => $withdrawalRequest->amount,
                    'paypal_email' => $withdrawalRequest->paypal_email,
                    'status' => $withdrawalRequest->status,
                    'created_at' => $withdrawalRequest->created_at->toIso8601String(),
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error("[WithdrawalRequest] Erreur création demande: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la soumission de la demande',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Liste les demandes de retrait de l'utilisateur
     *
     * GET /api/withdrawal-requests
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->input('per_page', 20);
            $status = $request->input('status');

            $query = WithdrawalRequest::where('user_id', $user->id)
                ->with(['admin'])
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
            \Log::error("[WithdrawalRequest] Erreur récupération liste: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des demandes',
            ], 500);
        }
    }

    /**
     * Voir une demande spécifique
     *
     * GET /api/withdrawal-requests/{id}
     */
    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();

            $withdrawalRequest = WithdrawalRequest::where('id', $id)
                ->where('user_id', $user->id)
                ->with(['admin'])
                ->first();

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
            \Log::error("[WithdrawalRequest] Erreur récupération demande: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la demande',
            ], 500);
        }
    }
}
