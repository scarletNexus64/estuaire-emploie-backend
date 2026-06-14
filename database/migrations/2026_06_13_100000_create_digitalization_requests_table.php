<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Demandes de digitalisation envoyées depuis l'app mobile (espace recruteur).
     * Un recruteur souhaitant digitaliser un process métier (ex: SaaS RH, paie…)
     * remplit un formulaire court reçu ici, dans l'Administration de l'admin panel.
     */
    public function up(): void
    {
        Schema::create('digitalization_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('project_name');
            $table->string('company_name')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('contact');
            $table->text('description');
            $table->enum('status', ['pending', 'processed', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digitalization_requests');
    }
};
