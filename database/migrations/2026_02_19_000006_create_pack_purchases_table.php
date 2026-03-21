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
        Schema::create('pack_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Pack acheté (soit exam pack, soit training pack)
            $table->enum('pack_type', ['exam', 'training']);
            $table->foreignId('exam_pack_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('training_pack_id')->nullable()->constrained()->onDelete('cascade');

            // Informations d'achat
            $table->decimal('amount_paid', 10, 2); // Montant payé
            $table->string('currency', 3)->default('XAF'); // Devise
            $table->string('payment_method')->nullable(); // wallet, paypal, etc.
            $table->string('transaction_id')->nullable(); // ID de la transaction

            // Gestion
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
            $table->timestamp('purchased_at')->useCurrent();
            $table->timestamp('expires_at')->nullable(); // Si accès limité dans le temps

            $table->timestamps();

            // Index
            $table->index(['user_id', 'pack_type']);
            $table->index(['exam_pack_id', 'status']);
            $table->index(['training_pack_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pack_purchases');
    }
};
