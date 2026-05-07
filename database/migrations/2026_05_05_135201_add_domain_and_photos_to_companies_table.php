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
        Schema::table('companies', function (Blueprint $table) {
            // Ajouter le champ domaine
            $table->string('domain')->nullable()->after('description');

            // Modifier sector pour le rendre nullable (maintenant il dépend du domaine)
            $table->string('sector')->nullable()->change();

            // Ajouter le champ photos (JSON array pour stocker 2-4 photos)
            $table->json('photos')->nullable()->after('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('domain');
            $table->dropColumn('photos');

            // Remettre sector comme non-nullable
            $table->string('sector')->nullable(false)->change();
        });
    }
};
