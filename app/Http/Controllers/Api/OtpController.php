<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\PhoneOtp;
use App\Models\ServiceConfiguration;
use App\Models\User;
use App\Services\Notifications\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    /**
     * Envoie un OTP par SMS (téléphone) ou par email.
     *
     * Body attendu (l'un ou l'autre) :
     *   { "phone": "+237690000000" }
     *   { "email": "user@example.com" }
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Détecter le canal
        if ($request->filled('phone')) {
            return $this->sendPhoneOtp($request->phone);
        }

        if ($request->filled('email')) {
            return $this->sendEmailOtp($request->email);
        }

        return response()->json([
            'message' => __('otp.provide_phone_or_email'),
        ], 422);
    }

    /**
     * Vérifie un OTP (SMS ou email).
     *
     * Body attendu :
     *   { "phone": "+237690000000", "code": "123456" }
     *   { "email": "user@example.com", "code": "123456" }
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'code'  => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        if ($request->filled('phone')) {
            return $this->verifyPhoneOtp($request->phone, $request->code);
        }

        if ($request->filled('email')) {
            return $this->verifyEmailOtp($request->email, $request->code);
        }

        return response()->json([
            'message' => __('otp.provide_phone_or_email'),
        ], 422);
    }

    // ──────────────────────────────────────────────────────────────
    // PRIVÉ — SMS
    // ──────────────────────────────────────────────────────────────

    private function sendPhoneOtp(string $phone): JsonResponse
    {
        // Nettoyer le numéro
        $phone = preg_replace('/\s+/', '', $phone);

        // Vérifier si le numéro est déjà utilisé
        if (User::where('phone', $phone)->exists()) {
            return response()->json([
                'message' => __('otp.phone_already_used'),
            ], 422);
        }

        // Générer un code à 6 chiffres
        $code = (string) random_int(100000, 999999);

        // Sauvegarder en base (5 minutes)
        PhoneOtp::updateOrCreate(
            ['phone' => $phone],
            [
                'code'       => $code,
                'expires_at' => Carbon::now()->addMinutes(5),
                'verified'   => false,
            ]
        );

        // Message OTP (utilisé pour le canal SMS)
        $message = "Utilisez \"{$code}\" pour poursuivre l'opération.\nValable pendant 5 minutes. Ne le partagez avec personne.";

        // Envoyer via le canal configuré (WhatsApp Meta ou SMS Nexah),
        // avec bascule automatique sur l'autre canal en cas d'échec.
        try {
            $result = $this->notificationService->sendOtp($phone, $code, $message);

            Log::info("[OTP] Envoi OTP → {$phone}", ['result' => $result]);

            if (empty($result['success'])) {
                Log::error("[OTP] Echec d'envoi de l'OTP pour {$phone}", ['result' => $result]);
                return response()->json([
                    'message' => __('otp.sms_send_failed'),
                ], 500);
            }

            return response()->json([
                'message' => __('otp.sms_sent'),
                'channel' => $result['channel'] ?? ServiceConfiguration::getDefaultNotificationChannel(),
            ], 200);

        } catch (\Exception $e) {
            Log::error("[OTP] Erreur envoi OTP : " . $e->getMessage());
            return response()->json([
                'message' => __('otp.sms_send_error'),
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function verifyPhoneOtp(string $phone, string $code): JsonResponse
    {
        $phone = preg_replace('/\s+/', '', $phone);

        $record = PhoneOtp::where('phone', $phone)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->where('verified', false)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => __('otp.code_invalid_or_expired'),
            ], 422);
        }

        $record->update(['verified' => true]);

        return response()->json([
            'message' => __('otp.phone_verified'),
        ], 200);
    }

    // ──────────────────────────────────────────────────────────────
    // PRIVÉ — EMAIL
    // ──────────────────────────────────────────────────────────────

    private function sendEmailOtp(string $email): JsonResponse
    {
        // Vérifier si l'email est déjà utilisé
        if (User::where('email', $email)->exists()) {
            return response()->json([
                'message' => __('otp.email_already_used'),
            ], 422);
        }

        // Générer un code à 6 chiffres
        $code = random_int(100000, 999999);

        // Sauvegarder en base (5 minutes comme le SMS)
        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'code'       => $code,
                'expires_at' => Carbon::now()->addMinutes(5),
                'verified'   => false,
            ]
        );

        try {
            // Envoi via le canal Brevo (même que la messagerie) → délivrabilité
            // rapide et hors spam, expéditeur vérifié sur le domaine.
            Mail::mailer('brevo')->raw(
                "Votre OTP est \"{$code}\"\nIl est valable pendant 5 minutes. Ne le partagez avec personne.",
                function ($message) use ($email) {
                    $message->to($email)
                        ->from(config('mail.support_from'), 'Estuaire Emploi')
                        ->subject('Code de vérification – Estuaire Emploie');
                }
            );

            return response()->json([
                'message' => __('otp.email_sent'),
                'channel' => 'email',
            ], 200);

        } catch (\Exception $e) {
            Log::error("[OTP] Erreur envoi email : " . $e->getMessage());
            return response()->json([
                'message' => __('otp.email_send_error'),
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function verifyEmailOtp(string $email, string $code): JsonResponse
    {
        $record = EmailVerification::where('email', $email)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->where('verified', false)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => __('otp.code_invalid_or_expired'),
            ], 422);
        }

        $record->update(['verified' => true]);

        return response()->json([
            'message' => __('otp.email_verified'),
        ], 200);
    }

    // ──────────────────────────────────────────────────────────────
    // PASSWORD RESET — SMS & EMAIL
    // ──────────────────────────────────────────────────────────────

    /**
     * Envoie un OTP pour le reset password (SMS ou email).
     * Contrairement à sendOtp(), vérifie que l'utilisateur EXISTE.
     */
    public function sendPasswordResetOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Détecter le canal
        if ($request->filled('phone')) {
            return $this->sendPasswordResetPhoneOtp($request->phone);
        }

        if ($request->filled('email')) {
            return $this->sendPasswordResetEmailOtp($request->email);
        }

        return response()->json([
            'message' => __('otp.provide_phone_or_email'),
        ], 422);
    }

    /**
     * Vérifie un OTP pour le reset password (SMS ou email).
     */
    public function verifyPasswordResetOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'code'  => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        if ($request->filled('phone')) {
            return $this->verifyPasswordResetPhoneOtp($request->phone, $request->code);
        }

        if ($request->filled('email')) {
            return $this->verifyPasswordResetEmailOtp($request->email, $request->code);
        }

        return response()->json([
            'message' => __('otp.provide_phone_or_email'),
        ], 422);
    }

    // ──────────────────────────────────────────────────────────────
    // PRIVÉ — PASSWORD RESET SMS
    // ──────────────────────────────────────────────────────────────

    private function sendPasswordResetPhoneOtp(string $phone): JsonResponse
    {
        // Nettoyer le numéro
        $phone = preg_replace('/\s+/', '', $phone);

        // Vérifier si le numéro existe dans la base
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return response()->json([
                'message' => __('otp.no_account_with_phone'),
            ], 404);
        }

        // Générer un code à 6 chiffres
        $code = (string) random_int(100000, 999999);

        // Sauvegarder en base (5 minutes)
        PhoneOtp::updateOrCreate(
            ['phone' => $phone],
            [
                'code'       => $code,
                'expires_at' => Carbon::now()->addMinutes(5),
                'verified'   => false,
            ]
        );

        // Message OTP (utilisé pour le canal SMS)
        $message = "Utilisez \"{$code}\" pour réinitialiser votre mot de passe.\nValable pendant 5 minutes. Ne le partagez avec personne.";

        // Envoyer via le canal configuré (WhatsApp Meta ou SMS Nexah),
        // avec bascule automatique sur l'autre canal en cas d'échec.
        try {
            $result = $this->notificationService->sendOtp($phone, $code, $message);

            Log::info("[PASSWORD RESET OTP] Envoi OTP → {$phone}", ['result' => $result]);

            if (empty($result['success'])) {
                Log::error("[PASSWORD RESET OTP] Echec d'envoi de l'OTP pour {$phone}", ['result' => $result]);
                return response()->json([
                    'message' => __('otp.sms_send_failed'),
                ], 500);
            }

            return response()->json([
                'message' => __('otp.reset_sms_sent'),
                'channel' => $result['channel'] ?? ServiceConfiguration::getDefaultNotificationChannel(),
            ], 200);

        } catch (\Exception $e) {
            Log::error("[PASSWORD RESET OTP] Erreur envoi OTP : " . $e->getMessage());
            return response()->json([
                'message' => __('otp.sms_send_error'),
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function verifyPasswordResetPhoneOtp(string $phone, string $code): JsonResponse
    {
        $phone = preg_replace('/\s+/', '', $phone);

        $record = PhoneOtp::where('phone', $phone)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->where('verified', false)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => __('otp.code_invalid_or_expired'),
            ], 422);
        }

        $record->update(['verified' => true]);

        return response()->json([
            'message' => __('otp.code_verified'),
        ], 200);
    }

    // ──────────────────────────────────────────────────────────────
    // PRIVÉ — PASSWORD RESET EMAIL
    // ──────────────────────────────────────────────────────────────

    private function sendPasswordResetEmailOtp(string $email): JsonResponse
    {
        // Vérifier si l'email existe dans la base
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'message' => __('otp.no_account_with_email'),
            ], 404);
        }

        // Générer un code à 6 chiffres
        $code = random_int(100000, 999999);

        // Sauvegarder en base (5 minutes)
        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'code'       => $code,
                'expires_at' => Carbon::now()->addMinutes(5),
                'verified'   => false,
            ]
        );

        try {
            // Envoi via le canal Brevo (même que la messagerie) → délivrabilité
            // rapide et hors spam, expéditeur vérifié sur le domaine.
            Mail::mailer('brevo')->raw(
                "Utilisez \"{$code}\" pour réinitialiser votre mot de passe.\nValable pendant 5 minutes. Ne le partagez avec personne.",
                function ($message) use ($email) {
                    $message->to($email)
                        ->from(config('mail.support_from'), 'Estuaire Emploi')
                        ->subject('Réinitialisation de mot de passe – Estuaire Emploie');
                }
            );

            return response()->json([
                'message' => __('otp.reset_email_sent'),
                'channel' => 'email',
            ], 200);

        } catch (\Exception $e) {
            Log::error("[PASSWORD RESET OTP] Erreur envoi email : " . $e->getMessage());
            return response()->json([
                'message' => __('otp.email_send_error'),
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function verifyPasswordResetEmailOtp(string $email, string $code): JsonResponse
    {
        $record = EmailVerification::where('email', $email)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->where('verified', false)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => __('otp.code_invalid_or_expired'),
            ], 422);
        }

        $record->update(['verified' => true]);

        return response()->json([
            'message' => __('otp.code_verified'),
        ], 200);
    }
}
