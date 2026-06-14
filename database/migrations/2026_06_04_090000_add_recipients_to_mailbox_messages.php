<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mailbox_messages', function (Blueprint $table) {
            // Liste complète des destinataires (To) et copies (Cc), séparées par des virgules
            $table->text('recipients_to')->nullable()->after('to_email');
            $table->text('cc')->nullable()->after('recipients_to');
        });
    }

    public function down(): void
    {
        Schema::table('mailbox_messages', function (Blueprint $table) {
            $table->dropColumn(['recipients_to', 'cc']);
        });
    }
};
