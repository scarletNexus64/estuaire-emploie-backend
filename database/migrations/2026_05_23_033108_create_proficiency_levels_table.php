<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proficiency_levels', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32); // 'skill' | 'language' | 'training'
            $table->string('slug');
            $table->string('name'); // canonical FR label
            $table->integer('rank')->default(0); // ordering 1..n
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['type', 'slug']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proficiency_levels');
    }
};
