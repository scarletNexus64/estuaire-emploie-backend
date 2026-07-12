<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rend la table manual_subscription_assignments polymorphe afin de tracer
 * non seulement les attributions de plans (SubscriptionPlan) mais aussi
 * les services premium (PremiumServiceConfig) et les add-ons (AddonServicesConfig).
 *
 * Migration idempotente (sécurisée pour la prod où le schéma peut diverger).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manual_subscription_assignments', function (Blueprint $table) {
            // Le type de l'élément attribué : plan | premium_service | addon_service
            if (!Schema::hasColumn('manual_subscription_assignments', 'item_type')) {
                $table->string('item_type')->default('plan')->after('user_id');
            }

            // La config attribuée (SubscriptionPlan / PremiumServiceConfig / AddonServicesConfig)
            if (!Schema::hasColumn('manual_subscription_assignments', 'assignable_type')) {
                $table->string('assignable_type')->nullable()->after('item_type');
            }
            if (!Schema::hasColumn('manual_subscription_assignments', 'assignable_id')) {
                $table->unsignedBigInteger('assignable_id')->nullable()->after('assignable_type');
            }

            // L'enregistrement utilisateur créé (UserSubscriptionPlan / UserPremiumService / UserAddonService)
            if (!Schema::hasColumn('manual_subscription_assignments', 'granted_type')) {
                $table->string('granted_type')->nullable()->after('assignable_id');
            }
            if (!Schema::hasColumn('manual_subscription_assignments', 'granted_id')) {
                $table->unsignedBigInteger('granted_id')->nullable()->after('granted_type');
            }
        });

        // Rendre nullable les colonnes spécifiques aux plans (les services ne les remplissent pas).
        // On utilise du SQL brut pour éviter la dépendance à doctrine/dbal.
        $this->makeNullable('subscription_plan_id', 'BIGINT UNSIGNED');
        $this->makeNullable('payment_id', 'BIGINT UNSIGNED');
        $this->makeNullable('user_subscription_plan_id', 'BIGINT UNSIGNED');
    }

    public function down(): void
    {
        Schema::table('manual_subscription_assignments', function (Blueprint $table) {
            foreach (['item_type', 'assignable_type', 'assignable_id', 'granted_type', 'granted_id'] as $col) {
                if (Schema::hasColumn('manual_subscription_assignments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    private function makeNullable(string $column, string $type): void
    {
        if (Schema::hasColumn('manual_subscription_assignments', $column)) {
            \DB::statement("ALTER TABLE manual_subscription_assignments MODIFY `{$column}` {$type} NULL");
        }
    }
};
