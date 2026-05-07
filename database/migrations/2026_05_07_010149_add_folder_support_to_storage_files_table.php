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
        Schema::table('storage_files', function (Blueprint $table) {
            // Ajouter le support des dossiers
            $table->boolean('is_folder')->default(false)->after('path');
            $table->foreignId('parent_folder_id')->nullable()->after('is_folder')
                ->constrained('storage_files')->onDelete('cascade');

            // Modifier path et les autres colonnes pour être nullable pour les dossiers
            $table->string('path')->nullable()->change();
            $table->string('mime_type')->nullable()->change();
            $table->bigInteger('size')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storage_files', function (Blueprint $table) {
            $table->dropForeign(['parent_folder_id']);
            $table->dropColumn(['is_folder', 'parent_folder_id']);

            // Remettre les colonnes en non-nullable
            $table->string('path')->nullable(false)->change();
            $table->string('mime_type')->nullable(false)->change();
            $table->bigInteger('size')->nullable(false)->change();
        });
    }
};
