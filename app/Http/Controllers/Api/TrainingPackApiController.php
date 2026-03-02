<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingPack;
use App\Models\PackPurchase;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrainingPackApiController extends Controller
{
    /**
     * Liste des packs de formation disponibles
     */
    public function index(Request $request)
    {
        $query = TrainingPack::with('trainingVideos')
                            ->withCount('trainingVideos')
                            ->active();

        // Filtres
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('instructor_name', 'like', "%{$search}%");
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
            $packs->getCollection()->transform(function ($pack) use ($userId) {
                $pack->is_purchased = PackPurchase::where('user_id', $userId)
                                                  ->where('training_pack_id', $pack->id)
                                                  ->where('pack_type', 'training')
                                                  ->active()
                                                  ->exists();
                return $pack;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $packs,
        ]);
    }

    /**
     * Détails d'un pack de formation
     */
    public function show($id)
    {
        $pack = TrainingPack::with(['trainingVideos' => function ($query) {
                                $query->orderBy('training_pack_videos.section_order')
                                      ->orderBy('training_pack_videos.display_order');
                            }])
                            ->withCount('trainingVideos')
                            ->findOrFail($id);

        // Incrémenter les vues
        $pack->incrementViews();

        // Vérifier si l'utilisateur a acheté ce pack
        $isPurchased = false;
        if (Auth::check()) {
            $isPurchased = PackPurchase::where('user_id', Auth::id())
                                       ->where('training_pack_id', $pack->id)
                                       ->where('pack_type', 'training')
                                       ->active()
                                       ->exists();
        }

        // Si non acheté, ne montrer que les vidéos en aperçu
        if (!$isPurchased) {
            $pack->trainingVideos = $pack->trainingVideos->where('is_preview', true);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'pack' => $pack,
                'is_purchased' => $isPurchased,
            ],
        ]);
    }

    /**
     * Filtres disponibles
     */
    public function filters()
    {
        $categories = TrainingPack::active()
                                  ->distinct()
                                  ->pluck('category')
                                  ->filter()
                                  ->values();

        $levels = TrainingPack::active()
                              ->distinct()
                              ->pluck('level')
                              ->filter()
                              ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categories,
                'levels' => $levels,
            ],
        ]);
    }

    /**
     * Acheter un pack de formation
     */
    public function purchase(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:wallet',
            'currency' => 'nullable|in:XAF,USD,EUR',
            'payment_provider' => 'nullable|string|in:freemopay,paypal',
        ]);

        $pack = TrainingPack::findOrFail($id);
        $user = Auth::user();
        $currency = $request->input('currency', 'XAF');
        $paymentProvider = $request->input('payment_provider', 'freemopay');

        // Vérifier si l'utilisateur a déjà acheté ce pack
        $existingPurchase = PackPurchase::where('user_id', $user->id)
                                        ->where('training_pack_id', $pack->id)
                                        ->where('pack_type', 'training')
                                        ->active()
                                        ->first();

        if ($existingPurchase) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà acheté ce pack de formation',
            ], 400);
        }

        // Obtenir le prix dans la devise demandée
        $price = $pack->getPrice($currency);

        if ($price <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Prix invalide pour ce pack',
            ], 400);
        }

        try {
            DB::beginTransaction();

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
                'description' => "Achat du pack de formation: {$pack->name}",
                'reference_type' => 'App\\Models\\TrainingPack',
                'reference_id' => $pack->id,
                'status' => 'completed',
                'payment_provider' => $paymentProvider,
            ]);

            // Créer l'achat du pack
            $purchase = PackPurchase::create([
                'user_id' => $user->id,
                'pack_type' => 'training',
                'training_pack_id' => $pack->id,
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
                'message' => 'Pack de formation acheté avec succès',
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
     * Envoie une notification FCM pour un achat de pack de formation
     */
    protected function sendPurchaseNotification($user, TrainingPack $pack, float $price, string $paymentProvider): void
    {
        try {
            if (!$user->fcm_token) {
                return;
            }

            $providerName = $paymentProvider === 'paypal' ? 'PayPal' : 'FreeMoPay';
            $title = "Pack de formation acheté";
            $body = "Votre pack de formation {$pack->name} pour " . number_format($price, 0, ',', ' ') . " FCFA via wallet {$providerName} a été acheté avec succès.";

            // Créer la notification avec la structure correcte
            $notification = \App\Models\Notification::create([
                'type' => 'training_pack_purchase',
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
                        'type' => 'training_pack_purchase',
                        'pack_name' => $pack->name,
                        'notification_id' => $notification->id,
                    ],
                ]);

            \Log::info("[TrainingPackApiController] ✅ FCM notification sent for pack purchase", [
                'user_id' => $user->id,
                'pack_name' => $pack->name,
                'amount' => $price,
                'provider' => $paymentProvider,
            ]);

        } catch (\Exception $e) {
            \Log::error("[TrainingPackApiController] ❌ Failed to send FCM notification: " . $e->getMessage());
        }
    }

    /**
     * Mes packs de formation achetés
     */
    public function myPurchases()
    {
        $user = Auth::user();

        $purchases = PackPurchase::with('trainingPack.trainingVideos')
                                 ->where('user_id', $user->id)
                                 ->where('pack_type', 'training')
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
                                 ->where('training_pack_id', $id)
                                 ->where('pack_type', 'training')
                                 ->active()
                                 ->exists();

        return response()->json([
            'success' => true,
            'has_access' => $hasAccess,
        ]);
    }

    /**
     * Voir une vidéo (retourne l'URL si l'utilisateur a accès)
     */
    public function viewVideo($packId, $videoId)
    {
        $user = Auth::user();

        // Vérifier l'accès au pack
        $hasAccess = PackPurchase::where('user_id', $user->id)
                                 ->where('training_pack_id', $packId)
                                 ->where('pack_type', 'training')
                                 ->active()
                                 ->exists();

        $video = \App\Models\TrainingVideo::findOrFail($videoId);

        // Autoriser les vidéos en aperçu pour tous
        if (!$hasAccess && !$video->is_preview) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez acheter ce pack pour accéder à cette vidéo',
            ], 403);
        }

        // Incrémenter les vues
        $video->incrementViews();

        return response()->json([
            'success' => true,
            'data' => [
                'video' => $video,
                'video_url' => $video->video_url,
            ],
        ]);
    }

    /**
     * Streamer une vidéo avec support des Range requests (optimisé pour mobile)
     */
    public function streamVideo($packId, $videoId)
    {
        $user = Auth::user();

        // Vérifier l'accès au pack
        $hasAccess = PackPurchase::where('user_id', $user->id)
                                 ->where('training_pack_id', $packId)
                                 ->where('pack_type', 'training')
                                 ->active()
                                 ->exists();

        $video = \App\Models\TrainingVideo::findOrFail($videoId);

        // Autoriser les vidéos en aperçu pour tous
        if (!$hasAccess && !$video->is_preview) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez acheter ce pack pour accéder à cette vidéo',
            ], 403);
        }

        // Vérifier que c'est une vidéo uploadée
        if ($video->video_type !== 'upload' || !$video->video_path) {
            return response()->json([
                'success' => false,
                'message' => 'Cette vidéo n\'est pas disponible en streaming',
            ], 400);
        }

        // Récupérer le chemin complet du fichier
        $path = storage_path('app/public/' . $video->video_path);

        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier vidéo introuvable',
            ], 404);
        }

        $size = filesize($path);
        $mimeType = 'video/mp4';

        // Gérer les Range requests pour le streaming
        $range = request()->header('Range');

        if ($range) {
            // Parse le header Range (ex: bytes=0-1023)
            preg_match('/bytes=(\d+)-(\d+)?/', $range, $matches);
            $start = intval($matches[1]);
            $end = isset($matches[2]) ? intval($matches[2]) : $size - 1;
            $length = $end - $start + 1;

            // Ouvrir le fichier et se positionner au début de la range
            $file = fopen($path, 'rb');
            fseek($file, $start);
            $data = fread($file, $length);
            fclose($file);

            return response($data, 206)
                ->header('Content-Type', $mimeType)
                ->header('Content-Length', $length)
                ->header('Content-Range', "bytes $start-$end/$size")
                ->header('Accept-Ranges', 'bytes')
                ->header('Cache-Control', 'public, max-age=31536000');
        }

        // Si pas de Range request, envoyer le fichier complet
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    /**
     * Streamer une vidéo (route publique) - Détecte automatiquement le pack
     */
    public function streamVideoPublic(Request $request, $videoId)
    {
        // Authentification via token dans l'URL ou header Authorization
        $user = Auth::user();

        // Si pas d'utilisateur via header, essayer le token dans l'URL
        if (!$user && $request->has('token')) {
            $token = $request->input('token');
            $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

            if ($tokenModel) {
                $user = $tokenModel->tokenable;
            }
        }

        $video = \App\Models\TrainingVideo::findOrFail($videoId);

        // Trouver tous les packs contenant cette vidéo
        $packIds = DB::table('training_pack_videos')
                    ->where('training_video_id', $video->id)
                    ->pluck('training_pack_id');

        // Vérifier si l'utilisateur a acheté au moins un pack contenant cette vidéo
        $hasAccess = false;

        if ($user && $packIds->isNotEmpty()) {
            $hasAccess = PackPurchase::where('user_id', $user->id)
                                     ->where('pack_type', 'training')
                                     ->whereIn('training_pack_id', $packIds)
                                     ->active()
                                     ->exists();
        }

        // Autoriser les vidéos en aperçu pour tous (même sans authentification)
        if (!$hasAccess && !$video->is_preview) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez acheter un pack contenant cette vidéo pour y accéder',
            ], 403);
        }

        // Vérifier que c'est une vidéo uploadée
        if ($video->video_type !== 'upload' || !$video->video_path) {
            return response()->json([
                'success' => false,
                'message' => 'Cette vidéo n\'est pas disponible en streaming',
            ], 400);
        }

        // Récupérer le chemin complet du fichier
        $path = storage_path('app/public/' . $video->video_path);

        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier vidéo introuvable',
            ], 404);
        }

        $size = filesize($path);
        $mimeType = 'video/mp4';

        // Gérer les Range requests pour le streaming
        $range = request()->header('Range');

        if ($range) {
            // Parse le header Range (ex: bytes=0-1023)
            preg_match('/bytes=(\d+)-(\d+)?/', $range, $matches);
            $start = intval($matches[1]);
            $end = isset($matches[2]) ? intval($matches[2]) : $size - 1;
            $length = $end - $start + 1;

            // Ouvrir le fichier et se positionner au début de la range
            $file = fopen($path, 'rb');
            fseek($file, $start);
            $data = fread($file, $length);
            fclose($file);

            return response($data, 206)
                ->header('Content-Type', $mimeType)
                ->header('Content-Length', $length)
                ->header('Content-Range', "bytes $start-$end/$size")
                ->header('Accept-Ranges', 'bytes')
                ->header('Cache-Control', 'public, max-age=31536000')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, HEAD, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Range, Authorization');
        }

        // Si pas de Range request, envoyer le fichier complet
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }
}
