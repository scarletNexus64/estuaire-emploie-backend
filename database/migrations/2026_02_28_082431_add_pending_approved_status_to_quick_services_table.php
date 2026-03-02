<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quick_services', function (Blueprint $table) {
            // Modifier la colonne status pour ajouter 'pending' et 'approved'
            $table->enum('status', ['pending', 'approved', 'open', 'in_progress', 'completed', 'cancelled'])
                ->default('pending')
                ->change();

            // Ajouter une colonne pour la date d'approbation
            $table->timestamp('approved_at')->nullable()->after('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quick_services', function (Blueprint $table) {
            // Retirer la colonne approved_at
            $table->dropColumn('approved_at');

            // Revenir à l'ancien enum
            $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled'])
                ->default('open')
                ->change();
        });
    }
};
