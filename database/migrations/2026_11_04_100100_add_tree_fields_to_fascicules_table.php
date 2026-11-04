<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Classement des fascicules en dossiers (Domaine → Filière) et prise en charge
 * des fichiers Word (.docx) en plus des PDF.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fascicules', function (Blueprint $table) {
            $table->string('domain', 40)->nullable()->after('description');
            $table->string('filiere_code', 20)->nullable()->after('domain');
            $table->string('kind', 30)->default('autre')->after('filiere_code');
            $table->unsignedTinyInteger('volume')->nullable()->after('kind');
            $table->string('file_type', 10)->default('pdf')->after('file_path');
            $table->unsignedInteger('display_order')->default(0)->after('volume');
            $table->index(['domain', 'filiere_code']);
        });
    }

    public function down(): void
    {
        Schema::table('fascicules', function (Blueprint $table) {
            $table->dropIndex(['domain', 'filiere_code']);
            $table->dropColumn(['domain', 'filiere_code', 'kind', 'volume', 'file_type', 'display_order']);
        });
    }
};
