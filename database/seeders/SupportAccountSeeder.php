<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Crée le compte officiel « Estuaire Emploi » qui reçoit les demandes de
 * support envoyées depuis l'application.
 *
 * Le support in-app réutilise le mécanisme des conversations de service
 * (`POST /conversations/service`), qui exige un interlocuteur réel : il faut
 * donc un utilisateur en base. Ce compte n'est pas destiné à se connecter — son
 * mot de passe est aléatoire — il sert uniquement de destinataire, et les
 * réponses sont écrites depuis le panneau d'administration.
 */
class SupportAccountSeeder extends Seeder
{
    /** Adresse identifiant le compte ; sert de clé de recherche à l'API. */
    public const EMAIL = 'support@estuaire-emploi.com';

    public function run(): void
    {
        $user = User::withTrashed()->where('email', self::EMAIL)->first();

        if ($user) {
            // Le compte existe : on le restaure et on rafraîchit son identité
            // sans toucher au mot de passe déjà en place.
            $user->restore();
            $user->forceFill([
                'name' => 'Estuaire Emploi',
                'role' => 'admin',
                'profile_photo' => 'images/logo-estuaire-emploi.png',
                'is_active' => true,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();

            $this->command?->info('Compte support mis à jour (id ' . $user->id . ').');

            return;
        }

        $user = User::create([
            'name' => 'Estuaire Emploi',
            'email' => self::EMAIL,
            'role' => 'admin',
            // Aucun humain ne se connecte avec ce compte : le mot de passe est
            // aléatoire et n'est jamais communiqué.
            'password' => Hash::make(Str::random(48)),
            'profile_photo' => 'images/logo-estuaire-emploi.png',
            'bio' => "Compte officiel du support Estuaire Emploi.",
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command?->info('Compte support créé (id ' . $user->id . ').');
    }
}
