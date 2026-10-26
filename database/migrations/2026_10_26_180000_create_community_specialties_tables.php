<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_filieres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('community_specialties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_filiere_id')->constrained('community_filieres')->restrictOnDelete();
            $table->unsignedBigInteger('insam_ia_category_id')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['community_filiere_id', 'is_active', 'display_order'], 'community_specialties_listing_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_specialties');
        Schema::dropIfExists('community_filieres');
    }
};
