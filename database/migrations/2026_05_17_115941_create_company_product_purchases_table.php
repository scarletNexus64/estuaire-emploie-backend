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
        Schema::create('company_product_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_product_id')
                ->constrained('company_products')
                ->cascadeOnDelete();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();
            // Acheteur (candidat)
            $table->foreignId('buyer_user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            // Vendeur = user recruteur propriétaire de l'entreprise
            $table->foreignId('seller_user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->nullable();
            $table->string('provider'); // freemopay | paypal
            $table->enum('status', ['pending', 'paid'])->default('pending');
            // Transaction wallet de débit acheteur (traçabilité)
            $table->unsignedBigInteger('wallet_transaction_id')->nullable();
            // Snapshot du produit au moment de l'achat
            $table->json('product_snapshot')->nullable();
            $table->timestamps();

            $table->index('buyer_user_id');
            $table->index('seller_user_id');
            $table->index('company_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_product_purchases');
    }
};
