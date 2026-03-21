<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contract_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('posted_by')->constrained('users')->cascadeOnDelete();

            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->string('salary_min')->nullable();
            $table->string('salary_max')->nullable();
            $table->boolean('salary_negotiable')->default(false);
            $table->enum('experience_level', ['junior', 'intermediaire', 'senior', 'expert'])->nullable();

            $table->enum('status', ['draft', 'pending', 'published', 'closed', 'expired'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);

            $table->date('application_deadline')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
