<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('cv_path')->nullable();
            $table->text('cover_letter')->nullable();
            $table->text('portfolio_url')->nullable();

            $table->enum('status', ['pending', 'viewed', 'shortlisted', 'rejected', 'interview', 'accepted'])->default('pending');
            $table->text('internal_notes')->nullable();

            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('responded_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['job_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};