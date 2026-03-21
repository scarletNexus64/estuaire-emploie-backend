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
        Schema::create('platform_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('amount_requested', 15, 2);
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('commission_amount', 15, 2)->default(0);
            $table->decimal('amount_sent', 15, 2);
            $table->string('currency', 10)->default('XAF');
            $table->string('payment_method'); // om, momo
            $table->string('payment_account');
            $table->string('payment_account_name')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('transaction_reference')->unique();
            $table->string('freemopay_reference')->nullable();
            $table->json('freemopay_response')->nullable();
            $table->string('failure_code')->nullable();
            $table->text('failure_reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
            $table->index('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_withdrawals');
    }
};
