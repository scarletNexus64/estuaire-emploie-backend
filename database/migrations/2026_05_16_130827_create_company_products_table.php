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
        Schema::create('company_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name'); // Nom du produit/service
            $table->text('description'); // Description
            $table->decimal('price', 10, 2); // Prix en FCFA
            $table->enum('type', ['product', 'service'])->default('product'); // Type
            $table->json('images')->nullable(); // Images (array de chemins)
            $table->boolean('is_active')->default(true); // Actif/Inactif
            $table->integer('stock')->nullable(); // Stock (null pour les services)
            $table->timestamps();
            $table->softDeletes(); // Soft delete

            // Index pour améliorer les performances
            $table->index('company_id');
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_products');
    }
};
