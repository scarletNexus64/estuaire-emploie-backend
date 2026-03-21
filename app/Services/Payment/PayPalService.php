<?php

namespace App\Services\Payment;

use App\Models\ServiceConfiguration;
use App\Models\Payment;
use App\Models\Company;
use App\Models\User;
use App\Services\CurrencyService;
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
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
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

            // Create amount - must be formatted as string with 2 decimals
            $totalAmount = number_format((float)$amount, 2, '.', '');

            Log::info("[PayPal Service] 💵 Formatting amount", [
                'original_amount' => $amount,
                'formatted_amount' => $totalAmount,
                'currency' => $this->config->paypal_currency,
            ]);

            $paypalAmount = new Amount();
            $paypalAmount->setCurrency($this->config->paypal_currency)
                ->setTotal($totalAmount);

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
     * Create a PayPal payment from an existing Payment record (for wallet recharge)
     *
     * @param Payment $payment
     * @return string The approval URL
     * @throws \Exception
     */
    public function createPayment(Payment $payment): string
    {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('PayPal service is not configured properly');
        }

        if (!$this->apiContext) {
            throw new \Exception('PayPal API context not initialized');
        }

        Log::info("╔════════════════════════════════════════════════════════════════════╗");
        Log::info("║ [PayPal Service] 💳 CREATING PAYMENT FOR WALLET RECHARGE          ║");
        Log::info("╚════════════════════════════════════════════════════════════════════╝");
        Log::info("   💰 Amount: {$payment->amount} {$payment->currency}");
        Log::info("   📋 Payment ID: {$payment->id}");

        try {
            // Generate external ID if not exists
            $externalId = $payment->external_id ?? 'PP-WR-' . $payment->id . '-' . time();

            // Update payment with external_id if needed
            if (!$payment->external_id) {
                $payment->update(['external_id' => $externalId]);
            }

            // Create PayPal payer
            $paypalPayer = new Payer();
            $paypalPayer->setPaymentMethod('paypal');

            // Convert amount to PayPal currency if different
            $paymentCurrency = $payment->currency ?? 'XAF';
            $paypalCurrency = $this->config->paypal_currency;
            $amountInPaypalCurrency = $payment->amount;

            if ($paymentCurrency !== $paypalCurrency) {
                Log::info("[PayPal Service] 💱 Converting currency", [
                    'from' => $paymentCurrency,
                    'to' => $paypalCurrency,
                    'original_amount' => $payment->amount,
                ]);

                try {
                    $amountInPaypalCurrency = $this->currencyService->convert(
                        $payment->amount,
                        $paymentCurrency,
                        $paypalCurrency
                    );

                    Log::info("[PayPal Service] ✅ Currency converted", [
                        'converted_amount' => $amountInPaypalCurrency,
                        'currency' => $paypalCurrency,
                    ]);
                } catch (\Exception $e) {
                    Log::error("[PayPal Service] ❌ Currency conversion failed: {$e->getMessage()}");
                    throw new \Exception("Impossible de convertir {$paymentCurrency} vers {$paypalCurrency}: {$e->getMessage()}");
                }
            }

            // Create amount - must be formatted as string with 2 decimals
            $totalAmount = number_format((float)$amountInPaypalCurrency, 2, '.', '');

            Log::info("[PayPal Service] 💵 Formatting amount", [
                'original_amount' => $payment->amount,
                'original_currency' => $paymentCurrency,
                'formatted_amount' => $totalAmount,
                'currency' => $paypalCurrency,
            ]);

            $paypalAmount = new Amount();
            $paypalAmount->setCurrency($paypalCurrency)
                ->setTotal($totalAmount);

            // Create transaction
            $transaction = new Transaction();
            $transaction->setAmount($paypalAmount)
                ->setDescription($payment->description ?? 'Wallet Recharge')
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
            $updateData = [
                'provider_reference' => $createdPayment->getId(),
                'provider' => 'paypal',
                'payment_provider_response' => [
                    'paypal_payment_id' => $createdPayment->getId(),
                    'approval_url' => $approvalUrl,
                    'state' => $createdPayment->getState(),
                    'created_time' => $createdPayment->getCreateTime(),
                ],
            ];

            // Add conversion info to metadata if currency was converted
            if ($paymentCurrency !== $paypalCurrency) {
                $metadata = $payment->metadata ?? [];
                $metadata['currency_conversion'] = [
                    'original_amount' => $payment->amount,
                    'original_currency' => $paymentCurrency,
                    'converted_amount' => $amountInPaypalCurrency,
                    'converted_currency' => $paypalCurrency,
                ];
                $updateData['metadata'] = $metadata;
            }

            $payment->update($updateData);

            Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::info("[PayPal Service] ✅ Payment created successfully!");
            Log::info("[PayPal Service] 📋 Payment ID: {$payment->id}");
            Log::info("[PayPal Service] 🔖 PayPal Payment ID: {$createdPayment->getId()}");
            Log::info("[PayPal Service] 🔗 Approval URL: {$approvalUrl}");
            Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            return $approvalUrl;

        } catch (\Exception $e) {
            // Update payment status to failed
            $payment->update([
                'status' => 'failed',
                'failure_reason' => $e->getMessage(),
            ]);

            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::error("[PayPal Service] ❌ Payment creation failed");
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

    /**
     * Create a native PayPal order for mobile/web checkout
     * Uses PayPal Orders API v2
     *
     * @param Payment $payment
     * @return array
     * @throws \Exception
     */
    public function createNativeOrder(Payment $payment): array
    {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('PayPal service is not configured properly');
        }

        Log::info("╔════════════════════════════════════════════════════════════════════╗");
        Log::info("║ [PayPal Service] 🏗️  CREATING NATIVE ORDER                        ║");
        Log::info("╚════════════════════════════════════════════════════════════════════╝");
        Log::info("   💰 Amount: {$payment->amount} {$payment->currency}");

        try {
            // Convert amount to PayPal currency if needed
            $paymentCurrency = $payment->currency ?? 'XAF';
            $paypalCurrency = $this->config->paypal_currency;
            $amountInPaypalCurrency = $payment->amount;

            if ($paymentCurrency !== $paypalCurrency) {
                Log::info("[PayPal Service] 💱 Converting currency", [
                    'from' => $paymentCurrency,
                    'to' => $paypalCurrency,
                    'original_amount' => $payment->amount,
                ]);

                $amountInPaypalCurrency = $this->currencyService->convert(
                    $payment->amount,
                    $paymentCurrency,
                    $paypalCurrency
                );

                // Store conversion info in metadata
                $metadata = $payment->metadata ?? [];
                $metadata['currency_conversion'] = [
                    'original_amount' => $payment->amount,
                    'original_currency' => $paymentCurrency,
                    'converted_amount' => $amountInPaypalCurrency,
                    'converted_currency' => $paypalCurrency,
                ];
                $payment->update(['metadata' => $metadata]);

                Log::info("[PayPal Service] ✅ Currency converted", [
                    'converted_amount' => $amountInPaypalCurrency,
                    'currency' => $paypalCurrency,
                ]);
            }

            // Format amount with 2 decimals
            $totalAmount = number_format((float)$amountInPaypalCurrency, 2, '.', '');

            // Get access token
            $accessToken = $this->getAccessToken();

            // Create order via PayPal Orders API v2
            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => 'WALLET_RECHARGE_' . $payment->id,
                        'description' => 'Wallet Recharge',
                        'amount' => [
                            'currency_code' => $paypalCurrency,
                            'value' => $totalAmount,
                        ],
                    ],
                ],
                'application_context' => [
                    'brand_name' => config('app.name', 'E-Emploie'),
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => $this->config->paypal_return_url . '?payment_id=' . $payment->id,
                    'cancel_url' => $this->config->paypal_cancel_url . '?payment_id=' . $payment->id,
                ],
            ];

            $baseUrl = $this->config->paypal_mode === 'live'
                ? 'https://api.paypal.com'
                : 'https://api.sandbox.paypal.com';

            $response = \Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->post($baseUrl . '/v2/checkout/orders', $orderData);

            if (!$response->successful()) {
                throw new \Exception('Failed to create PayPal order: ' . $response->body());
            }

            $responseData = $response->json();
            $orderId = $responseData['id'];

            // Extract approval URL
            $approvalUrl = null;
            if (isset($responseData['links'])) {
                foreach ($responseData['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }
            }

            if (!$approvalUrl) {
                throw new \Exception('Could not retrieve PayPal approval URL');
            }

            // Update payment with order ID
            $payment->update([
                'provider_reference' => $orderId,
                'provider' => 'paypal',
                'payment_provider_response' => $responseData,
            ]);

            Log::info("[PayPal Service] ✅ Native order created successfully", [
                'order_id' => $orderId,
                'amount' => $totalAmount,
                'currency' => $paypalCurrency,
                'approval_url' => $approvalUrl,
            ]);

            return [
                'success' => true,
                'order_id' => $orderId,
                'amount_usd' => $totalAmount,
                'approval_url' => $approvalUrl,
                'client_id' => $this->config->paypal_client_id,
            ];

        } catch (\Exception $e) {
            Log::error("[PayPal Service] ❌ Failed to create native order: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Capture a native PayPal order after user approval
     *
     * @param string $orderId
     * @param Payment $payment
     * @return array
     */
    public function captureNativeOrder(string $orderId, Payment $payment): array
    {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('PayPal service is not configured properly');
        }

        Log::info("╔════════════════════════════════════════════════════════════════════╗");
        Log::info("║ [PayPal Service] 💰 CAPTURING NATIVE ORDER                        ║");
        Log::info("╚════════════════════════════════════════════════════════════════════╝");
        Log::info("   🔖 Order ID: {$orderId}");

        try {
            // Get access token
            $accessToken = $this->getAccessToken();
            Log::info("[PayPal Service] 🔑 Access token obtained", [
                'token_length' => strlen($accessToken),
            ]);

            $baseUrl = $this->config->paypal_mode === 'live'
                ? 'https://api.paypal.com'
                : 'https://api.sandbox.paypal.com';

            $captureUrl = $baseUrl . '/v2/checkout/orders/' . $orderId . '/capture';

            Log::info("[PayPal Service] 📤 Preparing capture request", [
                'url' => $captureUrl,
                'order_id' => $orderId,
                'mode' => $this->config->paypal_mode,
                'base_url' => $baseUrl,
            ]);

            // Capture the order - PayPal expects empty JSON object {}
            // Using withBody to send raw JSON
            $response = \Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->withBody('{}', 'application/json')
              ->post($captureUrl);

            Log::info("[PayPal Service] 📥 Capture response received", [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body_preview' => substr($response->body(), 0, 500),
            ]);

            if (!$response->successful()) {
                $errorBody = $response->json();
                Log::error("[PayPal Service] ❌ Capture failed", [
                    'status' => $response->status(),
                    'body' => $errorBody,
                    'order_id' => $orderId,
                    'payment_id' => $payment->id,
                ]);

                return [
                    'success' => false,
                    'message' => $errorBody['message'] ?? 'Failed to capture PayPal order',
                ];
            }

            $responseData = $response->json();
            $status = $responseData['status'] ?? '';

            Log::info("[PayPal Service] 📥 Capture response", [
                'status' => $status,
                'order_id' => $orderId,
            ]);

            if ($status === 'COMPLETED') {
                // Update payment with capture details
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'payment_provider_response' => $responseData,
                ]);

                Log::info("[PayPal Service] ✅ Order captured successfully");

                return [
                    'success' => true,
                    'status' => 'completed',
                    'capture_id' => $responseData['purchase_units'][0]['payments']['captures'][0]['id'] ?? null,
                ];
            }

            // If not completed, mark as failed
            $payment->update([
                'status' => 'failed',
                'failure_reason' => "Order status: {$status}",
            ]);

            return [
                'success' => false,
                'message' => "Order not completed. Status: {$status}",
            ];

        } catch (\Exception $e) {
            Log::error("[PayPal Service] ❌ Failed to capture order: {$e->getMessage()}");

            return [
                'success' => false,
                'message' => 'Failed to capture order: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get PayPal access token
     *
     * @return string
     * @throws \Exception
     */
    private function getAccessToken(): string
    {
        try {
            $baseUrl = $this->config->paypal_mode === 'live'
                ? 'https://api.paypal.com'
                : 'https://api.sandbox.paypal.com';

            Log::info("[PayPal Service] 🔐 Requesting access token", [
                'base_url' => $baseUrl,
                'mode' => $this->config->paypal_mode,
                'client_id' => substr($this->config->paypal_client_id, 0, 10) . '...',
            ]);

            $response = \Http::asForm()
                ->withBasicAuth($this->config->paypal_client_id, $this->config->paypal_client_secret)
                ->post($baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            Log::info("[PayPal Service] 🔐 Access token response", [
                'status' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if (!$response->successful()) {
                Log::error("[PayPal Service] ❌ Access token request failed", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('Failed to get PayPal access token: ' . $response->body());
            }

            $tokenData = $response->json();
            $accessToken = $tokenData['access_token'];

            Log::info("[PayPal Service] ✅ Access token obtained successfully", [
                'token_type' => $tokenData['token_type'] ?? 'unknown',
                'expires_in' => $tokenData['expires_in'] ?? 'unknown',
                'token_preview' => substr($accessToken, 0, 20) . '...',
            ]);

            return $accessToken;

        } catch (\Exception $e) {
            Log::error("[PayPal Service] ❌ Failed to get access token: {$e->getMessage()}");
            throw $e;
        }
    }
}
