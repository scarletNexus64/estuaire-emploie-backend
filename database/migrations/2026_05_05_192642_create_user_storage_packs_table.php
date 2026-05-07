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
        Schema::create('user_storage_packs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('storage_pack_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('storage_mb'); // Espace acheté (copié depuis le pack)
            $table->unsignedInteger('storage_used_mb')->default(0); // Espace utilisé
            $table->string('storage_folder_path')->nullable(); // Chemin du dossier de stockage
            $table->timestamp('purchased_at'); // Date d'achat
            $table->timestamp('expires_at'); // Date d'expiration
            $table->boolean('is_active')->default(true); // Pack actif ou expiré
            $table->decimal('purchase_price', 10, 2); // Prix payé lors de l'achat
            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les requêtes
            $table->index('user_id');
            $table->index('expires_at');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_storage_packs');
    }
};
