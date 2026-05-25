<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insamtechs_formation_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('insamtechs_formation_id');
            $table->string('formation_title')->nullable();
            $table->decimal('amount_paid', 12, 2);
            $table->string('currency', 3)->default('XAF');
            $table->string('payment_method', 50)->default('wallet');
            $table->string('payment_provider', 50)->nullable(); // freemopay, paypal
            $table->string('status', 20)->default('completed'); // completed, refunded
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();

            // Un utilisateur ne peut acheter qu'une fois la même formation
            $table->unique(['user_id', 'insamtechs_formation_id'], 'unique_user_formation_purchase');
            $table->index('insamtechs_formation_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insamtechs_formation_purchases');
    }
};
