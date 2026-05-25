<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManualSubscriptionAssignment;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ManualSubscriptionController extends Controller
{
    /**
     * Afficher le formulaire d'attribution manuelle
     */
    public function create()
    {
        // Récupérer tous les utilisateurs (sauf les admins)
        $users = User::where('role', '!=', 'admin')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        // Récupérer tous les plans d'abonnement actifs
        $subscriptionPlans = SubscriptionPlan::active()
            ->ordered()
            ->get();

        // Récupérer les attributions récentes pour affichage
        $recentAssignments = ManualSubscriptionAssignment::with([
            'user',
            'subscriptionPlan',
            'assignedByAdmin',
        ])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.manual-subscriptions.create', compact(
            'users',
            'subscriptionPlans',
            'recentAssignments'
        ));
    }

    /**
     * Attribuer manuellement un forfait à un utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // 1. Récupérer l'utilisateur et le plan
            $user = User::findOrFail($validated['user_id']);
            $plan = SubscriptionPlan::findOrFail($validated['subscription_plan_id']);

            // Vérifier si l'utilisateur a déjà un abonnement actif
            $existingSubscription = UserSubscriptionPlan::forUser($user->id)
                ->valid()
                ->first();

            if ($existingSubscription) {
                throw ValidationException::withMessages([
                    'user_id' => "Cet utilisateur a déjà un abonnement actif jusqu'au " .
                                $existingSubscription->expires_at->format('d/m/Y'),
                ]);
            }

            // 2. Créer un paiement "manuel" avec statut completed
            $payment = Payment::create([
                'user_id' => $user->id,
                'payable_type' => SubscriptionPlan::class,
                'payable_id' => $plan->id,
                'amount' => $plan->price,
                'fees' => 0,
                'total' => $plan->price,
                'payment_method' => 'manual_assignment',
                'provider' => 'admin',
                'external_id' => 'MANUAL-' . now()->format('YmdHis') . '-' . $user->id,
                'status' => 'completed',
                'paid_at' => now(),
                'description' => "Attribution manuelle du plan {$plan->name} par l'admin " . auth()->user()->name,
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info("Manual subscription payment created", [
                'payment_id' => $payment->id,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'admin_id' => auth()->id(),
            ]);

            // 3. Créer l'abonnement utilisateur
            $userSubscriptionPlan = UserSubscriptionPlan::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'payment_id' => $payment->id,
            ]);

            Log::info("UserSubscriptionPlan created", [
                'user_subscription_plan_id' => $userSubscriptionPlan->id,
            ]);

            // 4. Activer l'abonnement (cela va initialiser les dates, limites et mettre à jour le rôle)
            $userSubscriptionPlan->activate();

            Log::info("Subscription activated", [
                'starts_at' => $userSubscriptionPlan->starts_at,
                'expires_at' => $userSubscriptionPlan->expires_at,
                'user_role' => $user->fresh()->role,
            ]);

            // 5. Créer l'enregistrement de traçabilité
            $assignment = ManualSubscriptionAssignment::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'payment_id' => $payment->id,
                'user_subscription_plan_id' => $userSubscriptionPlan->id,
                'assigned_by_admin_id' => auth()->id(),
                'reason' => $validated['reason'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info("Manual subscription assignment created", [
                'assignment_id' => $assignment->id,
                'assigned_by' => auth()->user()->name,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.manual-subscriptions.create')
                ->with('success', "L'abonnement {$plan->name} a été attribué avec succès à {$user->name}. L'abonnement est actif jusqu'au {$userSubscriptionPlan->expires_at->format('d/m/Y')}.");

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Manual subscription assignment failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Erreur lors de l'attribution de l'abonnement : " . $e->getMessage());
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
            'payment',
            'userSubscriptionPlan',
            'assignedByAdmin',
        ])->findOrFail($id);

        return view('admin.manual-subscriptions.show', compact('assignment'));
    }
}
