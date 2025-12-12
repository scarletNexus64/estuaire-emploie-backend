<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relations (user OU company peut payer)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');

            // Relation polymorphique (pour quoi on paie: subscription, service, etc.)
            $table->morphs('payable'); // payable_type, payable_id

            // Montant
            $table->decimal('amount', 10, 2); // Montant en FCFA
            $table->decimal('fees', 10, 2)->default(0); // Frais de transaction
            $table->decimal('total', 10, 2); // Total = amount + fees

            // Mode de paiement
            $table->enum('payment_method', [
                'mtn_money',
                'orange_money',
                'card',
                'bank_transfer',
                'cash',
                'free' // Pour les tests ou offres gratuites
            ]);

            // Informations de transaction
            $table->string('transaction_reference')->unique()->nullable(); // Référence externe
            $table->string('phone_number')->nullable(); // Numéro Mobile Money
            $table->text('payment_provider_response')->nullable(); // Réponse JSON du provider

            // Statut
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');

            // Dates importantes
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Notes
            $table->text('notes')->nullable(); // Notes internes
            $table->text('failure_reason')->nullable(); // Raison de l'échec

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['user_id', 'status']);
            $table->index(['company_id', 'status']);
            $table->index('payable_type');
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
