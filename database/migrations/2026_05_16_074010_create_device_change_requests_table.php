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
        Schema::create('device_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('old_device_id');
            $table->string('new_device_id');
            $table->string('device_name')->nullable(); // Nom de l'appareil (ex: iPhone 13)
            $table->string('device_model')->nullable(); // Modèle de l'appareil
            $table->string('reason')->nullable(); // Raison du changement
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // Admin qui a traité la demande
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable(); // Notes de l'admin
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_change_requests');
    }
};
