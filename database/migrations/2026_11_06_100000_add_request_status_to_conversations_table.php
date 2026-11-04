<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Demandes de message (annuaire de la messagerie).
 *
 * Une conversation ouverte depuis l'annuaire naît « pending » : le premier
 * message part, mais le demandeur ne peut plus écrire tant que le
 * destinataire n'a pas accepté. Les conversations existantes (candidatures,
 * services, support, boutique) restent « accepted ».
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->string('status', 16)->default('accepted')->after('service_id');
            $table->foreignId('requested_by')->nullable()->after('status')
                ->constrained('users')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->after('requested_by')
                ->constrained('companies')->nullOnDelete();
            $table->timestamp('accepted_at')->nullable()->after('company_id');
            $table->index(['status', 'requested_by']);
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndex(['status', 'requested_by']);
            $table->dropConstrainedForeignId('company_id');
            $table->dropConstrainedForeignId('requested_by');
            $table->dropColumn(['status', 'accepted_at']);
        });
    }
};
