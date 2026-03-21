<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rendre email nullable — inscription par téléphone (OTP SMS) sans email.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Supprimer l'index unique existant
            $table->dropUnique('users_email_unique');
            // 2. Rendre la colonne nullable
            $table->string('email')->nullable()->change();
            // 3. Recréer l'index unique (nullable : plusieurs NULL sont autorisés en MySQL)
            $table->unique('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');
            $table->string('email')->nullable(false)->change();
            $table->unique('email');
        });
    }
};
