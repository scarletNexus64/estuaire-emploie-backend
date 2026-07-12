<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonServicesConfig;
use App\Models\ManualSubscriptionAssignment;
use App\Models\Payment;
use App\Models\PremiumServiceConfig;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserAddonService;
use App\Models\UserPremiumService;
use App\Models\UserSubscriptionPlan;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ManualSubscriptionController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    /**
     * Afficher le formulaire d'attribution manuelle (pack ou service)
     */
    public function create()
    {
        $recruiterPlans = SubscriptionPlan::active()->where('plan_type', 'recruiter')->ordered()->get();
        $jobSeekerPlans = SubscriptionPlan::active()->where('plan_type', 'job_seeker')->ordered()->get();
        $premiumServices = PremiumServiceConfig::where('is_active', true)->orderBy('display_order')->get();
        $addonServices = AddonServicesConfig::where('is_active', true)->orderBy('display_order')->get();

        $recentAssignments = ManualSubscriptionAssignment::with([
            'user',
            'subscriptionPlan',
            'assignable',
            'assignedByAdmin',
        ])->latest()->take(10)->get();

        // Pré-remplissage si retour en erreur de validation
        $selectedUser = old('user_id') ? User::find(old('user_id')) : null;

        return view('admin.manual-subscriptions.create', compact(
            'recruiterPlans',
            'jobSeekerPlans',
            'premiumServices',
            'addonServices',
            'recentAssignments',
            'selectedUser'
        ));
    }

    /**
     * Recherche d'utilisateurs (AJAX) pour le composant de recherche.
     */
    public function searchUsers(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $users = User::where('role', '!=', 'admin')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'email', 'phone', 'role']);

        return response()->json(['data' => $users]);
    }

    /**
     * Attribuer manuellement un pack ou un service à un utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'item_type' => 'required|in:plan,premium_service,addon_service',
            'item_id' => 'required|integer',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($validated['user_id']);

            $result = match ($validated['item_type']) {
                'plan' => $this->assignPlan($user, (int) $validated['item_id'], $validated),
                'premium_service' => $this->assignPremiumService($user, (int) $validated['item_id'], $validated),
                'addon_service' => $this->assignAddonService($user, (int) $validated['item_id'], $validated),
            };

            $assignment = ManualSubscriptionAssignment::create([
                'user_id' => $user->id,
                'item_type' => $validated['item_type'],
                'assignable_type' => get_class($result['config']),
                'assignable_id' => $result['config']->id,
                'granted_type' => get_class($result['granted']),
                'granted_id' => $result['granted']->id,
                'subscription_plan_id' => $result['subscription_plan_id'] ?? null,
                'payment_id' => $result['payment']->id,
                'user_subscription_plan_id' => $result['user_subscription_plan_id'] ?? null,
                'assigned_by_admin_id' => auth()->id(),
                'reason' => $validated['reason'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Manual assignment created', [
                'assignment_id' => $assignment->id,
                'item_type' => $validated['item_type'],
                'user_id' => $user->id,
                'assigned_by' => auth()->id(),
            ]);

            DB::commit();

            // Push notification (hors transaction : un échec d'envoi ne doit pas annuler l'attribution)
            $this->notifyUser($user, $result);

            return redirect()
                ->route('admin.manual-subscriptions.create')
                ->with('success', $result['success_message']);

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Manual assignment failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Erreur lors de l'attribution : " . $e->getMessage());
        }
    }

    /**
     * Attribuer un plan d'abonnement (renouvelle si un abonnement de même type est actif).
     */
    private function assignPlan(User $user, int $planId, array $validated): array
    {
        $plan = SubscriptionPlan::active()->findOrFail($planId);

        $payment = $this->createManualPayment(
            $user,
            $plan,
            "Attribution manuelle du plan {$plan->name}",
            $validated['notes'] ?? null
        );

        // Un abonnement actif du MÊME type (recruteur/candidat) ? -> renouveler/prolonger
        $existing = UserSubscriptionPlan::forUser($user->id)
            ->whereHas('subscriptionPlan', fn ($q) => $q->where('plan_type', $plan->plan_type))
            ->valid()
            ->latest()
            ->first();

        if ($existing) {
            $existing->renew($plan, $payment);
            $userSubscriptionPlan = $existing;
            $verb = 'renouvelé/prolongé';
        } else {
            $userSubscriptionPlan = UserSubscriptionPlan::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'payment_id' => $payment->id,
            ]);
            $userSubscriptionPlan->activate();
            $verb = 'attribué';
        }

        $expires = $userSubscriptionPlan->expires_at?->format('d/m/Y');

        return [
            'config' => $plan,
            'granted' => $userSubscriptionPlan,
            'payment' => $payment,
            'subscription_plan_id' => $plan->id,
            'user_subscription_plan_id' => $userSubscriptionPlan->id,
            'success_message' => "Le pack {$plan->name} a été {$verb} avec succès à {$user->name}"
                . ($expires ? " (actif jusqu'au {$expires})." : '.'),
            'notif_title' => 'Abonnement activé 🎉',
            'notif_message' => "Votre pack {$plan->name} a été activé"
                . ($expires ? " et est valable jusqu'au {$expires}." : '.'),
            'notif_type' => 'subscription_purchase',
            'notif_data' => [
                'plan_id' => (string) $plan->id,
                'plan_name' => $plan->name,
                'expires_at' => $userSubscriptionPlan->expires_at?->toISOString(),
            ],
        ];
    }

    /**
     * Attribuer un service premium (PremiumServiceConfig -> UserPremiumService).
     */
    private function assignPremiumService(User $user, int $serviceId, array $validated): array
    {
        $service = PremiumServiceConfig::findOrFail($serviceId);

        $payment = $this->createManualPayment(
            $user,
            $service,
            "Attribution manuelle du service premium {$service->name}",
            $validated['notes'] ?? null
        );

        $expiresAt = $service->duration_days ? now()->addDays($service->duration_days) : null;

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

        $expires = $expiresAt?->format('d/m/Y');

        return [
            'config' => $service,
            'granted' => $userService,
            'payment' => $payment,
            'success_message' => "Le service premium {$service->name} a été attribué avec succès à {$user->name}"
                . ($expires ? " (valable jusqu'au {$expires})." : '.'),
            'notif_title' => 'Service premium activé ✨',
            'notif_message' => "Votre service {$service->name} a été activé"
                . ($expires ? " et est valable jusqu'au {$expires}." : '.'),
            'notif_type' => 'premium_service_purchase',
            'notif_data' => [
                'service_id' => (string) $service->id,
                'service_name' => $service->name,
                'expires_at' => $expiresAt?->toISOString(),
            ],
        ];
    }

    /**
     * Attribuer un add-on recruteur (AddonServicesConfig -> UserAddonService).
     */
    private function assignAddonService(User $user, int $serviceId, array $validated): array
    {
        $service = AddonServicesConfig::findOrFail($serviceId);

        $payment = $this->createManualPayment(
            $user,
            $service,
            "Attribution manuelle du service {$service->name}",
            $validated['notes'] ?? null
        );

        $expiresAt = $service->duration_days ? now()->addDays($service->duration_days) : null;

        $userService = UserAddonService::create([
            'user_id' => $user->id,
            'addon_services_config_id' => $service->id,
            'payment_id' => $payment->id,
            'purchased_at' => now(),
            'activated_at' => now(),
            'expires_at' => $expiresAt,
            'is_active' => true,
        ]);

        $expires = $expiresAt?->format('d/m/Y');

        return [
            'config' => $service,
            'granted' => $userService,
            'payment' => $payment,
            'success_message' => "Le service {$service->name} a été attribué avec succès à {$user->name}"
                . ($expires ? " (valable jusqu'au {$expires})." : '.'),
            'notif_title' => 'Service activé ✨',
            'notif_message' => "Votre service {$service->name} a été activé"
                . ($expires ? " et est valable jusqu'au {$expires}." : '.'),
            'notif_type' => 'service_purchase',
            'notif_data' => [
                'service_id' => (string) $service->id,
                'service_name' => $service->name,
                'expires_at' => $expiresAt?->toISOString(),
            ],
        ];
    }

    /**
     * Crée un paiement "manuel" complété pour tracer l'attribution.
     * NB: payment_method est un ENUM -> on utilise 'promotional_free' (valeur valide),
     * le caractère manuel étant porté par payment_type / provider.
     */
    private function createManualPayment(User $user, $config, string $description, ?string $notes): Payment
    {
        return Payment::create([
            'user_id' => $user->id,
            'payable_type' => get_class($config),
            'payable_id' => $config->id,
            'amount' => $config->price,
            'fees' => 0,
            'total' => $config->price,
            'currency' => 'XAF',
            'payment_method' => 'promotional_free',
            'payment_type' => 'manual_assignment',
            'provider' => 'admin',
            'external_id' => 'MANUAL-' . now()->format('YmdHis') . '-' . $user->id,
            'status' => 'completed',
            'paid_at' => now(),
            'description' => $description . " par l'admin " . (auth()->user()->name ?? 'système'),
            'notes' => $notes,
        ]);
    }

    /**
     * Envoie la push notification (et l'enregistre en BDD via NotificationService).
     */
    private function notifyUser(User $user, array $result): void
    {
        try {
            $this->notificationService->sendToUser(
                $user,
                $result['notif_title'],
                $result['notif_message'],
                $result['notif_type'],
                $result['notif_data'] ?? []
            );
        } catch (\Throwable $e) {
            Log::warning('Manual assignment notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Afficher la liste de toutes les attributions manuelles
     */
    public function index()
    {
        $assignments = ManualSubscriptionAssignment::with([
            'user',
            'subscriptionPlan',
            'assignable',
            'assignedByAdmin',
            'userSubscriptionPlan',
        ])
            ->latest()
            ->paginate(20);

        return view('admin.manual-subscriptions.index', compact('assignments'));
    }

    /**
     * Afficher les détails d'une attribution manuelle
     */
    public function show($id)
    {
        $assignment = ManualSubscriptionAssignment::with([
            'user',
            'subscriptionPlan',
            'assignable',
            'granted',
            'payment',
            'userSubscriptionPlan',
            'assignedByAdmin',
        ])->findOrFail($id);

        return view('admin.manual-subscriptions.show', compact('assignment'));
    }
}
