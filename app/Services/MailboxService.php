<?php

namespace App\Services;

use App\Mail\SupportMail;
use App\Models\MailboxAttachment;
use App\Models\MailboxMessage;
use App\Models\MailboxThread;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webklex\IMAP\Facades\Client;

class MailboxService
{
    /**
     * Envoie un email (via le canal Brevo) et enregistre le message sortant
     * dans son fil de conversation.
     *
     * @param  array  $data  to_email, to_name, subject, body_html,
     *                        attachments[] (chemins disque public), thread_id?, admin_id?
     */
    public function send(array $data): MailboxMessage
    {
        $fromAddress = config('mail.support_from', env('SUPPORT_MAIL_FROM', 'support.contact@estuaireemploi.com'));
        $fromName    = env('SUPPORT_MAIL_FROM_NAME', 'Estuaire Emploi - Support');

        // Destinataires : 'to' (tableau) ou repli sur 'to_email' (envoi simple / réponse)
        $toList = array_values(array_unique(array_filter(array_map('trim', $data['to'] ?? array_filter([$data['to_email'] ?? null])))));
        $ccList = array_values(array_unique(array_filter(array_map('trim', $data['cc'] ?? []))));
        $primaryTo = $toList[0] ?? ($data['to_email'] ?? null);

        // Fil existant (réponse) ou nouveau fil (rattaché au destinataire principal)
        $thread = isset($data['thread_id'])
            ? MailboxThread::find($data['thread_id'])
            : null;

        if (!$thread) {
            $thread = MailboxThread::create([
                'subject'         => $data['subject'] ?? null,
                'contact_email'   => $primaryTo,
                'contact_name'    => $data['to_name'] ?? null,
                'status'          => 'open',
                'last_message_at' => now(),
            ]);
        }

        // En-têtes de threading
        $messageId = '<' . Str::uuid() . '@estuaireemploi.com>';
        $inReplyTo = null;
        $references = [];
        if ($thread->exists) {
            $last = $thread->messages()->whereNotNull('message_id')->latest()->first();
            if ($last) {
                $inReplyTo = $last->message_id;
                $references = array_filter([
                    $last->references,
                    $last->message_id,
                ]);
            }
        }

        $attachments = $data['attachments'] ?? [];

        $mailer = Mail::mailer('brevo')->to($toList);
        if (!empty($ccList)) {
            $mailer->cc($ccList);
        }
        $mailer->send(
            new SupportMail(
                subjectLine:    $data['subject'] ?? '(sans objet)',
                htmlBody:       $data['body_html'] ?? '',
                fromAddress:    $fromAddress,
                fromName:       $fromName,
                replyToAddress: $fromAddress,
                files:          $attachments,
                messageId:      $messageId,
                inReplyTo:      $inReplyTo,
                references:     $references,
            )
        );

        $message = $thread->messages()->create([
            'direction'        => 'outbound',
            'from_email'       => $fromAddress,
            'from_name'        => $fromName,
            'to_email'         => $primaryTo,
            'recipients_to'    => implode(', ', $toList),
            'cc'               => $ccList ? implode(', ', $ccList) : null,
            'subject'          => $data['subject'] ?? null,
            'body_html'        => $data['body_html'] ?? null,
            'body_text'        => trim(strip_tags($data['body_html'] ?? '')),
            'message_id'       => $messageId,
            'in_reply_to'      => $inReplyTo,
            'references'       => implode(' ', $references),
            'sent_by_admin_id' => $data['admin_id'] ?? null,
            'is_read'          => true,
            'has_attachments'  => count($attachments) > 0,
            'send_status'      => 'sent',
            'sent_at'          => now(),
        ]);

        // Copie locale des pièces jointes envoyées
        foreach ($attachments as $path) {
            $full = storage_path('app/public/' . ltrim($path, '/'));
            $message->attachments()->create([
                'filename'  => basename($path),
                'mime_type' => is_file($full) ? mime_content_type($full) : null,
                'size'      => is_file($full) ? filesize($full) : 0,
                'path'      => $path,
            ]);
        }

        $thread->update([
            'status'          => 'open',
            'last_message_at' => now(),
        ]);

        return $message;
    }

    /**
     * Récupère les nouveaux mails de la boîte IMAP et les range en base.
     *
     * @return int Nombre de nouveaux messages importés
     */
    public function syncInbox(): int
    {
        $imported = 0;

        $client = Client::account('default');
        $client->connect();

        $folder = $client->getFolderByName('INBOX');
        $messages = $folder->query()->unseen()->limit(100)->get();

        foreach ($messages as $mail) {
            try {
                $uid = (string) $mail->getUid();

                // Déjà importé ?
                if (MailboxMessage::where('imap_folder', 'INBOX')->where('imap_uid', $uid)->exists()) {
                    $mail->setFlag('Seen');
                    continue;
                }

                $fromObj   = $mail->getFrom()[0] ?? null;
                $fromEmail = $fromObj ? strtolower(trim((string) $fromObj->mail)) : 'unknown@unknown';
                $fromName  = $fromObj ? trim((string) $fromObj->personal) : null;

                $subject   = trim((string) $mail->getSubject());
                $messageId = trim((string) $mail->getMessageId());
                if ($messageId !== '' && !Str::startsWith($messageId, '<')) {
                    $messageId = '<' . $messageId . '>';
                }
                $inReplyTo  = trim((string) $mail->getInReplyTo());
                $references = trim((string) $mail->getReferences());

                $bodyHtml = (string) $mail->getHTMLBody();
                $bodyText = (string) $mail->getTextBody();
                if ($bodyHtml === '' && $bodyText !== '') {
                    $bodyHtml = nl2br(e($bodyText));
                }

                $thread = $this->resolveThread($fromEmail, $fromName, $subject, $inReplyTo, $references);

                $message = $thread->messages()->create([
                    'direction'       => 'inbound',
                    'from_email'      => $fromEmail,
                    'from_name'       => $fromName,
                    'to_email'        => config('mail.support_from', env('SUPPORT_MAIL_FROM', '')),
                    'subject'         => $subject ?: null,
                    'body_html'       => $bodyHtml ?: null,
                    'body_text'       => $bodyText ?: null,
                    'message_id'      => $messageId ?: null,
                    'in_reply_to'     => $inReplyTo ?: null,
                    'references'      => $references ?: null,
                    'imap_folder'     => 'INBOX',
                    'imap_uid'        => $uid,
                    'is_read'         => false,
                    'has_attachments' => $mail->hasAttachments(),
                    'send_status'     => 'received',
                ]);

                if ($mail->hasAttachments()) {
                    foreach ($mail->getAttachments() as $att) {
                        $name = (string) $att->getName() ?: ('piece-' . Str::random(6));
                        $path = 'mailbox_attachments/' . date('Y/m') . '/' . Str::random(8) . '_' . Str::slug(pathinfo($name, PATHINFO_FILENAME)) . '.' . pathinfo($name, PATHINFO_EXTENSION);
                        Storage::disk('public')->put($path, $att->getContent());
                        $message->attachments()->create([
                            'filename'   => $name,
                            'mime_type'  => (string) $att->getMimeType() ?: null,
                            'size'       => (int) $att->getSize(),
                            'path'       => $path,
                            'content_id' => $att->getId() ? (string) $att->getId() : null,
                        ]);
                    }
                }

                // Réception : on remonte le fil en haut de la liste au moment de l'import
                // (l'en-tête Date du mail peut être décalé selon le fuseau de l'expéditeur).
                $thread->update([
                    'status'          => 'open',
                    'last_message_at' => now(),
                ]);
                $thread->increment('unread_count');

                $mail->setFlag('Seen');
                $imported++;
            } catch (\Throwable $e) {
                Log::error('[Mailbox] Echec import message IMAP', ['error' => $e->getMessage()]);
            }
        }

        return $imported;
    }

    /**
     * Trouve le fil correspondant à un mail entrant : d'abord par les en-têtes
     * de threading, sinon par email + objet normalisé, sinon nouveau fil.
     */
    protected function resolveThread(string $fromEmail, ?string $fromName, string $subject, string $inReplyTo, string $references): MailboxThread
    {
        // 1) Par Message-ID référencé
        $refIds = array_filter(array_merge(
            $inReplyTo ? [$inReplyTo] : [],
            $references ? preg_split('/\s+/', $references) : []
        ));
        if (!empty($refIds)) {
            $match = MailboxMessage::whereIn('message_id', $refIds)->latest()->first();
            if ($match) {
                return $match->thread;
            }
        }

        // 2) Par contact + objet normalisé (sans Re:/Fwd:)
        $normalized = $this->normalizeSubject($subject);
        if ($normalized !== '') {
            $thread = MailboxThread::where('contact_email', $fromEmail)
                ->whereRaw('LOWER(TRIM(REPLACE(REPLACE(REPLACE(subject, "Re: ", ""), "RE: ", ""), "Fwd: ", ""))) = ?', [$normalized])
                ->latest()
                ->first();
            if ($thread) {
                return $thread;
            }
        }

        // 3) Nouveau fil
        return MailboxThread::create([
            'subject'         => $subject ?: '(sans objet)',
            'contact_email'   => $fromEmail,
            'contact_name'    => $fromName,
            'status'          => 'open',
            'last_message_at' => now(),
        ]);
    }

    protected function normalizeSubject(string $subject): string
    {
        $s = preg_replace('/^((re|fwd|tr|rép|rep)\s*:\s*)+/i', '', trim($subject));
        return strtolower(trim($s));
    }
}
