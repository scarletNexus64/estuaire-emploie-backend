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
        // Idempotent : le schéma de prod possède déjà `company_id` / `payment_id`
        // et ne possède pas `background_color`. On n'ajoute que ce qui manque.
        Schema::table('advertisements', function (Blueprint $table) {
            // Propriétaire de la campagne (null = annonce admin historique)
            if (!Schema::hasColumn('advertisements', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('id')
                    ->constrained('companies')->nullOnDelete();
            }
            if (!Schema::hasColumn('advertisements', 'created_by_user_id')) {
                $table->foreignId('created_by_user_id')->nullable()->after('company_id')
                    ->constrained('users')->nullOnDelete();
            }

            // Type de contenu diffusé
            if (!Schema::hasColumn('advertisements', 'content_type')) {
                $table->enum('content_type', ['text', 'flyer', 'logo'])
                    ->default('text')->after('status');
            }

            // Ciblage : student / candidate / recruiter (entreprise) / all
            if (!Schema::hasColumn('advertisements', 'target_audience')) {
                $table->enum('target_audience', ['student', 'candidate', 'recruiter', 'all'])
                    ->default('all')->after('content_type');
            }

            // Budget & audience
            if (!Schema::hasColumn('advertisements', 'budget')) {
                $table->decimal('budget', 10, 2)->default(0)->after('target_audience');
            }
            if (!Schema::hasColumn('advertisements', 'target_reach')) {
                $table->unsignedInteger('target_reach')->default(0)->after('budget');
            }

            // Lien paiement wallet
            if (!Schema::hasColumn('advertisements', 'payment_id')) {
                $table->foreignId('payment_id')->nullable()->after('target_reach')
                    ->constrained('payments')->nullOnDelete();
            }

            // Origine de l'annonce
            if (!Schema::hasColumn('advertisements', 'source')) {
                $table->enum('source', ['admin', 'self_service'])
                    ->default('admin')->after('payment_id');
            }
        });

        // Index (ajoutés une fois les colonnes présentes, en évitant les doublons)
        $this->addIndexIfMissing('advertisements', 'advertisements_target_audience_is_active_index', ['target_audience', 'is_active']);
        $this->addIndexIfMissing('advertisements', 'advertisements_company_id_source_index', ['company_id', 'source']);

        // Élargir l'enum status pour inclure 'completed' (budget épuisé / période finie)
        DB::statement("ALTER TABLE `advertisements` MODIFY COLUMN `status` ENUM('active', 'paused', 'expired', 'completed') NOT NULL DEFAULT 'active'");
    }

    private function addIndexIfMissing(string $table, string $indexName, array $columns): void
    {
        $exists = DB::table('information_schema.statistics')
            ->where('table_schema', DB::raw('DATABASE()'))
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();

        if (!$exists) {
            Schema::table($table, function (Blueprint $t) use ($columns) {
                $t->index($columns);
            });
        }
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
