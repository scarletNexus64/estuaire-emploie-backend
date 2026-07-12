<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamPack;
use App\Models\PackPurchase;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamPackApiController extends Controller
{
    /**
     * Décore un pack avec le prix d'affichage dans la devise du user, dérivé de
     * `price_xaf` (base, source de vérité). `display_price_formatted` est prêt à
     * afficher. En promo gratuite, le prix affiché est 0.
     */
    private function decoratePackDisplayPrice($pack): void
    {
        $currency = app(\App\Services\CurrencyService::class);
        $target = $currency->resolveCurrency(auth('sanctum')->user());

        $baseXaf = ($pack->is_promotional ?? false)
            ? (float) ($pack->promotional_price ?? 0)
            : (float) $pack->price_xaf;

        $display = $currency->displayFor($baseXaf, $target);
        $pack->base_currency = $display['base_currency'];
        $pack->display_currency = $display['display_currency'];
        $pack->display_price = $display['display_price'];
        $pack->display_price_formatted = $display['display_price_formatted'];
    }

    /**
     * Liste des packs d'épreuves disponibles
     */
    public function index(Request $request)
    {
        $query = ExamPack::with('examPapers')
                         ->withCount('examPapers')
                         ->active();

        // Filtres
        if ($request->filled('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrer les packs mis en avant si demandé
        if ($request->boolean('featured')) {
            $query->featured();
        }

        $perPage = $request->input('per_page', 12);
        $packs = $query->orderBy('display_order')
                      ->orderBy('created_at', 'desc')
                      ->paginate($perPage);

        // Ajouter les informations d'achat et promotionnelles pour l'utilisateur connecté.
        // Lecture publique (mode vitrine) : token optionnel résolu via le guard sanctum.
        if (auth('sanctum')->check()) {
            $userId = auth('sanctum')->id();
            $user = auth('sanctum')->user();
            $isStudent = $user->hasStudentMode();

            $packs->getCollection()->transform(function ($pack) use ($userId, $isStudent) {
                $pack->is_purchased = PackPurchase::where('user_id', $userId)
                                                  ->where('exam_pack_id', $pack->id)
                                                  ->where('pack_type', 'exam')
                                                  ->active()
                                                  ->exists();
                $pack->is_free_for_student = $isStudent;

                // Vérifier si le pack est en promotion
                $promotion = $pack->getActivePromotion();
                if ($promotion) {
                    $pack->is_promotional = true;
                    $pack->promotional_price = 0;
                    $pack->promotion_id = $promotion->id;
                    $pack->promotion_name = $promotion->name;
                    $pack->promotion_end_date = $promotion->end_date->toDateTimeString();
                    $pack->promotion_remaining_days = $promotion->remaining_days;
                    $pack->promotion_usage_duration_days = $promotion->usage_duration_days;
                } else {
                    $pack->is_promotional = false;
                    $pack->promotional_price = null;
                }

                $this->decoratePackDisplayPrice($pack);

                return $pack;
            });
        } else {
            // Même si l'utilisateur n'est pas connecté, afficher les infos promo
            $packs->getCollection()->transform(function ($pack) {
                $promotion = $pack->getActivePromotion();
                if ($promotion) {
                    $pack->is_promotional = true;
                    $pack->promotional_price = 0;
                    $pack->promotion_id = $promotion->id;
                    $pack->promotion_name = $promotion->name;
                    $pack->promotion_end_date = $promotion->end_date->toDateTimeString();
                    $pack->promotion_remaining_days = $promotion->remaining_days;
                    $pack->promotion_usage_duration_days = $promotion->usage_duration_days;
                } else {
                    $pack->is_promotional = false;
                    $pack->promotional_price = null;
                }
                $this->decoratePackDisplayPrice($pack);
                return $pack;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $packs,
        ]);
    }

    /**
     * Détails d'un pack d'épreuves
     */
    public function show($id)
    {
        $pack = ExamPack::with(['examPapers' => function ($query) {
                            $query->orderBy('exam_pack_papers.display_order');
                        }, 'examPapers.correctionPaper'])
                        ->withCount('examPapers')
                        ->findOrFail($id);

        // Incrémenter les vues
        $pack->incrementViews();

        // Vérifier si l'utilisateur a acheté ce pack
        $isPurchased = false;
        $isFreeForStudent = false;
        // Lecture publique (mode vitrine) : token optionnel via le guard sanctum.
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            $isPurchased = PackPurchase::where('user_id', auth('sanctum')->id())
                                       ->where('exam_pack_id', $pack->id)
                                       ->where('pack_type', 'exam')
                                       ->active()
                                       ->exists();
            $isFreeForStudent = $user->hasStudentMode();
        }

        // Mode vitrine : exposer is_preview au top level sur chaque épreuve
        // et identifier la 1ère épreuve preview du pack (preview_paper_id)
        $previewPaperId = null;
        foreach ($pack->examPapers as $paper) {
            $paper->is_preview = (bool) ($paper->pivot->is_preview ?? false);
            if ($previewPaperId === null && $paper->is_preview) {
                $previewPaperId = $paper->id;
            }
        }

        // Vérifier si le pack est en promotion
        $promotionData = null;
        $promotion = $pack->getActivePromotion();
        if ($promotion) {
            $promotionData = [
                'id' => $promotion->id,
                'name' => $promotion->name,
                'description' => $promotion->description,
                'promotional_price' => 0,
                'end_date' => $promotion->end_date->toDateTimeString(),
                'remaining_days' => $promotion->remaining_days,
                'usage_duration_days' => $promotion->usage_duration_days,
                'max_activations' => $promotion->max_activations,
                'remaining_activations' => $promotion->remaining_activations,
            ];
        }

        // Prix d'affichage dans la devise du user (base = price_xaf ou promo).
        $pack->is_promotional = $promotion !== null;
        $pack->promotional_price = $promotion !== null ? 0 : null;
        $this->decoratePackDisplayPrice($pack);

        return response()->json([
            'success' => true,
            'data' => [
                'pack' => $pack,
                'is_purchased' => $isPurchased,
                'is_free_for_student' => $isFreeForStudent,
                'preview_paper_id' => $previewPaperId,
                'is_promotional' => $promotion !== null,
                'promotion' => $promotionData,
            ],
        ]);
    }

    /**
     * Filtres disponibles
     */
    public function filters()
    {
        $specialties = ExamPack::active()
                               ->distinct()
                               ->pluck('specialty')
                               ->filter()
                               ->values();

        $years = ExamPack::active()
                         ->distinct()
                         ->pluck('year')
                         ->filter()
                         ->sort()
                         ->values();

        $examTypes = ExamPack::active()
                             ->distinct()
                             ->pluck('exam_type')
                             ->filter()
                             ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'specialties' => $specialties,
                'years' => $years,
                'exam_types' => $examTypes,
            ],
        ]);
    }

    /**
     * Acheter un pack d'épreuves
     */
    public function purchase(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:wallet',
            'currency' => 'nullable|in:XAF,USD,EUR',
            'payment_provider' => 'nullable|string|in:kpay,freemopay,paypal',
        ]);

        $pack = ExamPack::findOrFail($id);
        $user = Auth::user();
        $currency = $request->input('currency', 'XAF');
        $paymentProvider = $request->input('payment_provider', 'kpay');

        // Vérifier si l'utilisateur a déjà acheté ce pack
        $existingPurchase = PackPurchase::where('user_id', $user->id)
                                        ->where('exam_pack_id', $pack->id)
                                        ->where('pack_type', 'exam')
                                        ->active()
                                        ->first();

        if ($existingPurchase) {
            return response()->json([
                'success' => false,
                'message' => __('exam_pack.already_purchased'),
            ], 400);
        }

        // Vérifier si l'utilisateur est un étudiant (a le Mode Étudiant actif)
        $isStudent = $user->hasStudentMode();

        // Prix d'AFFICHAGE (devise demandée) — pour le record d'achat uniquement.
        $price = $pack->getPrice($currency);

        // Prix de DÉBIT : toujours en XAF, car les wallets (freemopay/paypal) sont
        // libellés en XAF. Débiter price_usd/price_eur sur un solde XAF serait un
        // sous/sur-paiement (money-critical). Source de vérité = price_xaf.
        $priceXaf = $pack->getPrice('XAF');

        if ($price <= 0 && !$isStudent) {
            return response()->json([
                'success' => false,
                'message' => __('exam_pack.invalid_price'),
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Si l'utilisateur est un étudiant, le pack est GRATUIT
            if ($isStudent) {
                // Créer l'achat du pack GRATUITEMENT
                $purchase = PackPurchase::create([
                    'user_id' => $user->id,
                    'pack_type' => 'exam',
                    'exam_pack_id' => $pack->id,
                    'amount_paid' => 0,
                    'currency' => $currency,
                    'payment_method' => 'free_student',
                    'status' => 'completed',
                    'purchased_at' => now(),
                ]);

                // Incrémenter le compteur d'achats du pack
                $pack->incrementPurchases();

                DB::commit();

                // Envoyer notification FCM pour l'achat gratuit
                $this->sendPurchaseNotification($user, $pack, 0, 'student_mode');

                return response()->json([
                    'success' => true,
                    'message' => __('exam_pack.free_with_student_mode'),
                    'data' => [
                        'purchase' => $purchase,
                        'pack' => $pack,
                        'is_free' => true,
                    ],
                ]);
            }

            // Sinon, procéder au paiement normal

            // Déterminer le champ wallet à utiliser
            $walletField = $paymentProvider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';

            // Vérifier le solde du wallet sélectionné (comparaison en XAF)
            $currentBalance = $user->{$walletField} ?? 0;
            if ($currentBalance < $priceXaf) {
                return response()->json([
                    'success' => false,
                    'message' => __('exam_pack.insufficient_wallet', ['provider' => ucfirst($paymentProvider)]),
                    'required' => $priceXaf,
                    'available' => $currentBalance,
                ], 400);
            }

            // Calculer le nouveau solde (en XAF)
            $balanceBefore = $currentBalance;
            $balanceAfter = $currentBalance - $priceXaf;

            // Débiter le wallet sélectionné (montant XAF)
            $user->decrement($walletField, $priceXaf);

            // Créer la transaction wallet (montant XAF, cohérent avec le solde)
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $priceXaf,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => "Achat du pack d'épreuves: {$pack->name}",
                'reference_type' => 'App\\Models\\ExamPack',
                'reference_id' => $pack->id,
                'status' => 'completed',
                'payment_provider' => $paymentProvider,
            ]);

            // Créer l'achat du pack
            $purchase = PackPurchase::create([
                'user_id' => $user->id,
                'pack_type' => 'exam',
                'exam_pack_id' => $pack->id,
                'amount_paid' => $price,
                'currency' => $currency,
                'payment_method' => 'wallet',
                'status' => 'completed',
                'purchased_at' => now(),
            ]);

            // Incrémenter le compteur d'achats du pack
            $pack->incrementPurchases();

            DB::commit();

            // Envoyer notification FCM pour l'achat (montant XAF réellement débité)
            $this->sendPurchaseNotification($user, $pack, $priceXaf, $paymentProvider);

            return response()->json([
                'success' => true,
                'message' => __('exam_pack.purchased'),
                'data' => [
                    'purchase' => $purchase,
                    'pack' => $pack,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => __('exam_pack.purchase_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Envoie une notification FCM pour un achat de pack d'épreuves
     */
    protected function sendPurchaseNotification($user, ExamPack $pack, float $price, string $paymentProvider): void
    {
        try {
            if (!$user->fcm_token) {
                return;
            }

            $providerName = $paymentProvider === 'paypal' ? 'PayPal' : 'FreeMoPay';
            $title = "Pack d'épreuves acheté";
            $body = "Votre pack d'épreuves {$pack->name} pour " . number_format($price, 0, ',', ' ') . " FCFA via wallet {$providerName} a été acheté avec succès.";

            // Créer la notification avec la structure correcte
            $notification = \App\Models\Notification::create([
                'type' => 'exam_pack_purchase',
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'pack_name' => $pack->name,
                    'pack_id' => $pack->id,
                    'amount' => $price,
                    'provider' => $paymentProvider,
                ],
            ]);

            // Envoyer via FCM (API HTTP v1 via le SDK Firebase)
            app(\App\Services\FirebaseNotificationService::class)->sendToToken(
                $user->fcm_token,
                $title,
                $body,
                [
                    'type' => 'exam_pack_purchase',
                    'pack_name' => (string) $pack->name,
                    'notification_id' => (string) $notification->id,
                ]
            );

            \Log::info("[ExamPackApiController] ✅ FCM notification sent for pack purchase", [
                'user_id' => $user->id,
                'pack_name' => $pack->name,
                'amount' => $price,
                'provider' => $paymentProvider,
            ]);

        } catch (\Exception $e) {
            \Log::error("[ExamPackApiController] ❌ Failed to send FCM notification: " . $e->getMessage());
        }
    }

    /**
     * Mes packs d'épreuves achetés
     */
    public function myPurchases()
    {
        $user = Auth::user();

        $purchases = PackPurchase::with('examPack.examPapers')
                                 ->where('user_id', $user->id)
                                 ->where('pack_type', 'exam')
                                 ->active()
                                 ->latest('purchased_at')
                                 ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $purchases,
        ]);
    }

    /**
     * Vérifier si l'utilisateur a accès à un pack
     */
    public function checkAccess($id)
    {
        $user = Auth::user();

        $hasAccess = PackPurchase::where('user_id', $user->id)
                                 ->where('exam_pack_id', $id)
                                 ->where('pack_type', 'exam')
                                 ->active()
                                 ->exists();

        return response()->json([
            'success' => true,
            'has_access' => $hasAccess,
        ]);
    }
}
