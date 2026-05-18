<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackPromotion;
use App\Models\ExamPack;
use App\Models\TrainingPack;
use App\Models\StoragePack;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class PackPromotionController extends Controller
{
    /**
     * Liste toutes les promotions
     */
    public function index(Request $request)
    {
        $query = PackPromotion::with(['promotionable', 'creator']);

        // Filtrer par type de pack
        if ($request->filled('type')) {
            $query->forType($request->type);
        }

        // Filtrer par statut
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->active()->current();
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'expired':
                    $query->where('end_date', '<', now());
                    break;
                case 'scheduled':
                    $query->where('start_date', '>', now());
                    break;
            }
        }

        $promotions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $promotionableTypes = PackPromotion::getPromotionableTypes();

        return view('admin.promotions.create', compact('promotionableTypes'));
    }

    /**
     * Enregistre une nouvelle promotion
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'promotionable_type' => 'required|string|in:App\Models\ExamPack,App\Models\TrainingPack,App\Models\StoragePack,App\Models\SubscriptionPlan',
            'promotionable_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_duration_days' => 'required|integer|min:1|max:3650',
            'max_activations' => 'nullable|integer|min:1',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['created_by'] = Auth::id();
        $validated['current_activations'] = 0;

        // Vérifier que le pack existe
        $pack = $this->getPackByTypeAndId($validated['promotionable_type'], $validated['promotionable_id']);

        if (!$pack) {
            return back()->withErrors(['promotionable_id' => 'Le pack sélectionné n\'existe pas.'])->withInput();
        }

        // Créer la promotion
        $promotion = PackPromotion::create($validated);

        // Envoyer notification FCM à tous les utilisateurs via topic
        if ($validated['is_active']) {
            $this->sendPromotionNotificationToAll($promotion, $pack);
        }

        return redirect()->route('admin.pack-promotions.index')
            ->with('success', 'Promotion créée avec succès' . ($validated['is_active'] ? ' et notification envoyée à tous les utilisateurs.' : '.'));
    }

    /**
     * Affiche une promotion
     */
    public function show($id)
    {
        $promotion = PackPromotion::with(['promotionable', 'activations.user', 'creator'])->findOrFail($id);

        return view('admin.promotions.show', compact('promotion'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit($id)
    {
        $promotion = PackPromotion::with('promotionable')->findOrFail($id);
        $promotionableTypes = PackPromotion::getPromotionableTypes();

        return view('admin.promotions.edit', compact('promotion', 'promotionableTypes'));
    }

    /**
     * Met à jour une promotion
     */
    public function update(Request $request, $id)
    {
        $promotion = PackPromotion::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'promotionable_type' => 'required|string|in:App\Models\ExamPack,App\Models\TrainingPack,App\Models\StoragePack,App\Models\SubscriptionPlan',
            'promotionable_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_duration_days' => 'required|integer|min:1|max:3650',
            'max_activations' => 'nullable|integer|min:1',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Vérifier que le pack existe
        $pack = $this->getPackByTypeAndId($validated['promotionable_type'], $validated['promotionable_id']);

        if (!$pack) {
            return back()->withErrors(['promotionable_id' => 'Le pack sélectionné n\'existe pas.'])->withInput();
        }

        // Si la promotion vient d'être activée, envoyer notification
        $wasInactive = !$promotion->is_active;
        $nowActive = $validated['is_active'];

        $promotion->update($validated);

        // Envoyer notification si la promotion vient d'être activée
        if ($wasInactive && $nowActive) {
            $this->sendPromotionNotificationToAll($promotion->fresh(), $pack);
            $successMessage = 'Promotion modifiée avec succès et notification envoyée à tous les utilisateurs.';
        } else {
            $successMessage = 'Promotion modifiée avec succès.';
        }

        return redirect()->route('admin.pack-promotions.index')
            ->with('success', $successMessage);
    }

    /**
     * Supprime une promotion (soft delete)
     */
    public function destroy($id)
    {
        $promotion = PackPromotion::findOrFail($id);
        $promotion->delete();

        return redirect()->route('admin.pack-promotions.index')
            ->with('success', 'Promotion supprimée avec succès.');
    }

    /**
     * Active/désactive rapidement une promotion
     */
    public function toggleActive($id)
    {
        $promotion = PackPromotion::with('promotionable')->findOrFail($id);
        $promotion->is_active = !$promotion->is_active;
        $promotion->save();

        // Si la promotion vient d'être activée, envoyer notification
        if ($promotion->is_active) {
            $pack = $promotion->promotionable;
            $this->sendPromotionNotificationToAll($promotion, $pack);
            $message = 'Promotion activée avec succès et notification envoyée.';
        } else {
            $message = 'Promotion désactivée avec succès.';
        }

        return back()->with('success', $message);
    }

    /**
     * Affiche les statistiques d'une promotion
     */
    public function stats($id)
    {
        $promotion = PackPromotion::with(['promotionable', 'activations.user'])->findOrFail($id);

        // Statistiques
        $totalActivations = $promotion->activations()->count();
        $activeActivations = $promotion->activations()->active()->count();
        $expiredActivations = $promotion->activations()->expired()->count();

        // Activations par jour
        $activationsByDay = $promotion->activations()
            ->selectRaw('DATE(activated_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Dernières activations
        $recentActivations = $promotion->activations()
            ->with('user')
            ->orderBy('activated_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.promotions.stats', compact(
            'promotion',
            'totalActivations',
            'activeActivations',
            'expiredActivations',
            'activationsByDay',
            'recentActivations'
        ));
    }

    /**
     * API AJAX: Récupère les packs par type
     */
    public function getPacksByType(Request $request, $type)
    {
        $packs = collect();

        switch ($type) {
            case 'App\Models\ExamPack':
                $packs = ExamPack::select('id', 'name', 'price_xaf')
                    ->active()
                    ->orderBy('name')
                    ->get()
                    ->map(function ($pack) {
                        return [
                            'id' => $pack->id,
                            'name' => $pack->name,
                            'price' => $pack->price_xaf . ' F XAF',
                        ];
                    });
                break;

            case 'App\Models\TrainingPack':
                $packs = TrainingPack::select('id', 'name', 'price_xaf')
                    ->active()
                    ->orderBy('name')
                    ->get()
                    ->map(function ($pack) {
                        return [
                            'id' => $pack->id,
                            'name' => $pack->name,
                            'price' => $pack->price_xaf . ' F XAF',
                        ];
                    });
                break;

            case 'App\Models\StoragePack':
                $packs = StoragePack::select('id', 'name', 'price')
                    ->active()
                    ->orderBy('name')
                    ->get()
                    ->map(function ($pack) {
                        return [
                            'id' => $pack->id,
                            'name' => $pack->name,
                            'price' => $pack->price . ' F',
                        ];
                    });
                break;

            case 'App\Models\SubscriptionPlan':
                $packs = SubscriptionPlan::select('id', 'name', 'price')
                    ->active()
                    ->orderBy('name')
                    ->get()
                    ->map(function ($pack) {
                        return [
                            'id' => $pack->id,
                            'name' => $pack->name,
                            'price' => $pack->price . ' F',
                        ];
                    });
                break;
        }

        return response()->json($packs);
    }

    /**
     * Récupère un pack par type et ID
     */
    private function getPackByTypeAndId($type, $id)
    {
        return match ($type) {
            'App\Models\ExamPack' => ExamPack::find($id),
            'App\Models\TrainingPack' => TrainingPack::find($id),
            'App\Models\StoragePack' => StoragePack::find($id),
            'App\Models\SubscriptionPlan' => SubscriptionPlan::find($id),
            default => null,
        };
    }

    /**
     * Envoie une notification FCM à tous les utilisateurs via topic
     */
    private function sendPromotionNotificationToAll(PackPromotion $promotion, $pack)
    {
        try {
            // Récupérer le chemin du fichier Firebase credentials
            $firebaseCredentials = storage_path('app/firebase/estuaire-emploi-firebase-adminsdk-yh05q-9b5584d7c2.json');

            if (!file_exists($firebaseCredentials)) {
                Log::error('Firebase credentials file not found: ' . $firebaseCredentials);
                return;
            }

            // Initialiser Firebase
            $factory = (new Factory)->withServiceAccount($firebaseCredentials);
            $messaging = $factory->createMessaging();

            // Nom du pack
            $packName = $pack->name ?? 'un pack';

            // Créer le message de notification
            $notification = FirebaseNotification::create(
                '🎁 Nouvelle Promotion Gratuite!',
                "Le pack \"{$packName}\" est maintenant GRATUIT! Profitez-en dès maintenant."
            );

            // Données supplémentaires
            $data = [
                'type' => 'promotion',
                'promotion_id' => (string) $promotion->id,
                'pack_type' => $promotion->promotionable_type,
                'pack_id' => (string) $promotion->promotionable_id,
                'pack_name' => $packName,
                'end_date' => $promotion->end_date->format('Y-m-d H:i:s'),
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ];

            // Envoyer à tous les utilisateurs via le topic 'all_users'
            $message = CloudMessage::withTarget('topic', 'all_users')
                ->withNotification($notification)
                ->withData($data);

            $messaging->send($message);

            Log::info('Promotion notification sent to all users via topic', [
                'promotion_id' => $promotion->id,
                'pack_name' => $packName,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send promotion notification: ' . $e->getMessage(), [
                'promotion_id' => $promotion->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
