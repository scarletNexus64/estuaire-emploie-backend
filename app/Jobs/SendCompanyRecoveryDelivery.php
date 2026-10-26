<?php

namespace App\Jobs;

use App\Mail\CompanyRecoveryMail;
use App\Models\CompanyRecoveryDelivery;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendCompanyRecoveryDelivery implements ShouldQueue
{
    use Queueable;

    public $tries = 4;
    public $timeout = 60;
    public $backoff = [30, 120, 600];

    public function __construct(public int $deliveryId)
    {
        $this->onQueue('company-recovery');
        $this->afterCommit();
    }

    public function handle(): void
    {
        try {
            DB::transaction(function () {
                $delivery = CompanyRecoveryDelivery::lockForUpdate()->find($this->deliveryId);
                if (!$delivery || $delivery->sent_at || $delivery->cancelled_at) return;
                if ($delivery->expires_at?->isPast()) {
                    $delivery->update(['payload' => null, 'cancelled_at' => now(), 'last_error' => 'Identifiants expirés : régénérer depuis la demande.']);
                    return;
                }
                $data = $delivery->payload;
                if ($delivery->channel === 'mail') {
                    Mail::mailer(config('company_recovery.mailer'))->to($data['email'])->send(new CompanyRecoveryMail($data));
                } else {
                    $user = User::find($data['user_id']);
                    if (!$user?->fcm_token) {
                        throw new \RuntimeException('Aucun appareil enregistré pour ce compte.');
                    }
                    $result = app(FirebaseNotificationService::class)->sendToToken($user->fcm_token, $data['title'], $data['message'], [
                        'type' => 'company_recovery_status',
                        'request_id' => (string) $delivery->request_id,
                        'status' => $data['status'],
                    ]);
                    if (empty($result['name'])) {
                        throw new \RuntimeException('Firebase n’a pas accepté la notification.');
                    }
                }
                $delivery->update(['sent_at' => now(), 'payload' => null, 'last_error' => null]);
            });
        } catch (\Throwable $e) {
            // Never persist transport errors containing email bodies/credentials.
            CompanyRecoveryDelivery::whereKey($this->deliveryId)->update(['last_error' => 'Envoi échoué. Une nouvelle tentative est nécessaire.']);
            throw new \RuntimeException('Échec de livraison de la demande de récupération #'.$this->deliveryId);
        }
    }
}
