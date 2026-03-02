<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PremiumServiceConfig;
use App\Models\User;
use App\Models\UserPremiumService;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CandidatePremiumServiceController extends Controller
{
    /**
     * Liste des services premium disponibles pour les candidats
     * GET /api/candidate/premium-services
     */
    public function index()
    {
        $services = PremiumServiceConfig::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }

    /**
     * Acheter un service premium avec le wallet
     * POST /api/candidate/premium-services/purchase
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'service_slug' => 'required|string|exists:premium_services_configs,slug',
            'payment_provider' => 'required|string|in:freemopay,paypal',
        ]);

        $user = Auth::user();
        $paymentProvider = $request->payment_provider;

        // Récupérer le service
        $service = PremiumServiceConfig::where('slug', $request->service_slug)
            ->where('is_active', true)
            ->first();

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Ce service n\'est pas disponible',
            ], 404);
        }

        // Vérifier si l'utilisateur a déjà ce service actif
        $existingService = UserPremiumService::where('user_id', $user->id)
            ->where('premium_services_config_id', $service->id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->first();

        if ($existingService) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà ce service actif',
                'data' => [
                    'service' => $service,
                    'user_service' => $existingService->load('config'),
                ],
            ], 400);
        }

        // Déterminer le champ wallet et le nom du provider
        $walletField = $paymentProvider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';
        $walletBalance = $user->{$walletField} ?? 0;
        $providerName = $paymentProvider === 'paypal' ? 'PayPal' : 'FreeMoPay';

        // Vérifier le solde wallet
        if ($walletBalance < $service->price) {
            return response()->json([
                'success' => false,
                'message' => "Solde {$providerName} insuffisant. Veuillez recharger votre wallet.",
                'data' => [
                    'required_amount' => $service->price,
                    'current_balance' => $walletBalance,
                    'missing_amount' => $service->price - $walletBalance,
                    'provider' => $paymentProvider,
                ],
            ], 400);
        }

        // Transaction pour l'achat
        DB::beginTransaction();
        try {
            // Store balance before debit
            $balanceBefore = $walletBalance;

            // Débiter le wallet spécifique
            $user->decrement($walletField, $service->price);
            $user->refresh();
            $balanceAfter = $user->{$walletField};

            // Créer le paiement
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $service->price,
                'fees' => 0,
                'total' => $service->price,
                'currency' => 'XAF',
                'payment_method' => 'wallet',
                'payment_type' => 'service',
                'provider' => $paymentProvider,
                'status' => 'completed',
                'paid_at' => now(),
                'payable_type' => PremiumServiceConfig::class,
                'payable_id' => $service->id,
            ]);

            // Créer la transaction wallet
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $service->price,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => "Achat du service: {$service->name} ({$providerName})",
                'reference_type' => 'payment',
                'reference_id' => $payment->id,
                'provider' => $paymentProvider,
                'status' => 'completed',
            ]);

            // Calculer la date d'expiration
            $expiresAt = $service->duration_days
                ? now()->addDays($service->duration_days)
                : null;

            // Activer le service
            $userService = UserPremiumService::create([
                'user_id' => $user->id,
                'premium_services_config_id' => $service->id,
                'payment_id' => $payment->id,
                'purchased_at' => now(),
                'activated_at' => now(),
                'expires_at' => $expiresAt,
                'is_active' => true,
                'auto_renew' => false,
            ]);

            DB::commit();

            // Envoyer notification FCM pour l'achat
            $this->sendPurchaseNotification($user, $service, $paymentProvider);

            return response()->json([
                'success' => true,
                'message' => 'Service activé avec succès !',
                'data' => [
                    'service' => $userService->load('config'),
                    'payment' => $payment,
                    'new_balance' => $balanceAfter,
                    'provider' => $paymentProvider,
                ],
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'achat du service',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Envoie une notification FCM pour un achat de service premium
     */
    protected function sendPurchaseNotification(User $user, PremiumServiceConfig $service, string $paymentProvider): void
    {
        try {
            if (!$user->fcm_token) {
                return;
            }

            $providerName = $paymentProvider === 'paypal' ? 'PayPal' : 'FreeMoPay';
            $title = "Service premium activé";
            $body = "Votre service premium {$service->name} pour " . number_format($service->price, 0, ',', ' ') . " FCFA via wallet {$providerName} a été activé avec succès.";

            // Créer la notification avec la structure correcte
            $notification = \App\Models\Notification::create([
                'type' => 'premium_service_purchase',
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'service_name' => $service->name,
                    'service_slug' => $service->slug,
                    'amount' => $service->price,
                    'provider' => $paymentProvider,
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
                        'type' => 'premium_service_purchase',
                        'service_name' => $service->name,
                        'notification_id' => $notification->id,
                    ],
                ]);

            \Log::info("[CandidatePremiumService] ✅ FCM notification sent for service purchase", [
                'user_id' => $user->id,
                'service_name' => $service->name,
                'amount' => $service->price,
                'provider' => $paymentProvider,
            ]);

        } catch (\Exception $e) {
            \Log::error("[CandidatePremiumService] ❌ Failed to send FCM notification: " . $e->getMessage());
        }
    }

    /**
     * Liste de mes services premium actifs
     * GET /api/candidate/premium-services/my-services
     */
    public function myServices()
    {
        $user = Auth::user();

        $services = UserPremiumService::with('config', 'payment')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('activated_at', 'desc')
            ->get();

        // Séparer les services actifs et expirés
        $activeServices = $services->filter(function ($service) {
            return $service->isValid();
        });

        $expiredServices = $services->filter(function ($service) {
            return !$service->isValid();
        });

        return response()->json([
            'success' => true,
            'data' => [
                'active_services' => $activeServices->values(),
                'expired_services' => $expiredServices->values(),
            ],
        ]);
    }

    /**
     * Vérifier l'accès à un service spécifique
     * GET /api/candidate/premium-services/check-access/{slug}
     */
    public function checkAccess(string $slug)
    {
        $user = Auth::user();

        $service = PremiumServiceConfig::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service introuvable',
            ], 404);
        }

        $userService = UserPremiumService::where('user_id', $user->id)
            ->where('premium_services_config_id', $service->id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->first();

        $hasAccess = $userService !== null;

        return response()->json([
            'success' => true,
            'data' => [
                'service_slug' => $slug,
                'service_name' => $service->name,
                'has_access' => $hasAccess,
                'user_service' => $hasAccess ? $userService->load('config') : null,
            ],
        ]);
    }

    /**
     * Obtenir les détails d'un service spécifique
     * GET /api/candidate/premium-services/{slug}
     */
    public function show(string $slug)
    {
        $service = PremiumServiceConfig::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service introuvable',
            ], 404);
        }

        $user = Auth::user();

        // Vérifier si l'utilisateur a déjà ce service
        $userService = UserPremiumService::where('user_id', $user->id)
            ->where('premium_services_config_id', $service->id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'service' => $service,
                'has_access' => $userService !== null,
                'user_service' => $userService ? $userService->load('config') : null,
            ],
        ]);
    }
}
