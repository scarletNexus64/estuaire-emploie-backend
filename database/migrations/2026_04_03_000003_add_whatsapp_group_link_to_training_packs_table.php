<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('training_packs', function (Blueprint $table) {
            $table->string('whatsapp_group_link')->nullable()->after('is_active');
        });
    }
    public function down(): void
    {
        Schema::table('training_packs', function (Blueprint $table) {
            $table->dropColumn('whatsapp_group_link');
        });
    }
};
