<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Services\Notifications\NexahService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
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
            'message' => 'Veuillez fournir un numéro de téléphone ou une adresse email.',
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
            'message' => 'Veuillez fournir un numéro de téléphone ou une adresse email.',
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
                'message' => 'Ce numéro de téléphone est déjà associé à un compte.',
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

        // Message OTP
        // $message = "Votre OTP est \"{$code}\"\nIl est valable pendant 5 minutes. Ne le partagez avec personne.";
           $message = "Utilisez \"{$code}\" pour poursuivre l'opération.\nValable pendant 5 minutes. Ne le partagez avec personne.";

        // Envoyer via Nexah (2 fois avec senderID différents)
        try {
            $nexah = new NexahService();

            // Envoi 1 : senderID = 'infos'
            $result1 = $nexah->sendSms($phone, $message, 'infos');
            Log::info("[OTP] SMS 1 (senderID: infos) → {$phone}", ['result' => $result1]);

            // Envoi 2 : senderID = celui de la config admin
            $result2 = $nexah->sendSms($phone, $message);
            Log::info("[OTP] SMS 2 (senderID: config) → {$phone}", ['result' => $result2]);

            if (!$result1['success'] && !$result2['success']) {
                Log::error("[OTP] Les deux envois SMS ont échoué pour {$phone}");
                return response()->json([
                    'message' => 'Impossible d\'envoyer le SMS. Veuillez réessayer.',
                ], 500);
            }

            return response()->json([
                'message' => 'Code OTP envoyé par SMS.',
                'channel' => 'sms',
            ], 200);

        } catch (\Exception $e) {
            Log::error("[OTP] Erreur envoi SMS : " . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de l\'envoi du SMS. Veuillez réessayer.',
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
                'message' => 'Code invalide ou expiré.',
            ], 422);
        }

        $record->update(['verified' => true]);

        return response()->json([
            'message' => 'Numéro vérifié avec succès.',
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
                'message' => 'Cet email est déjà associé à un compte.',
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
            Mail::raw(
                "Votre OTP est \"{$code}\"\nIl est valable pendant 5 minutes. Ne le partagez avec personne.",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Code de vérification – Estuaire Emploie');
                }
            );

            return response()->json([
                'message' => 'Code OTP envoyé par email.',
                'channel' => 'email',
            ], 200);

        } catch (\Exception $e) {
            Log::error("[OTP] Erreur envoi email : " . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer.',
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
                'message' => 'Code invalide ou expiré.',
            ], 422);
        }

        $record->update(['verified' => true]);

        return response()->json([
            'message' => 'Email vérifié avec succès.',
        ], 200);
    }
}
