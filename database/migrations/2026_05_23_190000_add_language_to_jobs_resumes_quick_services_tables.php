<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('jobs', 'language')) {
                $table->string('language', 8)->default('fr')->index()->after('title');
            }
        });

        Schema::table('resumes', function (Blueprint $table) {
            if (! Schema::hasColumn('resumes', 'language')) {
                $table->string('language', 8)->default('fr')->index()->after('title');
            }
        });

        Schema::table('quick_services', function (Blueprint $table) {
            if (! Schema::hasColumn('quick_services', 'language')) {
                $table->string('language', 8)->default('fr')->index()->after('title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (Schema::hasColumn('jobs', 'language')) {
                $table->dropIndex(['language']);
                $table->dropColumn('language');
            }
        });

        Schema::table('resumes', function (Blueprint $table) {
            if (Schema::hasColumn('resumes', 'language')) {
                $table->dropIndex(['language']);
                $table->dropColumn('language');
            }
        });

        Schema::table('quick_services', function (Blueprint $table) {
            if (Schema::hasColumn('quick_services', 'language')) {
                $table->dropIndex(['language']);
                $table->dropColumn('language');
            }
        });
    }
};
