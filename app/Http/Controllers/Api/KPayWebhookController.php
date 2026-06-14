<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessKPayWebhook;
use App\Models\ServiceConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Réception des webhooks KPay (source de vérité du statut des transactions).
 *
 * Sécurité : la signature HMAC-SHA256 est calculée sur le BODY BRUT et comparée
 * en temps constant. Réponse 200 immédiate puis traitement asynchrone (queue).
 * KPay retente sur 5xx/timeout (3s) mais pas sur 4xx → on renvoie 400 si la
 * signature est invalide (et non 200).
 */
class KPayWebhookController extends Controller
{
    public function handleDeposit(Request $request)
    {
        return $this->handle($request);
    }

    public function handleWithdrawal(Request $request)
    {
        return $this->handle($request);
    }

    public function handleGeneric(Request $request)
    {
        return $this->handle($request);
    }

    protected function handle(Request $request)
    {
        $raw = $request->getContent();
        $signature = $request->header('X-KPAY-Signature', '');
        $event = $request->header('X-KPAY-Event', '');

        $secret = ServiceConfiguration::getKPayConfig()?->kpay_webhook_secret;

        // Webhook secret OPTIONNEL :
        //  - Si un secret est configuré → on VÉRIFIE la signature (rejet 400 si invalide).
        //  - Sinon → on accepte le webhook sans vérification (mode sans signature)
        //    et on répond 200 pour que KPay ne retente pas indéfiniment.
        if ($secret) {
            if (!$this->signatureMatches($raw, $signature, $secret)) {
                // Log diagnostic : compare la signature reçue aux formats calculés.
                $hex = hash_hmac('sha256', $raw, $secret);
                Log::warning('🔔 [KPay] WEBHOOK signature invalide', [
                    'event' => $event,
                    'received' => $signature ?: '(vide)',
                    'expected_hex' => $hex,
                    'expected_base64' => base64_encode(hash_hmac('sha256', $raw, $secret, true)),
                ]);
                return response('Invalid signature', 400);
            }
            Log::info('🔔 [KPay] WEBHOOK signature valide', ['event' => $event]);
        } else {
            Log::info('🔔 [KPay] WEBHOOK accepté sans vérification (secret non configuré)', [
                'event' => $event,
            ]);
        }

        $payload = json_decode($raw, true);
        if (!is_array($payload)) {
            Log::warning('[KPay Webhook] Payload JSON invalide');
            return response('Invalid payload', 400);
        }

        $event = $event ?: ($payload['event'] ?? '');

        // Traitement asynchrone — on répond 200 immédiatement.
        ProcessKPayWebhook::dispatch($payload, $event);

        return response('OK', 200);
    }

    /**
     * Vérifie la signature en tolérant les formats possibles :
     *  - hex (hash_hmac défaut)
     *  - base64 (hash_hmac binaire encodé)
     *  - préfixe éventuel "sha256=" devant la valeur
     * Comparaison à temps constant (hash_equals).
     */
    protected function signatureMatches(string $raw, string $signature, string $secret): bool
    {
        if ($signature === '') {
            return false;
        }

        // Retire un éventuel préfixe "sha256=" (ou "hmac-sha256=").
        $sig = preg_replace('/^(sha256|hmac-sha256)=/i', '', trim($signature));

        $expectedHex = hash_hmac('sha256', $raw, $secret);                 // hex
        $expectedB64 = base64_encode(hash_hmac('sha256', $raw, $secret, true)); // base64

        return hash_equals($expectedHex, $sig)
            || hash_equals(strtolower($expectedHex), strtolower($sig))
            || hash_equals($expectedB64, $sig);
    }
}
