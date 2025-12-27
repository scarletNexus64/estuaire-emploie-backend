<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscriptionPlan;
use App\Services\Payment\FreeMoPayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Subscription Plans",
 *     description="API Endpoints pour la gestion des abonnements recruteurs"
 * )
 */
class SubscriptionPlanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/subscription-plans",
     *     summary="Liste des plans d'abonnement disponibles",
     *     description="Récupère tous les plans d'abonnement actifs pour les recruteurs",
     *     tags={"Subscription Plans"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des plans d'abonnement",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Plans d'abonnement récupérés avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="STARTER"),
     *                     @OA\Property(property="slug", type="string", example="starter"),
     *                     @OA\Property(property="description", type="string", example="Idéal pour débuter"),
     *                     @OA\Property(property="price", type="number", format="float", example=15000.00),
     *                     @OA\Property(property="duration_days", type="integer", example=30),
     *                     @OA\Property(property="jobs_limit", type="integer", nullable=true, example=3),
     *                     @OA\Property(property="contacts_limit", type="integer", nullable=true, example=10),
     *                     @OA\Property(property="can_access_cvtheque", type="boolean", example=false),
     *                     @OA\Property(property="can_boost_jobs", type="boolean", example=false),
     *                     @OA\Property(property="can_see_analytics", type="boolean", example=false),
     *                     @OA\Property(property="priority_support", type="boolean", example=false),
     *                     @OA\Property(property="is_popular", type="boolean", example=false),
     *                     @OA\Property(property="color", type="string", nullable=true, example="#667eea"),
     *                     @OA\Property(property="icon", type="string", nullable=true, example="rocket")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $plans = SubscriptionPlan::active()
            ->ordered()
            ->get([
                'id',
                'name',
                'slug',
                'description',
                'price',
                'duration_days',
                'jobs_limit',
                'contacts_limit',
                'can_access_cvtheque',
                'can_boost_jobs',
                'can_see_analytics',
                'priority_support',
                'featured_company_badge',
                'custom_company_page',
                'features',
                'is_popular',
                'color',
                'icon',
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Plans d\'abonnement récupérés avec succès',
            'data' => $plans,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/subscription-plans/{id}",
     *     summary="Détails d'un plan d'abonnement",
     *     description="Récupère les informations détaillées d'un plan d'abonnement spécifique",
     *     tags={"Subscription Plans"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du plan d'abonnement",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du plan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Plan d'abonnement récupéré avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="STARTER"),
     *                 @OA\Property(property="slug", type="string", example="starter"),
     *                 @OA\Property(property="description", type="string", example="Idéal pour débuter"),
     *                 @OA\Property(property="price", type="number", format="float", example=15000.00),
     *                 @OA\Property(property="duration_days", type="integer", example=30),
     *                 @OA\Property(property="jobs_limit", type="integer", nullable=true, example=3),
     *                 @OA\Property(property="contacts_limit", type="integer", nullable=true, example=10),
     *                 @OA\Property(property="can_access_cvtheque", type="boolean", example=false),
     *                 @OA\Property(property="can_boost_jobs", type="boolean", example=false),
     *                 @OA\Property(property="can_see_analytics", type="boolean", example=false),
     *                 @OA\Property(property="priority_support", type="boolean", example=false),
     *                 @OA\Property(property="featured_company_badge", type="boolean", example=false),
     *                 @OA\Property(property="custom_company_page", type="boolean", example=false),
     *                 @OA\Property(property="features", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="is_popular", type="boolean", example=false),
     *                 @OA\Property(property="color", type="string", nullable=true, example="#667eea"),
     *                 @OA\Property(property="icon", type="string", nullable=true, example="rocket")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Plan d'abonnement non trouvé")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $plan = SubscriptionPlan::active()->find($id);

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan d\'abonnement non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Plan d\'abonnement récupéré avec succès',
            'data' => $plan,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/subscriptions/activate",
     *     summary="Activer un abonnement après paiement",
     *     description="Active l'abonnement d'un utilisateur si le paiement associé est complété.
     *                  Cette méthode vérifie le statut du paiement et retourne un message approprié.",
     *     tags={"Subscription Plans"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"subscription_plan_id", "payment_id"},
     *             @OA\Property(
     *                 property="subscription_plan_id",
     *                 type="integer",
     *                 description="ID du plan d'abonnement",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="payment_id",
     *                 type="integer",
     *                 description="ID du paiement associé",
     *                 example=123
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Abonnement activé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Abonnement activé avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="subscription_id", type="integer", example=1),
     *                 @OA\Property(property="plan_name", type="string", example="STARTER"),
     *                 @OA\Property(property="starts_at", type="string", format="datetime"),
     *                 @OA\Property(property="ends_at", type="string", format="datetime"),
     *                 @OA\Property(property="is_active", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Paiement non confirmé ou données invalides",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Paiement non confirmé")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan ou paiement non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Plan d'abonnement non trouvé")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Abonnement déjà existant",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cet abonnement existe déjà")
     *         )
     *     )
     * )
     */
    public function activate(Request $request): JsonResponse
    {
        $request->validate([
            'subscription_plan_id' => 'required|integer|exists:subscription_plans,id',
            'payment_id' => 'required|integer|exists:payments,id',
        ]);

        $user = $request->user();
        $subscriptionPlanId = $request->subscription_plan_id;
        $paymentId = $request->payment_id;

        // Vérifier que le plan existe et est actif
        $plan = SubscriptionPlan::active()->find($subscriptionPlanId);
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan d\'abonnement non trouvé ou inactif',
            ], 404);
        }

        // Vérifier que le paiement existe et appartient à l'utilisateur
        $payment = Payment::where('id', $paymentId)
            ->where('user_id', $user->id)
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé',
            ], 404);
        }

        // Vérifier si la relation ternaire existe déjà
        $existingSubscription = UserSubscriptionPlan::where('user_id', $user->id)
            ->where('subscription_plan_id', $subscriptionPlanId)
            ->where('payment_id', $paymentId)
            ->first();

        if ($existingSubscription) {
            // La relation existe déjà, vérifier le statut du paiement
            if ($payment->isCompleted()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Abonnement déjà actif',
                    'data' => $this->formatSubscriptionResponse($existingSubscription),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Cet abonnement existe déjà mais le paiement n\'est pas confirmé',
                'payment_status' => $payment->status,
            ], 400);
        }

        // Vérifier le statut du paiement
        if (!$payment->isCompleted()) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non confirmé',
                'payment_status' => $payment->status,
            ], 400);
        }

        // Créer la relation ternaire (l'abonnement est actif car le paiement est complété)
        try {
            DB::beginTransaction();

            $userSubscription = UserSubscriptionPlan::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $subscriptionPlanId,
                'payment_id' => $paymentId,
            ]);

            // Charger les relations pour la réponse
            $userSubscription->load(['subscriptionPlan', 'payment']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Abonnement activé avec succès',
                'data' => $this->formatSubscriptionResponse($userSubscription),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'activation de l\'abonnement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/my-subscription",
     *     summary="Récupérer l'abonnement actif de l'utilisateur",
     *     description="Retourne l'abonnement actif de l'utilisateur connecté avec les détails du plan",
     *     tags={"Subscription Plans"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Abonnement actif trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Abonnement actif récupéré"),
     *             @OA\Property(property="has_active_subscription", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 nullable=true,
     *                 @OA\Property(property="subscription_id", type="integer"),
     *                 @OA\Property(property="plan", type="object"),
     *                 @OA\Property(property="payment", type="object"),
     *                 @OA\Property(property="starts_at", type="string", format="datetime"),
     *                 @OA\Property(property="ends_at", type="string", format="datetime"),
     *                 @OA\Property(property="is_active", type="boolean"),
     *                 @OA\Property(property="is_expired", type="boolean"),
     *                 @OA\Property(property="days_remaining", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */
    public function mySubscription(Request $request): JsonResponse
    {
        $user = $request->user();
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return response()->json([
                'success' => true,
                'message' => 'Aucun abonnement actif',
                'has_active_subscription' => false,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Abonnement actif récupéré',
            'has_active_subscription' => $subscription->isValid(),
            'data' => $this->formatSubscriptionResponse($subscription),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/my-subscriptions",
     *     summary="Historique des abonnements de l'utilisateur",
     *     description="Retourne l'historique de tous les abonnements de l'utilisateur connecté",
     *     tags={"Subscription Plans"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Historique des abonnements",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Historique des abonnements récupéré"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */
    public function mySubscriptions(Request $request): JsonResponse
    {
        $user = $request->user();
        $subscriptions = $user->userSubscriptionPlans()
            ->with(['subscriptionPlan', 'payment'])
            ->latest()
            ->get()
            ->map(fn($sub) => $this->formatSubscriptionResponse($sub));

        return response()->json([
            'success' => true,
            'message' => 'Historique des abonnements récupéré',
            'data' => $subscriptions,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/payments/init",
     *     summary="Initier un paiement pour un abonnement",
     *     description="Initie un paiement FreeMoPay pour souscrire à un plan d'abonnement",
     *     tags={"Subscription Plans"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"subscription_plan_id", "phone_number"},
     *             @OA\Property(
     *                 property="subscription_plan_id",
     *                 type="integer",
     *                 description="ID du plan d'abonnement",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="phone_number",
     *                 type="string",
     *                 description="Numéro de téléphone pour le paiement (format: 237XXXXXXXXX)",
     *                 example="237658895572"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paiement initié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Paiement initié avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="payment_id", type="integer", example=123),
     *                 @OA\Property(property="reference", type="string", example="FMP123456789"),
     *                 @OA\Property(property="amount", type="number", example=15000),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="subscription_plan_id", type="integer", example=1),
     *                 @OA\Property(property="plan_name", type="string", example="STARTER")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Données invalides ou erreur de paiement",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Numéro de téléphone invalide")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Plan d'abonnement non trouvé")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */
    public function initPayment(Request $request): JsonResponse
    {
        $request->validate([
            'subscription_plan_id' => 'required|integer|exists:subscription_plans,id',
            'phone_number' => 'required|string|min:12|max:15',
        ]);

        $user = $request->user();
        $subscriptionPlanId = $request->subscription_plan_id;
        $phoneNumber = $request->phone_number;

        // Vérifier que le plan existe et est actif
        $plan = SubscriptionPlan::active()->find($subscriptionPlanId);
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan d\'abonnement non trouvé ou inactif',
            ], 404);
        }

        try {
            // Initialiser le service de paiement
            $freemoPayService = new FreeMoPayService();

            // Description du paiement
            $description = "Abonnement {$plan->name} - Estuaire Emploie";

            // Initier le paiement (passer le plan comme payable)
            $payment = $freemoPayService->initPayment(
                $user,
                $plan->price,
                $phoneNumber,
                $description,
                "SUB-{$user->id}-{$plan->id}-" . now()->format('YmdHis'),
                $plan  // payable
            );

            Log::info("[SubscriptionPlanController] Payment initiated - Payment ID: {$payment->id}, Plan: {$plan->name}");

            return response()->json([
                'success' => true,
                'message' => 'Paiement initié avec succès. Veuillez confirmer sur votre téléphone.',
                'data' => [
                    'payment_id' => $payment->id,
                    'reference' => $payment->provider_reference,
                    'external_id' => $payment->external_id,
                    'amount' => $payment->amount,
                    'status' => $payment->status,
                    'subscription_plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error("[SubscriptionPlanController] Payment init failed: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/payments/{id}/status",
     *     summary="Vérifier le statut d'un paiement",
     *     description="Vérifie le statut actuel d'un paiement en cours",
     *     tags={"Subscription Plans"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du paiement",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut du paiement",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Statut récupéré"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="payment_id", type="integer", example=123),
     *                 @OA\Property(property="status", type="string", example="completed"),
     *                 @OA\Property(property="is_completed", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paiement non trouvé"
     *     )
     * )
     */
    public function checkPaymentStatus(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $payment = Payment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé',
            ], 404);
        }

        // Si le paiement est encore pending, vérifier avec FreeMoPay
        if ($payment->status === 'pending' && $payment->provider_reference) {
            try {
                $freemoPayService = new FreeMoPayService();
                $statusResponse = $freemoPayService->checkPaymentStatus($payment->provider_reference);

                $freemoStatus = $statusResponse['status'] ?? null;

                // Mettre à jour le statut local si nécessaire
                if ($freemoStatus === 'SUCCESS' && $payment->status !== 'completed') {
                    $payment->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                    ]);
                } elseif (in_array($freemoStatus, ['FAILED', 'CANCELLED', 'REJECTED'])) {
                    $payment->update(['status' => 'failed']);
                }

            } catch (\Exception $e) {
                Log::warning("[SubscriptionPlanController] Could not check payment status: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Statut récupéré',
            'data' => [
                'payment_id' => $payment->id,
                'reference' => $payment->provider_reference,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'is_completed' => $payment->isCompleted(),
                'paid_at' => $payment->paid_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Formate la réponse d'un abonnement
     */
    private function formatSubscriptionResponse(UserSubscriptionPlan $subscription): array
    {
        $subscription->loadMissing(['subscriptionPlan', 'payment']);

        $startsAt = $subscription->payment?->paid_at;
        $endsAt = $subscription->end_date;
        $daysRemaining = $endsAt ? now()->diffInDays($endsAt, false) : null;

        return [
            'subscription_id' => $subscription->id,
            'plan' => [
                'id' => $subscription->subscriptionPlan->id,
                'name' => $subscription->subscriptionPlan->name,
                'slug' => $subscription->subscriptionPlan->slug,
                'description' => $subscription->subscriptionPlan->description,
                'price' => $subscription->subscriptionPlan->price,
                'duration_days' => $subscription->subscriptionPlan->duration_days,
                'jobs_limit' => $subscription->subscriptionPlan->jobs_limit,
                'contacts_limit' => $subscription->subscriptionPlan->contacts_limit,
                'can_access_cvtheque' => $subscription->subscriptionPlan->can_access_cvtheque,
                'can_boost_jobs' => $subscription->subscriptionPlan->can_boost_jobs,
                'can_see_analytics' => $subscription->subscriptionPlan->can_see_analytics,
                'priority_support' => $subscription->subscriptionPlan->priority_support,
            ],
            'payment' => [
                'id' => $subscription->payment->id,
                'amount' => $subscription->payment->amount,
                'status' => $subscription->payment->status,
                'payment_method' => $subscription->payment->payment_method,
                'paid_at' => $subscription->payment->paid_at?->toIso8601String(),
            ],
            'starts_at' => $startsAt?->toIso8601String(),
            'ends_at' => $endsAt?->toIso8601String(),
            'is_active' => $subscription->isActive(),
            'is_expired' => $subscription->isExpired(),
            'is_valid' => $subscription->isValid(),
            'days_remaining' => max(0, $daysRemaining ?? 0),
            'created_at' => $subscription->created_at?->toIso8601String() ?? '-',
        ];
    }
}