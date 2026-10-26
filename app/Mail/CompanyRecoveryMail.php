<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class CompanyRecoveryMail extends Mailable
{
    public function __construct(public array $details) {}

    public function build(): static
    {
        return $this->from(config('mail.support_from'), config('mail.support_from_name', 'Estuaire Emploi'))
            ->subject($this->details['title'])
            ->view('emails.company-recovery', ['details' => $this->details]);
    }
}
