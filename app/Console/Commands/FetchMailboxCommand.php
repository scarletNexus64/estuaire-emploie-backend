<?php

namespace App\Console\Commands;

use App\Services\MailboxService;
use Illuminate\Console\Command;

class FetchMailboxCommand extends Command
{
    protected $signature = 'mailbox:fetch';

    protected $description = 'Récupère les nouveaux emails de la boîte support (IMAP) et les range dans la messagerie';

    public function handle(MailboxService $service): int
    {
        $count = $service->syncInbox();
        $this->info("Messagerie : {$count} nouveau(x) message(s) importé(s).");

        return self::SUCCESS;
    }
}
