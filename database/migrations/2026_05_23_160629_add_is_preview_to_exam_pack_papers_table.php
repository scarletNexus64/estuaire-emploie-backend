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
        Schema::table('exam_pack_papers', function (Blueprint $table) {
            // Une épreuve marquée preview est accessible en mode vitrine
            // (avant l'achat / activation du Mode Étudiant)
            $table->boolean('is_preview')->default(false)->after('display_order');
            $table->index('is_preview');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_pack_papers', function (Blueprint $table) {
            $table->dropIndex(['is_preview']);
            $table->dropColumn('is_preview');
        });
    }
};
