<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payment\KPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Retour de la passerelle hébergée KPay (paiement par carte bancaire).
 *
 * KPay redirige le client vers cette URL avec une query signée :
 *   ?status=COMPLETED&reference=…&externalId=…&ts=…&sig=<hmac-sha256-hex>
 *
 * Cette page n'est qu'un signal de redirection. Elle ne crédite JAMAIS un
 * wallet : le crédit reste porté par le webhook `payment.completed` (source de
 * vérité) et le job de polling en secours. Une redirection est triviale à
 * forger, et la signature seule ne prouve pas que l'opérateur a encaissé.
 *
 * Elle sert à deux choses :
 *  - donner un retour visuel immédiat au client dans la WebView de l'app ;
 *  - exposer une URL que la WebView détecte pour se refermer.
 */
class KPayGatewayReturnController extends Controller
{
    /**
     * GET /api/payments/kpay/return
     */
    public function handle(Request $request, KPayService $kpay)
    {
        $status = (string) $request->query('status', '');
        $reference = (string) $request->query('reference', '');
        $externalId = (string) $request->query('externalId', '');
        $timestamp = (string) $request->query('ts', '');
        $signature = (string) $request->query('sig', '');

        $signatureValid = $signature !== ''
            && $kpay->verifyGatewaySignature($status, $reference, $externalId, $timestamp, $signature);

        if (!$signatureValid) {
            Log::warning('[KPay] Retour passerelle avec signature invalide', [
                'external_id' => $externalId,
                'status' => $status,
            ]);
        }

        $payment = $externalId ? Payment::where('external_id', $externalId)->first() : null;

        // Le statut affiché vient de notre base (alimentée par le webhook), pas
        // de la query : celle-ci n'est qu'une indication côté client.
        $resolvedStatus = $payment?->status ?? 'pending';

        Log::info('[KPay] Retour passerelle', [
            'external_id' => $externalId,
            'gateway_status' => $status,
            'payment_status' => $resolvedStatus,
            'signature_valid' => $signatureValid,
        ]);

        // La WebView de l'app détecte cette URL et lit le JSON ; un navigateur
        // classique reçoit la même information.
        return response()->json([
            'success' => true,
            'data' => [
                'payment_id' => $payment?->id,
                'status' => $resolvedStatus,
                'gateway_status' => $status,
                'signature_valid' => $signatureValid,
                // Le statut final n'est jamais synchrone : l'app poll
                // `payment-status` jusqu'au verdict du webhook.
                'poll_url' => $payment ? "/api/wallet/payment-status/{$payment->id}" : null,
            ],
        ]);
    }
}
