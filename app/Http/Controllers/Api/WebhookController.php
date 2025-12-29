<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle FreeMoPay webhook callback
     *
     * @OA\Post(
     *     path="/api/webhooks/freemopay",
     *     summary="FreeMoPay payment callback webhook",
     *     description="Receives payment status updates from FreeMoPay",
     *     tags={"Webhooks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="reference", type="string", example="FMP123456789"),
     *             @OA\Property(property="status", type="string", example="SUCCESS"),
     *             @OA\Property(property="externalId", type="string", example="PAY-20251212120000"),
     *             @OA\Property(property="amount", type="number", example=5000),
     *             @OA\Property(property="payer", type="string", example="237658895572"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Webhook processed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Webhook processed successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid webhook data"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     )
     * )
     */
    public function freemopayCallback(Request $request)
    {
        // Log incoming webhook
        Log::info("[FreeMoPay Webhook] Received callback");
        Log::info("[FreeMoPay Webhook] Headers: " . json_encode($request->headers->all()));
        Log::info("[FreeMoPay Webhook] Body: " . json_encode($request->all()));

        try {
            // Get webhook data
            $reference = $request->input('reference');
            $status = $request->input('status');
            $externalId = $request->input('externalId');
            $amount = $request->input('amount');
            $payer = $request->input('payer');

            // Validate required fields
            if (!$reference || !$status) {
                Log::error("[FreeMoPay Webhook] Missing required fields");
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required fields: reference and status are required'
                ], 400);
            }

            // Find payment by provider_reference or external_id
            $payment = Payment::where('provider_reference', $reference)
                ->orWhere('external_id', $externalId)
                ->first();

            if (!$payment) {
                Log::warning("[FreeMoPay Webhook] Payment not found - Reference: {$reference}, External ID: {$externalId}");
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            Log::info("[FreeMoPay Webhook] Payment found - ID: {$payment->id}, Current status: {$payment->status}");

            // Map FreeMoPay status to our status
            $newStatus = $this->mapFreeMoPayStatus($status);

            // Update payment
            $payment->update([
                'status' => $newStatus,
                'provider_response' => array_merge(
                    $payment->provider_response ?? [],
                    ['webhook' => $request->all()]
                ),
                'completed_at' => in_array($newStatus, ['success', 'failed']) ? now() : null,
            ]);

            Log::info("[FreeMoPay Webhook] Payment updated - ID: {$payment->id}, New status: {$newStatus}");

            // Trigger events based on status
            if ($newStatus === 'success') {
                // TODO: Trigger payment success event
                // event(new PaymentSuccessful($payment));
                Log::info("[FreeMoPay Webhook] Payment successful - ID: {$payment->id}");
            } elseif ($newStatus === 'failed') {
                // TODO: Trigger payment failed event
                // event(new PaymentFailed($payment));
                Log::warning("[FreeMoPay Webhook] Payment failed - ID: {$payment->id}");
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error("[FreeMoPay Webhook] Error processing webhook: " . $e->getMessage());
            Log::error("[FreeMoPay Webhook] Stack trace: " . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error processing webhook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map FreeMoPay status to internal status
     *
     * @param string $freemopayStatus
     * @return string
     */
    protected function mapFreeMoPayStatus(string $freemopayStatus): string
    {
        return match (strtoupper($freemopayStatus)) {
            'SUCCESS', 'SUCCESSFUL', 'COMPLETED' => 'success',
            'FAILED', 'FAILURE', 'ERROR', 'REJECTED' => 'failed',
            'PENDING', 'PROCESSING', 'INITIATED' => 'pending',
            'CANCELLED', 'CANCELED' => 'cancelled',
            default => 'pending',
        };
    }
}