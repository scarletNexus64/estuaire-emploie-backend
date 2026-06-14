<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Un "fil" = une conversation avec un correspondant externe (candidat, entreprise...)
        Schema::create('mailbox_threads', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable();
            $table->string('contact_email')->index();
            $table->string('contact_name')->nullable();
            $table->enum('status', ['open', 'pending', 'closed'])->default('open');
            $table->unsignedBigInteger('assigned_admin_id')->nullable();
            $table->unsignedInteger('unread_count')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'last_message_at'], 'idx_thread_status_last');
            $table->foreign('assigned_admin_id')->references('id')->on('users')->nullOnDelete();
        });

        // Un message individuel dans un fil (entrant = reçu via IMAP, sortant = envoyé via SMTP/Brevo)
        Schema::create('mailbox_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('mailbox_threads')->cascadeOnDelete();
            $table->enum('direction', ['inbound', 'outbound']);
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->string('to_email');
            $table->string('subject')->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();

            // En-têtes de threading mail (RFC 5322)
            $table->string('message_id')->nullable()->unique();
            $table->string('in_reply_to')->nullable()->index();
            $table->text('references')->nullable();

            // Origine IMAP (pour ne pas réimporter deux fois)
            $table->string('imap_folder')->nullable();
            $table->unsignedBigInteger('imap_uid')->nullable();

            $table->unsignedBigInteger('sent_by_admin_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('has_attachments')->default(false);
            $table->enum('send_status', ['received', 'queued', 'sent', 'failed'])->default('received');
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['thread_id', 'created_at'], 'idx_msg_thread_created');
            $table->unique(['imap_folder', 'imap_uid'], 'uniq_imap_msg');
            $table->foreign('sent_by_admin_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('mailbox_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('mailbox_messages')->cascadeOnDelete();
            $table->string('filename');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('path');                 // chemin dans storage
            $table->string('content_id')->nullable(); // pour les images inline (cid:)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mailbox_attachments');
        Schema::dropIfExists('mailbox_messages');
        Schema::dropIfExists('mailbox_threads');
    }
};
