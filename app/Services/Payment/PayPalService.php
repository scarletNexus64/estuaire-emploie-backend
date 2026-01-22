<?php

namespace App\Services\Payment;

use App\Models\ServiceConfiguration;
use App\Models\Payment;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment as PayPalPayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class PayPalService
{
    protected ?ServiceConfiguration $config = null;
    protected ?ApiContext $apiContext = null;

    public function __construct()
    {
        $this->config = ServiceConfiguration::getPayPalConfig();

        if ($this->config && $this->config->isConfigured()) {
            $this->apiContext = new ApiContext(
                new OAuthTokenCredential(
                    $this->config->paypal_client_id,
                    $this->config->paypal_client_secret
                )
            );

            $this->apiContext->setConfig([
                'mode' => $this->config->paypal_mode ?? 'sandbox',
                'log.LogEnabled' => true,
                'log.FileName' => storage_path('logs/paypal.log'),
                'log.LogLevel' => 'INFO',
                'cache.enabled' => true,
            ]);
        }
    }

    /**
     * Initialize a PayPal payment
     *
     * @param User|Company $payer The entity making the payment
     * @param float $amount Payment amount
     * @param string $description Payment description
     * @param string|null $externalId Optional external ID
     * @param \Illuminate\Database\Eloquent\Model|null $payable The payable entity (e.g., SubscriptionPlan)
     * @return Payment
     * @throws \Exception
     */
    public function initPayment(
        $payer,
        float $amount,
        string $description,
        ?string $externalId = null,
        $payable = null
    ): Payment {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('PayPal service is not configured properly');
        }

        if (!$this->apiContext) {
            throw new \Exception('PayPal API context not initialized');
        }

        Log::info("╔════════════════════════════════════════════════════════════════════╗");
        Log::info("║ [PayPal Service] 💳 INITIATING PAYMENT                            ║");
        Log::info("╚════════════════════════════════════════════════════════════════════╝");
        Log::info("   💰 Amount: {$amount} {$this->config->paypal_currency}");
        Log::info("   📝 Description: {$description}");

        // Generate external ID if not provided
        if (!$externalId) {
            $externalId = 'PP-' . uniqid() . '-' . time();
        }
        Log::info("   ✓ External ID: {$externalId}");

        // Create Payment record in database (status: pending)
        $payment = DB::transaction(function () use ($payer, $amount, $description, $externalId, $payable) {
            $paymentData = [
                'amount' => $amount,
                'fees' => 0,
                'total' => $amount,
                'phone_number' => null,
                'description' => $description,
                'external_id' => $externalId,
                'status' => 'pending',
                'provider' => 'paypal',
                'payment_method' => 'paypal',
            ];

            if ($payer instanceof Company) {
                $paymentData['company_id'] = $payer->id;
                $paymentData['user_id'] = $payer->user_id ?? null;
            } elseif ($payer instanceof User) {
                $paymentData['user_id'] = $payer->id;
                $paymentData['company_id'] = $payer->company_id ?? null;
            }

            $payment = Payment::create($paymentData);

            // Associate with payable if provided
            if ($payable) {
                $payment->payable()->associate($payable);
                $payment->save();
            }

            Log::info("   ✓ Payment record created - ID: {$payment->id}");

            return $payment;
        });

        try {
            // Create PayPal payer
            $paypalPayer = new Payer();
            $paypalPayer->setPaymentMethod('paypal');

            // Create amount
            $paypalAmount = new Amount();
            $paypalAmount->setCurrency($this->config->paypal_currency)
                ->setTotal($amount);

            // Create transaction
            $transaction = new Transaction();
            $transaction->setAmount($paypalAmount)
                ->setDescription($description)
                ->setInvoiceNumber($externalId);

            // Set redirect URLs
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($this->config->paypal_return_url . '?payment_id=' . $payment->id)
                ->setCancelUrl($this->config->paypal_cancel_url . '?payment_id=' . $payment->id);

            // Create PayPal payment
            $paypalPayment = new PayPalPayment();
            $paypalPayment->setIntent('sale')
                ->setPayer($paypalPayer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

            // Execute payment creation
            $createdPayment = $paypalPayment->create($this->apiContext);

            // Get approval URL
            $approvalUrl = null;
            foreach ($createdPayment->getLinks() as $link) {
                if ($link->getRel() === 'approval_url') {
                    $approvalUrl = $link->getHref();
                    break;
                }
            }

            if (!$approvalUrl) {
                throw new \Exception('Could not retrieve PayPal approval URL');
            }

            // Update payment with PayPal reference
            $payment->update([
                'provider_reference' => $createdPayment->getId(),
                'payment_provider_response' => [
                    'paypal_payment_id' => $createdPayment->getId(),
                    'approval_url' => $approvalUrl,
                    'state' => $createdPayment->getState(),
                    'created_time' => $createdPayment->getCreateTime(),
                ],
            ]);

            Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::info("[PayPal Service] ✅ Payment created successfully!");
            Log::info("[PayPal Service] 📋 Payment ID: {$payment->id}");
            Log::info("[PayPal Service] 🔖 PayPal Payment ID: {$createdPayment->getId()}");
            Log::info("[PayPal Service] 🔗 Approval URL: {$approvalUrl}");
            Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            return $payment;

        } catch (\Exception $e) {
            // Update payment status to failed
            $payment->update([
                'status' => 'failed',
                'failure_reason' => $e->getMessage(),
            ]);

            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::error("[PayPal Service] ❌ Payment initialization failed");
            Log::error("[PayPal Service] ❌ Error: {$e->getMessage()}");
            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            throw $e;
        }
    }

    /**
     * Execute payment after user approval
     *
     * @param string $paymentId PayPal payment ID
     * @param string $payerId PayPal payer ID
     * @param Payment $payment Our payment record
     * @return Payment
     * @throws \Exception
     */
    public function executePayment(string $paymentId, string $payerId, Payment $payment): Payment
    {
        if (!$this->apiContext) {
            throw new \Exception('PayPal API context not initialized');
        }

        Log::info("╔════════════════════════════════════════════════════════════════════╗");
        Log::info("║ [PayPal Service] ✅ EXECUTING PAYMENT                             ║");
        Log::info("╚════════════════════════════════════════════════════════════════════╝");
        Log::info("   🔖 PayPal Payment ID: {$paymentId}");
        Log::info("   👤 Payer ID: {$payerId}");
        Log::info("   📋 Local Payment ID: {$payment->id}");

        try {
            // Get payment details
            $paypalPayment = PayPalPayment::get($paymentId, $this->apiContext);

            // Execute payment
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);

            $result = $paypalPayment->execute($execution, $this->apiContext);

            // Check if payment is approved
            if ($result->getState() === 'approved') {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'payment_provider_response' => [
                        'paypal_payment_id' => $result->getId(),
                        'state' => $result->getState(),
                        'payer_id' => $payerId,
                        'transactions' => $result->getTransactions(),
                    ],
                ]);

                Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                Log::info("[PayPal Service] ✅ Payment executed successfully!");
                Log::info("[PayPal Service] 📋 Payment ID: {$payment->id}");
                Log::info("[PayPal Service] 💰 Amount: {$payment->amount}");
                Log::info("[PayPal Service] ⏰ Paid At: {$payment->paid_at}");
                Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            } else {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => "Payment state: {$result->getState()}",
                ]);

                Log::warning("[PayPal Service] ⚠️ Payment not approved - State: {$result->getState()}");
            }

            return $payment;

        } catch (\Exception $e) {
            $payment->update([
                'status' => 'failed',
                'failure_reason' => $e->getMessage(),
            ]);

            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::error("[PayPal Service] ❌ Payment execution failed");
            Log::error("[PayPal Service] ❌ Error: {$e->getMessage()}");
            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            throw $e;
        }
    }

    /**
     * Test PayPal connection
     *
     * @return array
     */
    public function testConnection(): array
    {
        try {
            if (!$this->apiContext) {
                return [
                    'success' => false,
                    'message' => 'PayPal API context not initialized',
                ];
            }

            // Try to get access token
            $token = $this->apiContext->getCredential()->getAccessToken($this->apiContext->getConfig());

            if ($token) {
                return [
                    'success' => true,
                    'message' => 'PayPal connection successful',
                    'data' => [
                        'mode' => $this->config->paypal_mode,
                        'token_length' => strlen($token),
                    ],
                ];
            }

            return [
                'success' => false,
                'message' => 'Could not retrieve access token',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get payment details from PayPal
     *
     * @param string $paymentId
     * @return array
     * @throws \Exception
     */
    public function getPaymentDetails(string $paymentId): array
    {
        if (!$this->apiContext) {
            throw new \Exception('PayPal API context not initialized');
        }

        try {
            $payment = PayPalPayment::get($paymentId, $this->apiContext);

            return [
                'success' => true,
                'data' => [
                    'id' => $payment->getId(),
                    'state' => $payment->getState(),
                    'create_time' => $payment->getCreateTime(),
                    'update_time' => $payment->getUpdateTime(),
                    'intent' => $payment->getIntent(),
                    'payer' => $payment->getPayer(),
                    'transactions' => $payment->getTransactions(),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Could not retrieve payment details: ' . $e->getMessage(),
            ];
        }
    }
}
