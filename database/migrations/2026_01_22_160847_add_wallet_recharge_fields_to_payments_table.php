<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier l'enum payment_method pour ajouter paypal et freemopay
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('mtn_money', 'orange_money', 'card', 'bank_transfer', 'cash', 'free', 'paypal', 'freemopay') NOT NULL");

        Schema::table('payments', function (Blueprint $table) {
            // Ajouter payment_type pour identifier le type de paiement (wallet_recharge, subscription, etc.)
            $table->string('payment_type')->nullable()->after('payment_method');

            // Ajouter currency pour les paiements internationaux
            $table->string('currency', 3)->default('XAF')->after('total');

            // Ajouter metadata pour stocker des informations supplémentaires en JSON
            $table->json('metadata')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'currency', 'metadata']);
        });

        // Restaurer l'enum original
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('mtn_money', 'orange_money', 'card', 'bank_transfer', 'cash', 'free') NOT NULL");
    }
};
