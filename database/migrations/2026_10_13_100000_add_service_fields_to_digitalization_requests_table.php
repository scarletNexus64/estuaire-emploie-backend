<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Les demandes de digitalisation deviennent des demandes de prestation
     * ouvertes à tous les profils (étudiant, candidat, entreprise) :
     * catégorie, canaux de contact, pièce jointe et suivi de la diffusion FCM.
     * Le contact devient optionnel (inutile si seul le canal in-app est choisi).
     */
    public function up(): void
    {
        Schema::table('digitalization_requests', function (Blueprint $table) {
            $table->string('category', 40)->default('company_service')->after('user_id');
            $table->json('channels')->nullable()->after('contact');
            $table->string('attachment_path')->nullable()->after('description');
            $table->string('attachment_name')->nullable()->after('attachment_path');
            $table->timestamp('notified_at')->nullable()->after('processed_at');
            $table->unsignedInteger('responses_count')->default(0)->after('notified_at');

            $table->index('category');
        });

        Schema::table('digitalization_requests', function (Blueprint $table) {
            $table->string('contact')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('digitalization_requests', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropColumn(['category', 'channels', 'attachment_path', 'attachment_name', 'notified_at', 'responses_count']);
        });
    }
};
