<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailVerificationController extends Controller
{
    /**
     * Envoie un code de vérification à l'email spécifié
     */
    public function sendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = $request->email;

        // Vérifier si l'email existe déjà dans la table users
        if (User::where('email', $email)->exists()) {
            return response()->json([
                'message' => 'Cet email est déjà utilisé par un autre compte.',
            ], 422);
        }

        // Générer un code à 6 chiffres
        $code = random_int(100000, 999999);

        // Créer ou mettre à jour l'enregistrement de vérification
        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(10),
                'verified' => false,
            ]
        );

        // Envoyer l'email avec le code
        try {
            Mail::raw(
                "Bonjour,\n\nVotre code de vérification Estuaire Emploie est : $code\n\nCe code expire dans 10 minutes.\n\nSi vous n'avez pas demandé ce code, ignorez cet email.\n\nL'équipe Estuaire Emploie",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Code de vérification - Estuaire Emploie');
                }
            );

            return response()->json([
                'message' => 'Code envoyé par email',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Vérifie le code saisi par l'utilisateur
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $record = EmailVerification::where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->where('verified', false)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Code invalide ou expiré',
            ], 422);
        }

        // Marquer comme vérifié
        $record->update(['verified' => true]);

        return response()->json([
            'message' => 'Email vérifié avec succès',
        ], 200);
    }
}
