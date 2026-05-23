<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PackPromotion;
use App\Models\PackPromotionActivation;
use App\Models\ExamPack;
use App\Models\TrainingPack;
use App\Models\StoragePack;
use App\Models\SubscriptionPlan;
use App\Models\PackPurchase;
use App\Models\UserStoragePack;
use App\Models\UserSubscriptionPlan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackPromotionApiController extends Controller
{
    /**
     * Liste des packs actuellement en promotion (0F)
     */
    public function getActivePromotions(Request $request)
    {
        try {
            $query = PackPromotion::with('promotionable')->available();

            // Filtrer par type si spécifié
            if ($request->filled('type')) {
                $typeMap = [
                    'exam' => 'App\Models\ExamPack',
                    'training' => 'App\Models\TrainingPack',
                    'storage' => 'App\Models\StoragePack',
                    'subscription' => 'App\Models\SubscriptionPlan',
                ];

                if (isset($typeMap[$request->type])) {
                    $query->forType($typeMap[$request->type]);
                }
            }

            $promotions = $query->orderBy('created_at', 'desc')->get();

            $result = $promotions->map(function ($promotion) {
                $pack = $promotion->promotionable;

                if (!$pack) {
                    return null;
                }

                return [
                    'id' => $promotion->id,
                    'name' => $promotion->name,
                    'description' => $promotion->description,
                    'pack_type' => $this->getPackTypeSlug($promotion->promotionable_type),
                    'pack_type_name' => $promotion->pack_type_name,
                    'pack_id' => $promotion->promotionable_id,
                    'pack_name' => $pack->name,
                    'pack_data' => $this->formatPackData($pack, $promotion->promotionable_type),
                    'start_date' => $promotion->start_date->toDateTimeString(),
                    'end_date' => $promotion->end_date->toDateTimeString(),
                    'remaining_days' => $promotion->remaining_days,
                    'usage_duration_days' => $promotion->usage_duration_days,
                    'max_activations' => $promotion->max_activations,
                    'current_activations' => $promotion->current_activations,
                    'remaining_activations' => $promotion->remaining_activations,
                    'is_available' => $promotion->isAvailable(),
                ];
            })->filter();

            return response()->json([
                'success' => true,
                'message' => __('pack_promotion.active_promotions_fetched'),
                'data' => $result->values(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching active promotions: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('pack_promotion.fetch_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vérifie si un pack spécifique est en promotion
     */
    public function checkPromotion($type, $id)
    {
        try {
            $typeMap = [
                'exam' => 'App\Models\ExamPack',
                'training' => 'App\Models\TrainingPack',
                'storage' => 'App\Models\StoragePack',
                'subscription' => 'App\Models\SubscriptionPlan',
            ];

            if (!isset($typeMap[$type])) {
                return response()->json([
                    'success' => false,
                    'message' => __('pack_promotion.invalid_pack_type'),
                ], 400);
            }

            $fullType = $typeMap[$type];

            $promotion = PackPromotion::available()
                ->where('promotionable_type', $fullType)
                ->where('promotionable_id', $id)
                ->first();

            if (!$promotion) {
                return response()->json([
                    'success' => true,
                    'is_promotional' => false,
                    'data' => null,
                ]);
            }

            $user = Auth::user();
            $canActivate = $user ? $promotion->canActivate($user) : false;
            $hasActivated = false;

            if ($user) {
                $hasActivated = PackPromotionActivation::where('pack_promotion_id', $promotion->id)
                    ->where('user_id', $user->id)
                    ->exists();
            }

            return response()->json([
                'success' => true,
                'is_promotional' => true,
                'data' => [
                    'promotion_id' => $promotion->id,
                    'name' => $promotion->name,
                    'description' => $promotion->description,
                    'end_date' => $promotion->end_date->toDateTimeString(),
                    'remaining_days' => $promotion->remaining_days,
                    'usage_duration_days' => $promotion->usage_duration_days,
                    'can_activate' => $canActivate,
                    'has_activated' => $hasActivated,
                    'remaining_activations' => $promotion->remaining_activations,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking promotion: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('pack_promotion.check_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Active une promotion pour l'utilisateur connecté
     */
    public function activatePromotion($promotionId)
    {
        try {
            $user = Auth::user();
            $promotion = PackPromotion::with('promotionable')->findOrFail($promotionId);

            // Vérifier si l'utilisateur peut activer cette promotion
            if (!$promotion->canActivate($user)) {
                return response()->json([
                    'success' => false,
                    'message' => __('pack_promotion.cannot_activate'),
                ], 400);
            }

            DB::beginTransaction();

            try {
                // Activer la promotion (crée PackPromotionActivation)
                $activation = $promotion->activate($user);

                // Créer l'achat/souscription correspondant avec amount = 0
                $this->createFreePackAccess($promotion, $user, $activation);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => __('pack_promotion.activated', ['date' => $activation->expires_at->format('d/m/Y')]),
                    'data' => [
                        'activation_id' => $activation->id,
                        'activated_at' => $activation->activated_at->toDateTimeString(),
                        'expires_at' => $activation->expires_at->toDateTimeString(),
                        'remaining_days' => $activation->remaining_days,
                        'pack_name' => $promotion->pack_name,
                        'pack_type' => $this->getPackTypeSlug($promotion->promotionable_type),
                    ],
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error activating promotion: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'promotion_id' => $promotionId,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('pack_promotion.activation_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mes promotions activées
     */
    public function myActivations()
    {
        try {
            $user = Auth::user();

            $activations = PackPromotionActivation::with('promotion.promotionable')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $result = $activations->map(function ($activation) {
                $promotion = $activation->promotion;
                $pack = $promotion->promotionable ?? null;

                return [
                    'activation_id' => $activation->id,
                    'promotion_id' => $promotion->id,
                    'promotion_name' => $promotion->name,
                    'pack_type' => $this->getPackTypeSlug($promotion->promotionable_type),
                    'pack_name' => $pack ? $pack->name : 'Pack supprimé',
                    'activated_at' => $activation->activated_at->toDateTimeString(),
                    'expires_at' => $activation->expires_at->toDateTimeString(),
                    'is_expired' => $activation->isExpired(),
                    'remaining_days' => $activation->remaining_days,
                    'remaining_hours' => $activation->remaining_hours,
                    'status' => $activation->status,
                    'percentage_used' => round($activation->percentage_used, 1),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => __('pack_promotion.activations_fetched'),
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching user activations: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('pack_promotion.activations_fetch_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crée l'accès gratuit au pack pour l'utilisateur
     */
    private function createFreePackAccess(PackPromotion $promotion, $user, PackPromotionActivation $activation)
    {
        $pack = $promotion->promotionable;

        // Créer un paiement "completed" avec amount 0
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => 0,
            'fees' => 0,
            'total' => 0,
            'status' => 'completed',
            'payment_method' => 'promotional_free',
            'transaction_reference' => 'PROMO_' . $promotion->id . '_' . $user->id . '_' . time(),
            'paid_at' => now(),
            'metadata' => json_encode([
                'promotion_id' => $promotion->id,
                'promotion_name' => $promotion->name,
                'activation_id' => $activation->id,
            ]),
        ]);

        switch ($promotion->promotionable_type) {
            case 'App\Models\ExamPack':
            case 'App\Models\TrainingPack':
                PackPurchase::create([
                    'user_id' => $user->id,
                    'exam_pack_id' => $promotion->promotionable_type === 'App\Models\ExamPack' ? $pack->id : null,
                    'training_pack_id' => $promotion->promotionable_type === 'App\Models\TrainingPack' ? $pack->id : null,
                    'payment_id' => $payment->id,
                    'amount' => 0,
                ]);
                break;

            case 'App\Models\StoragePack':
                UserStoragePack::create([
                    'user_id' => $user->id,
                    'storage_pack_id' => $pack->id,
                    'payment_id' => $payment->id,
                    'storage_mb' => $pack->storage_mb,
                    'used_mb' => 0,
                    'purchased_at' => $activation->activated_at,
                    'expires_at' => $activation->expires_at,
                ]);
                break;

            case 'App\Models\SubscriptionPlan':
                UserSubscriptionPlan::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $pack->id,
                    'payment_id' => $payment->id,
                    'started_at' => $activation->activated_at,
                    'expires_at' => $activation->expires_at,
                    'jobs_posted' => 0,
                    'contacts_viewed' => 0,
                ]);
                break;
        }

        Log::info('Free pack access created via promotion', [
            'user_id' => $user->id,
            'promotion_id' => $promotion->id,
            'pack_type' => $promotion->promotionable_type,
            'pack_id' => $pack->id,
            'expires_at' => $activation->expires_at,
        ]);
    }

    /**
     * Formatte les données du pack selon son type
     */
    private function formatPackData($pack, $type)
    {
        if (!$pack) {
            return null;
        }

        $baseData = [
            'id' => $pack->id,
            'name' => $pack->name,
            'description' => $pack->description ?? null,
        ];

        switch ($type) {
            case 'App\Models\ExamPack':
                return array_merge($baseData, [
                    'specialty' => $pack->specialty ?? null,
                    'year' => $pack->year ?? null,
                    'exam_type' => $pack->exam_type ?? null,
                    'cover_image' => $pack->cover_image_url ?? null,
                    'original_price_xaf' => $pack->price_xaf,
                ]);

            case 'App\Models\TrainingPack':
                return array_merge($baseData, [
                    'category' => $pack->category ?? null,
                    'level' => $pack->level ?? null,
                    'duration_hours' => $pack->duration_hours ?? null,
                    'instructor_name' => $pack->instructor_name ?? null,
                    'cover_image' => $pack->cover_image ?? null,
                    'original_price_xaf' => $pack->price_xaf,
                ]);

            case 'App\Models\StoragePack':
                return array_merge($baseData, [
                    'storage_mb' => $pack->storage_mb,
                    'duration_days' => $pack->duration_days,
                    'formatted_storage' => $pack->formatted_storage,
                    'formatted_duration' => $pack->formatted_duration,
                    'original_price' => $pack->price,
                ]);

            case 'App\Models\SubscriptionPlan':
                return array_merge($baseData, [
                    'plan_type' => $pack->plan_type,
                    'duration_days' => $pack->duration_days,
                    'jobs_limit' => $pack->jobs_limit,
                    'contacts_limit' => $pack->contacts_limit,
                    'features' => $pack->features ?? [],
                    'original_price' => $pack->price,
                ]);

            default:
                return $baseData;
        }
    }

    /**
     * Obtient le slug du type de pack (pour Flutter)
     */
    private function getPackTypeSlug($fullType)
    {
        return match ($fullType) {
            'App\Models\ExamPack' => 'exam',
            'App\Models\TrainingPack' => 'training',
            'App\Models\StoragePack' => 'storage',
            'App\Models\SubscriptionPlan' => 'subscription',
            default => 'unknown',
        };
    }
}
