<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('recovery_source')->nullable();
            $table->string('recovery_source_key', 64)->nullable()->unique();
            $table->foreignId('recovery_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('recovery_claimed_at')->nullable();
            $table->foreignId('recovery_claimed_by')->nullable()->constrained('users')->nullOnDelete();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('temporary_password_expires_at')->nullable();
        });
        Schema::create('company_recovery_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('requester_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('result_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('mode', 16);
            $table->string('name');
            $table->string('phone', 30);
            $table->string('position', 100);
            $table->string('reply_email')->nullable();
            $table->text('explanation');
            $table->string('status', 20)->default('pending');
            $table->text('admin_reply')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'status']);
            $table->index(['requester_user_id', 'status']);
        });
        Schema::create('company_recovery_email_challenges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('code_hash', 64);
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();
        });
        // Transactional outbox: credentials are encrypted until delivery, then erased.
        Schema::create('company_recovery_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('company_recovery_requests')->cascadeOnDelete();
            $table->string('channel', 10);
            $table->text('payload')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('last_error')->nullable();
            $table->timestamps();
        });
        Schema::create('api_password_reset_grants', function (Blueprint $table) {
            $table->string('token_hash', 64)->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_password_reset_grants');
        Schema::dropIfExists('company_recovery_deliveries');
        Schema::dropIfExists('company_recovery_email_challenges');
        Schema::dropIfExists('company_recovery_requests');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('temporary_password_expires_at'));
        Schema::table('companies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recovery_user_id');
            $table->dropConstrainedForeignId('recovery_claimed_by');
            $table->dropUnique(['recovery_source_key']);
            $table->dropColumn(['recovery_source', 'recovery_source_key', 'recovery_claimed_at']);
        });
    }
};
