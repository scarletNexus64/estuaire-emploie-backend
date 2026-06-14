<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Transforme la table `advertisements` (jusqu'ici réservée à l'admin) en support
 * du sponsoring self-service (Marketing Digital) : une entreprise crée son annonce,
 * choisit un budget => audience, paie via wallet, et l'annonce est auto-publiée.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            // Propriétaire de la campagne (null = annonce admin historique)
            $table->foreignId('company_id')->nullable()->after('id')
                ->constrained('companies')->nullOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->after('company_id')
                ->constrained('users')->nullOnDelete();

            // Type de contenu diffusé
            $table->enum('content_type', ['text', 'flyer', 'logo'])
                ->default('text')->after('background_color');

            // Ciblage : student / candidate / recruiter (entreprise) / all
            $table->enum('target_audience', ['student', 'candidate', 'recruiter', 'all'])
                ->default('all')->after('content_type');

            // Budget & audience
            $table->decimal('budget', 10, 2)->default(0)->after('target_audience');
            $table->unsignedInteger('target_reach')->default(0)->after('budget');

            // Lien paiement wallet
            $table->foreignId('payment_id')->nullable()->after('target_reach')
                ->constrained('payments')->nullOnDelete();

            // Origine de l'annonce
            $table->enum('source', ['admin', 'self_service'])
                ->default('admin')->after('payment_id');

            $table->index(['target_audience', 'is_active']);
            $table->index(['company_id', 'source']);
        });

        // Élargir l'enum status pour inclure 'completed' (budget épuisé / période finie)
        DB::statement("ALTER TABLE `advertisements` MODIFY COLUMN `status` ENUM('active', 'paused', 'expired', 'completed') NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
            $table->dropConstrainedForeignId('created_by_user_id');
            $table->dropConstrainedForeignId('payment_id');
            $table->dropColumn(['content_type', 'target_audience', 'budget', 'target_reach', 'source']);
        });

        DB::statement("ALTER TABLE `advertisements` MODIFY COLUMN `status` ENUM('active', 'paused', 'expired') NOT NULL DEFAULT 'active'");
    }
};
