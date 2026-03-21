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
        Schema::table('payments', function (Blueprint $table) {
            // Ajouter external_id pour FreeMoPay (identifiant unique côté application)
            $table->string('external_id')->nullable()->unique()->after('transaction_reference');

            // Ajouter reference FreeMoPay (identifiant retourné par FreeMoPay)
            $table->string('provider_reference')->nullable()->after('external_id');

            // Ajouter provider pour identifier le service de paiement
            $table->string('provider')->nullable()->after('payment_method');

            // Ajouter description du paiement
            $table->text('description')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['external_id', 'provider_reference', 'provider', 'description']);
        });
    }
};
