<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // La table historique de production précède le schéma actuel.
        if (!Schema::hasColumn('advertisements', 'image')) {
            Schema::table('advertisements', function (Blueprint $table) {
                $table->string('image')->nullable();
            });
        }
    }

    public function down(): void
    {
        // La colonne appartient au schéma initial actuel : conserver les images.
    }
};
