<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Service Configuration",
 *     description="API Endpoints pour la configuration et le test des services externes (WhatsApp, Nexah SMS, FreeMoPay)"
 * )
 */
class ServiceConfigController extends Controller
{
    /**
     * Display service configuration page
     */
    public function index()
    {
        $whatsappConfig = ServiceConfiguration::getWhatsAppConfig();
        $nexahConfig = ServiceConfiguration::getNexahConfig();
        $freemopayConfig = ServiceConfiguration::getFreeMoPayConfig();
        $kpayConfig = ServiceConfiguration::getKPayConfig();
        $paypalConfig = ServiceConfiguration::getPayPalConfig();
        $preferencesConfig = ServiceConfiguration::getConfig('notification_preferences');

        return view('admin.service-config.index', compact(
            'whatsappConfig',
            'nexahConfig',
            'freemopayConfig',
            'kpayConfig',
            'paypalConfig',
            'preferencesConfig'
        ));
    }

    /**
     * Update WhatsApp configuration
     */
    public function updateWhatsApp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'whatsapp_api_token' => 'required|string|min:10',
            'whatsapp_phone_number_id' => 'required|string|min:10',
            'whatsapp_api_version' => 'required|string',
            'whatsapp_template_name' => 'required|string|min:3',
            'whatsapp_language' => 'required|string|max:5',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Erreurs de validation: ' . implode(' | ', $errors));
        }

        try {
            $config = ServiceConfiguration::updateOrCreate(
                ['service_type' => 'whatsapp'],
                [
                    'whatsapp_api_token' => $request->whatsapp_api_token,
                    'whatsapp_phone_number_id' => $request->whatsapp_phone_number_id,
                    'whatsapp_api_version' => $request->whatsapp_api_version,
                    'whatsapp_template_name' => $request->whatsapp_template_name,
                    'whatsapp_language' => $request->whatsapp_language,
                    'is_active' => $request->has('is_active'),
                ]
            );

            // Clear cache
            ServiceConfiguration::clearCache('whatsapp');

            // Validate configuration
            $errors = $config->validateWhatsAppConfig();
            if (!empty($errors)) {
                return redirect()->back()
                    ->with('warning', 'Configuration sauvegardée avec des avertissements: ' . implode(', ', $errors));
            }

            return redirect()->back()
                ->with('success', 'Configuration WhatsApp mise à jour avec succès!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update Nexah SMS configuration
     */
    public function updateNexah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nexah_base_url' => 'required|url',
            'nexah_send_endpoint' => 'required|string',
            'nexah_credits_endpoint' => 'required|string',
            'nexah_user' => 'required|string|min:3',
            'nexah_password' => 'required|string|min:3',
            'nexah_sender_id' => 'required|string|min:3|max:11',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Erreurs de validation: ' . implode(' | ', $errors));
        }

        try {
            $config = ServiceConfiguration::updateOrCreate(
                ['service_type' => 'nexah_sms'],
                [
                    'nexah_base_url' => $request->nexah_base_url,
                    'nexah_send_endpoint' => $request->nexah_send_endpoint,
                    'nexah_credits_endpoint' => $request->nexah_credits_endpoint,
                    'nexah_user' => $request->nexah_user,
                    'nexah_password' => $request->nexah_password,
                    'nexah_sender_id' => $request->nexah_sender_id,
                    'is_active' => $request->has('is_active'),
                ]
            );

            // Clear cache
            ServiceConfiguration::clearCache('nexah_sms');

            // Validate configuration
            $errors = $config->validateNexahConfig();
            if (!empty($errors)) {
                return redirect()->back()
                    ->with('warning', 'Configuration sauvegardée avec des avertissements: ' . implode(', ', $errors));
            }

            return redirect()->back()
                ->with('success', 'Configuration Nexah SMS mise à jour avec succès!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update FreeMoPay configuration
     */
    public function updateFreeMoPay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'freemopay_base_url' => 'required|url',
            'freemopay_app_key' => 'required|string|min:5',
            'freemopay_secret_key' => 'required|string|min:5',
            'freemopay_callback_url' => 'required|url',
            'freemopay_init_payment_timeout' => 'required|integer|min:1|max:30',
            'freemopay_status_check_timeout' => 'required|integer|min:1|max:30',
            'freemopay_token_timeout' => 'required|integer|min:1|max:30',
            'freemopay_token_cache_duration' => 'required|integer|min:60|max:3600',
            'freemopay_max_retries' => 'required|integer|min:0|max:5',
            'freemopay_retry_delay' => 'required|numeric|min:0|max:5',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Erreurs de validation: ' . implode(' | ', $errors));
        }

        try {
            $config = ServiceConfiguration::updateOrCreate(
                ['service_type' => 'freemopay'],
                [
                    'freemopay_base_url' => $request->freemopay_base_url,
                    'freemopay_app_key' => $request->freemopay_app_key,
                    'freemopay_secret_key' => $request->freemopay_secret_key,
                    'freemopay_callback_url' => $request->freemopay_callback_url,
                    'freemopay_init_payment_timeout' => $request->freemopay_init_payment_timeout,
                    'freemopay_status_check_timeout' => $request->freemopay_status_check_timeout,
                    'freemopay_token_timeout' => $request->freemopay_token_timeout,
                    'freemopay_token_cache_duration' => $request->freemopay_token_cache_duration,
                    'freemopay_max_retries' => $request->freemopay_max_retries,
                    'freemopay_retry_delay' => $request->freemopay_retry_delay,
                    'is_active' => $request->has('is_active'),
                ]
            );

            // Clear cache
            ServiceConfiguration::clearCache('freemopay');

            // Validate configuration
            $errors = $config->validateFreeMoPayConfig();
            if (!empty($errors)) {
                return redirect()->back()
                    ->with('warning', 'Configuration sauvegardée avec des avertissements: ' . implode(', ', $errors));
            }

            return redirect()->back()
                ->with('success', 'Configuration FreeMoPay mise à jour avec succès!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update KPay configuration
     */
    public function updateKPay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kpay_base_url' => 'required|url',
            'kpay_api_key' => 'required|string|min:8',
            'kpay_secret_key' => 'required|string|min:8',
            'kpay_webhook_secret' => 'nullable|string|min:8',
            'kpay_environment' => 'required|in:sandbox,live',
            'kpay_deposit_callback_url' => 'nullable|url',
            'kpay_withdrawal_callback_url' => 'nullable|url',
            'kpay_init_payment_timeout' => 'required|integer|min:5|max:120',
            'kpay_status_check_timeout' => 'required|integer|min:5|max:120',
            'kpay_max_retries' => 'required|integer|min:0|max:5',
            'kpay_retry_delay' => 'required|numeric|min:0|max:5',
            'kpay_min_deposit' => 'required|integer|min:50|max:1000000',
            'kpay_min_withdrawal' => 'required|integer|min:100|max:1000000',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Erreurs de validation: ' . implode(' | ', $errors));
        }

        try {
            $config = ServiceConfiguration::updateOrCreate(
                ['service_type' => 'kpay'],
                [
                    'kpay_base_url' => $request->kpay_base_url,
                    'kpay_api_key' => $request->kpay_api_key,
                    'kpay_secret_key' => $request->kpay_secret_key,
                    'kpay_webhook_secret' => $request->kpay_webhook_secret,
                    'kpay_environment' => $request->kpay_environment,
                    'kpay_deposit_callback_url' => $request->kpay_deposit_callback_url,
                    'kpay_withdrawal_callback_url' => $request->kpay_withdrawal_callback_url,
                    'kpay_init_payment_timeout' => $request->kpay_init_payment_timeout,
                    'kpay_status_check_timeout' => $request->kpay_status_check_timeout,
                    'kpay_max_retries' => $request->kpay_max_retries,
                    'kpay_retry_delay' => $request->kpay_retry_delay,
                    'kpay_min_deposit' => $request->kpay_min_deposit,
                    'kpay_min_withdrawal' => $request->kpay_min_withdrawal,
                    'is_active' => $request->has('is_active'),
                ]
            );

            ServiceConfiguration::clearCache('kpay');

            $errors = $config->validateKPayConfig();
            if (!empty($errors)) {
                return redirect()->back()
                    ->with('warning', 'Configuration sauvegardée avec des avertissements: ' . implode(', ', $errors));
            }

            return redirect()->back()
                ->with('success', 'Configuration KPay mise à jour avec succès!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update PayPal configuration
     */
    public function updatePayPal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paypal_mode' => 'required|in:sandbox,live',
            'paypal_client_id' => 'required|string|min:5',
            'paypal_client_secret' => 'required|string|min:5',
            'paypal_currency' => 'required|string|size:3',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Erreurs de validation: ' . implode(' | ', $errors));
        }

        try {
            $config = ServiceConfiguration::updateOrCreate(
                ['service_type' => 'paypal'],
                [
                    'paypal_mode' => $request->paypal_mode,
                    'paypal_client_id' => $request->paypal_client_id,
                    'paypal_client_secret' => $request->paypal_client_secret,
                    'paypal_currency' => strtoupper($request->paypal_currency),
                    'paypal_return_url' => config('app.frontend_url') . '/payment/success',
                    'paypal_cancel_url' => config('app.frontend_url') . '/payment/cancel',
                    'is_active' => $request->has('is_active'),
                ]
            );

            // Clear cache
            ServiceConfiguration::clearCache('paypal');

            // Validate configuration
            $errors = $config->validatePayPalConfig();
            if (!empty($errors)) {
                return redirect()->back()
                    ->with('warning', 'Configuration sauvegardée avec des avertissements: ' . implode(', ', $errors));
            }

            return redirect()->back()
                ->with('success', 'Configuration PayPal mise à jour avec succès!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update notification preferences
     */
    public function updateNotificationPreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'default_notification_channel' => 'required|in:whatsapp,sms',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs.');
        }

        try {
            ServiceConfiguration::updateOrCreate(
                ['service_type' => 'notification_preferences'],
                [
                    'default_notification_channel' => $request->default_notification_channel,
                    'use_otp' => $request->has('use_otp'),
                    'use_paypal' => $request->has('use_paypal'),
                    'is_active' => true,
                ]
            );

            return redirect()->back()
                ->with('success', 'Préférences de notification mises à jour avec succès!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Test WhatsApp connection
     *
     * @OA\Post(
     *     path="/admin/service-config/test/whatsapp",
     *     summary="Tester la connexion WhatsApp Business API",
     *     description="Vérifie la validité des credentials WhatsApp Business en appelant l'API Facebook Graph",
     *     tags={"Service Configuration"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Test de connexion réussi",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="WhatsApp connection successful"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="verified_name", type="string", example="Ma Business"),
     *                 @OA\Property(property="display_phone_number", type="string", example="+237 6XX XXX XXX"),
     *                 @OA\Property(property="quality_rating", type="string", example="GREEN")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Configuration invalide ou connexion échouée",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Configuration WhatsApp invalide ou incomplète"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function testWhatsApp()
    {
        $config = ServiceConfiguration::getWhatsAppConfig();

        if (!$config || !$config->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.whatsapp_invalid')
            ], 400);
        }

        $whatsappService = new \App\Services\Notifications\WhatsAppService();
        $result = $whatsappService->testConnection();

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Test Nexah SMS connection
     *
     * @OA\Post(
     *     path="/admin/service-config/test/nexah",
     *     summary="Tester la connexion Nexah SMS API",
     *     description="Vérifie la validité des credentials Nexah SMS en appelant l'API de vérification de crédits",
     *     tags={"Service Configuration"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Test de connexion réussi",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Nexah SMS connection successful"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="credits", type="number", example=1500.50),
     *                 @OA\Property(property="currency", type="string", example="XAF")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Configuration invalide ou connexion échouée",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Configuration Nexah invalide ou incomplète"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function testNexah()
    {
        $config = ServiceConfiguration::getNexahConfig();

        if (!$config || !$config->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.nexah_invalid')
            ], 400);
        }

        $nexahService = new \App\Services\Notifications\NexahService();
        $result = $nexahService->testConnection();

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Test FreeMoPay connection
     *
     * @OA\Post(
     *     path="/admin/service-config/test/freemopay",
     *     summary="Tester la connexion FreeMoPay API",
     *     description="Vérifie la validité des credentials FreeMoPay en générant un token d'accès",
     *     tags={"Service Configuration"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Test de connexion réussi",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="FreeMoPay connection successful, token generated"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token_length", type="integer", example=256),
     *                 @OA\Property(property="token_preview", type="string", example="eyJhbGciOi...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Configuration invalide ou connexion échouée",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Connection test failed: Connection timed out"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function testFreeMoPay()
    {
        $config = ServiceConfiguration::getFreeMoPayConfig();

        if (!$config || !$config->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.freemopay_invalid')
            ], 400);
        }

        $freemopayService = new \App\Services\Payment\FreeMoPayService();
        $result = $freemopayService->testConnection();

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Test KPay connection (appelle /payments/balance)
     */
    public function testKPay()
    {
        $config = ServiceConfiguration::getKPayConfig();

        if (!$config || !$config->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration KPay incomplète ou inactive.'
            ], 400);
        }

        $result = (new \App\Services\Payment\KPayService())->testConnection();

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Send test WhatsApp message
     *
     * @OA\Post(
     *     path="/admin/service-config/send-test/whatsapp",
     *     summary="Envoyer un message WhatsApp de test",
     *     description="Envoie un code OTP de test via WhatsApp Business API pour vérifier le bon fonctionnement",
     *     tags={"Service Configuration"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone","otp"},
     *             @OA\Property(property="phone", type="string", description="Numéro de téléphone (format: +237658895572 ou 237658895572)", example="+237658895572"),
     *             @OA\Property(property="otp", type="string", description="Code OTP à envoyer (6 chiffres)", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Message envoyé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="WhatsApp message sent successfully"),
     *             @OA\Property(property="message_id", type="string", example="wamid.XXX"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur lors de l'envoi ou configuration invalide",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="config_debug", type="object")
     *         )
     *     )
     * )
     */
    public function sendTestWhatsApp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'otp' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.invalid_data', ['error' => $validator->errors()->first()])
            ], 400);
        }

        $config = ServiceConfiguration::getWhatsAppConfig();

        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.whatsapp_not_saved')
            ], 400);
        }

        // Check what's missing in configuration
        $errors = $config->validateWhatsAppConfig();
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.config_incomplete', ['fields' => implode(', ', $errors)]),
                'errors' => $errors,
                'config_debug' => [
                    'has_token' => !empty($config->whatsapp_api_token),
                    'has_phone_id' => !empty($config->whatsapp_phone_number_id),
                    'has_template' => !empty($config->whatsapp_template_name),
                    'is_active' => $config->is_active
                ]
            ], 400);
        }

        try {
            $whatsappService = new \App\Services\Notifications\WhatsAppService();
            $result = $whatsappService->sendOtp(
                $request->input('phone'),
                $request->input('otp')
            );

            return response()->json($result, $result['success'] ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.error', ['error' => $e->getMessage()])
            ], 500);
        }
    }

    /**
     * Send test Nexah SMS
     *
     * @OA\Post(
     *     path="/admin/service-config/send-test/nexah",
     *     summary="Envoyer un SMS de test via Nexah",
     *     description="Envoie un message SMS de test via l'API Nexah pour vérifier le bon fonctionnement",
     *     tags={"Service Configuration"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone","message"},
     *             @OA\Property(property="phone", type="string", description="Numéro de téléphone (format: +237658895572 ou 237658895572)", example="+237658895572"),
     *             @OA\Property(property="message", type="string", description="Message à envoyer", example="Ceci est un message de test depuis Estuaire Emploie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="SMS envoyé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="SMS sent successfully"),
     *             @OA\Property(property="message_id", type="string", example="MSG123456"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur lors de l'envoi ou configuration invalide",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function sendTestNexah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.invalid_data', ['error' => $validator->errors()->first()])
            ], 400);
        }

        $config = ServiceConfiguration::getNexahConfig();

        if (!$config || !$config->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.nexah_invalid_full')
            ], 400);
        }

        try {
            $nexahService = new \App\Services\Notifications\NexahService();
            $result = $nexahService->sendSms(
                $request->input('phone'),
                $request->input('message')
            );

            return response()->json($result, $result['success'] ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.error', ['error' => $e->getMessage()])
            ], 500);
        }
    }

    /**
     * Test PayPal connection
     */
    public function testPayPal()
    {
        $config = ServiceConfiguration::getPayPalConfig();

        if (!$config || !$config->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => __('service_config.paypal_invalid')
            ], 400);
        }

        $paypalService = new \App\Services\Payment\PayPalService();
        $result = $paypalService->testConnection();

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Clear all service configuration cache
     */
    public function clearCache()
    {
        try {
            ServiceConfiguration::clearCache();

            return redirect()->back()
                ->with('success', 'Cache des configurations nettoyé avec succès!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors du nettoyage du cache: ' . $e->getMessage());
        }
    }
}
