<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            $table->boolean('has_correction')->default(false)->after('is_correction');
            $table->foreignId('correction_paper_id')->nullable()->after('has_correction')
                  ->constrained('exam_papers')->nullOnDelete();
        });
    }
    public function down(): void
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            $table->dropForeign(['correction_paper_id']);
            $table->dropColumn(['has_correction', 'correction_paper_id']);
        });
    }
};
