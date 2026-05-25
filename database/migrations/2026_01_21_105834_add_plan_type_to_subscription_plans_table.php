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
        Schema::table('subscription_plans', function (Blueprint $table) {
            // Ajouter plan_type pour différencier recruteurs et chercheurs d'emploi
            $table->enum('plan_type', ['recruiter', 'job_seeker'])->default('recruiter')->after('slug');

            // Rendre nullable les colonnes spécifiques aux recruteurs
            $table->integer('jobs_limit')->nullable()->change();
            $table->integer('contacts_limit')->nullable()->change();
            $table->boolean('can_access_cvtheque')->nullable()->change();
            $table->boolean('can_boost_jobs')->nullable()->change();
            $table->boolean('can_see_analytics')->nullable()->change();
            $table->boolean('priority_support')->nullable()->change();
            $table->boolean('featured_company_badge')->nullable()->change();
            $table->boolean('custom_company_page')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('plan_type');
        });
    }
};
