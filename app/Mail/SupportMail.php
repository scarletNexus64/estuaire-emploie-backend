<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email;

class SupportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array  $attachments  Liste de chemins relatifs au disque "public"
     * @param  array  $references    Liste de Message-ID référencés (threading)
     */
    public function __construct(
        public string $subjectLine,
        public string $htmlBody,
        public string $fromAddress,
        public string $fromName,
        public string $replyToAddress,
        public array $files = [],
        public ?string $messageId = null,
        public ?string $inReplyTo = null,
        public array $references = [],
    ) {}

    public function build()
    {
        $mail = $this->from($this->fromAddress, $this->fromName)
            ->replyTo($this->replyToAddress, $this->fromName)
            ->subject($this->subjectLine)
            ->html($this->htmlBody);

        foreach ($this->files as $path) {
            $full = storage_path('app/public/' . ltrim($path, '/'));
            if (is_file($full)) {
                $mail->attach($full);
            }
        }

        $mail->withSymfonyMessage(function (Email $email) {
            $headers = $email->getHeaders();
            if ($this->messageId) {
                // messageId est attendu SANS chevrons par Symfony
                $headers->remove('Message-ID');
                $headers->addIdHeader('Message-ID', trim($this->messageId, '<>'));
            }
            if ($this->inReplyTo) {
                $headers->addTextHeader('In-Reply-To', $this->inReplyTo);
            }
            if (!empty($this->references)) {
                $headers->addTextHeader('References', implode(' ', $this->references));
            }
        });

        return $mail;
    }
}
