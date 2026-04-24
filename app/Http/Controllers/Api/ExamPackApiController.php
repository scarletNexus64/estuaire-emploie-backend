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

        // Ajouter les informations d'achat pour l'utilisateur connecté
        if (Auth::check()) {
            $userId = Auth::id();
            $user = Auth::user();
            $isStudent = $user->isStudent();

            $packs->getCollection()->transform(function ($pack) use ($userId, $isStudent) {
                $pack->is_purchased = PackPurchase::where('user_id', $userId)
                                                  ->where('exam_pack_id', $pack->id)
                                                  ->where('pack_type', 'exam')
                                                  ->active()
                                                  ->exists();
                $pack->is_free_for_student = $isStudent;
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
        if (Auth::check()) {
            $user = Auth::user();
            $isPurchased = PackPurchase::where('user_id', Auth::id())
                                       ->where('exam_pack_id', $pack->id)
                                       ->where('pack_type', 'exam')
                                       ->active()
                                       ->exists();
            $isFreeForStudent = $user->isStudent();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'pack' => $pack,
                'is_purchased' => $isPurchased,
                'is_free_for_student' => $isFreeForStudent,
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
            'payment_provider' => 'nullable|string|in:freemopay,paypal',
        ]);

        $pack = ExamPack::findOrFail($id);
        $user = Auth::user();
        $currency = $request->input('currency', 'XAF');
        $paymentProvider = $request->input('payment_provider', 'freemopay');

        // Vérifier si l'utilisateur a déjà acheté ce pack
        $existingPurchase = PackPurchase::where('user_id', $user->id)
                                        ->where('exam_pack_id', $pack->id)
                                        ->where('pack_type', 'exam')
                                        ->active()
                                        ->first();

        if ($existingPurchase) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà acheté ce pack',
            ], 400);
        }

        // Vérifier si l'utilisateur est un étudiant (a le Mode Étudiant actif)
        $isStudent = $user->isStudent();

        // Obtenir le prix dans la devise demandée
        $price = $pack->getPrice($currency);

        if ($price <= 0 && !$isStudent) {
            return response()->json([
                'success' => false,
                'message' => 'Prix invalide pour ce pack',
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
                    'message' => 'Pack obtenu gratuitement avec le Mode Étudiant',
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

            // Vérifier le solde du wallet sélectionné
            $currentBalance = $user->{$walletField} ?? 0;
            if ($currentBalance < $price) {
                return response()->json([
                    'success' => false,
                    'message' => "Solde insuffisant dans votre wallet " . ucfirst($paymentProvider),
                    'required' => $price,
                    'available' => $currentBalance,
                ], 400);
            }

            // Calculer le nouveau solde
            $balanceBefore = $currentBalance;
            $balanceAfter = $currentBalance - $price;

            // Débiter le wallet sélectionné
            $user->decrement($walletField, $price);

            // Créer la transaction wallet
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $price,
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

            // Envoyer notification FCM pour l'achat
            $this->sendPurchaseNotification($user, $pack, $price, $paymentProvider);

            return response()->json([
                'success' => true,
                'message' => 'Pack acheté avec succès',
                'data' => [
                    'purchase' => $purchase,
                    'pack' => $pack,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'achat du pack',
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
                        'type' => 'exam_pack_purchase',
                        'pack_name' => $pack->name,
                        'notification_id' => $notification->id,
                    ],
                ]);

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
