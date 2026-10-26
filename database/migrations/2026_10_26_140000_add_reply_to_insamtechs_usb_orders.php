<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insamtechs_usb_orders', function (Blueprint $table) {
            $table->text('admin_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('insamtechs_usb_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('replied_by');
            $table->dropColumn(['admin_reply', 'replied_at']);
        });
    }
};
