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
        Schema::table('applications', function (Blueprint $table) {
            $table->boolean('diploma_verified')->default(false)->after('responded_at');
            $table->timestamp('diploma_verified_at')->nullable()->after('diploma_verified');
            $table->foreignId('diploma_verified_by')->nullable()->constrained('users')->onDelete('set null')->after('diploma_verified_at');
            $table->text('diploma_verification_notes')->nullable()->after('diploma_verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['diploma_verified_by']);
            $table->dropColumn(['diploma_verified', 'diploma_verified_at', 'diploma_verified_by', 'diploma_verification_notes']);
        });
    }
};
