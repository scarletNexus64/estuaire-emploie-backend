<?php

namespace App\Console\Commands;

use App\Jobs\SendCompanyRecoveryDelivery;
use App\Models\CompanyRecoveryDelivery;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeliverCompanyRecoveries extends Command
{
    protected $signature = 'company-recoveries:deliver';
    protected $description = 'Relance les notifications de récupération en attente et efface les secrets expirés';

    public function handle(): int
    {
        CompanyRecoveryDelivery::whereNull('sent_at')->whereNull('cancelled_at')->where('expires_at', '<=', now())
            ->update(['payload' => null, 'cancelled_at' => now(), 'last_error' => 'Identifiants expirés : régénérer depuis la demande.']);
        CompanyRecoveryDelivery::whereNull('sent_at')->whereNull('cancelled_at')->orderBy('id')->limit(100)->get()
            ->each(fn ($delivery) => SendCompanyRecoveryDelivery::dispatch($delivery->id));
        DB::table('company_recovery_email_challenges')->where('expires_at', '<', now()->subDay())->delete();
        DB::table('api_password_reset_grants')->where('expires_at', '<', now())->delete();
        return self::SUCCESS;
    }
}
