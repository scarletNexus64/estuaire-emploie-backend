<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('community_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_specialty_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 180);
            $table->text('content');
            $table->string('kind', 20)->default('discussion');
            $table->text('resource_url')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
            $table->index(['community_specialty_id', 'kind', 'id'], 'community_discussion_listing');
        });
        Schema::create('community_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_discussion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });
        Schema::table('community_discussions', function (Blueprint $table) {
            $table->foreignId('accepted_reply_id')->nullable()->constrained('community_replies')->nullOnDelete();
        });
        Schema::create('community_mentor_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('bio');
            $table->text('experience');
            $table->string('availability', 500);
            $table->text('proof_url')->nullable();
            $table->string('status', 20)->default('pending')->index();
            $table->boolean('is_available')->default(true);
            $table->text('review_message')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('community_mentor_specialties', function (Blueprint $table) {
            $table->foreignId('application_id')->constrained('community_mentor_applications')->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained('community_specialties')->restrictOnDelete();
            $table->primary(['application_id', 'specialty_id']);
        });
        Schema::create('community_assistance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_specialty_id')->constrained()->restrictOnDelete();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mentor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 180);
            $table->text('description');
            $table->string('status', 20)->default('pending');
            $table->timestamps();
            $table->index(['requester_id', 'status']);
            $table->index(['community_specialty_id', 'status'], 'community_assistance_listing');
        });
        Schema::create('community_assistance_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('community_assistance_requests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('attachment_mime')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_assistance_messages');
        Schema::dropIfExists('community_assistance_requests');
        Schema::dropIfExists('community_mentor_specialties');
        Schema::dropIfExists('community_mentor_applications');
        Schema::table('community_discussions', fn (Blueprint $table) => $table->dropConstrainedForeignId('accepted_reply_id'));
        Schema::dropIfExists('community_replies');
        Schema::dropIfExists('community_discussions');
    }
};
