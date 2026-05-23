<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
    'password' => 'Le mot de passe fourni est incorrect.',
    'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',

    // Registration / Login
    'identifier_required' => 'Un email ou un numéro de téléphone est requis.',
    'identifier_required_short' => 'Veuillez fournir un email ou un numéro de téléphone.',
    'registration_success' => 'Inscription réussie',
    'login_success' => 'Connexion réussie',
    'login_failed' => 'Identifiant ou mot de passe incorrect.',
    'logout_success' => 'Déconnexion réussie',

    // Device
    'device_locked_pending' => 'Ce compte est lié à un autre appareil. Vous avez déjà une demande de changement en cours de traitement.',
    'device_locked' => 'Ce compte est lié à un autre appareil. Veuillez lancer une demande de changement d\'appareil qui sera validée par un administrateur.',

    // Role
    'already_in_role_recruiter' => 'Vous êtes déjà en mode recruteur',
    'already_in_role_candidate' => 'Vous êtes déjà en mode candidat',
    'role_switched' => 'Rôle changé avec succès',
    'role_updated' => 'Rôle mis à jour avec succès',

    // Profile
    'profile_updated' => 'Profil mis à jour avec succès',

    // Password
    'no_account_with_email' => 'Aucun compte trouvé avec cet email',
    'email_verified' => 'Email vérifié avec succès',
    'verify_email_otp_first' => 'Veuillez d\'abord vérifier le code OTP envoyé à votre email.',
    'verify_phone_otp_first' => 'Veuillez d\'abord vérifier le code OTP envoyé à votre téléphone.',
    'no_account_with_identifier' => 'Aucun compte trouvé avec cet identifiant',
    'password_reset_success' => 'Mot de passe réinitialisé avec succès',
    'incorrect_password' => 'Mot de passe incorrect',
    'old_password_incorrect' => 'L\'ancien mot de passe est incorrect',
    'new_password_must_differ' => 'Le nouveau mot de passe doit être différent de l\'ancien',
    'password_changed' => 'Mot de passe changé avec succès',

    // Account
    'account_deleted' => 'Compte supprimé avec succès',
    'account_delete_error' => 'Une erreur est survenue lors de la suppression du compte',

    // Availability
    'provide_email_or_phone' => 'Veuillez fournir un email ou un numéro de téléphone',
    'email_already_used' => 'Cette adresse email est déjà utilisée',
    'phone_already_used' => 'Ce numéro de téléphone est déjà utilisé',
    'available' => 'Disponible',
];
