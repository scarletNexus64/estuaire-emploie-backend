<?php

return [
    'unavailable' => 'Le service INSAM-IA est momentanément indisponible. Réessayez dans quelques instants.',
    'not_configured' => "L'intégration INSAM-IA n'est pas encore activée sur cette plateforme.",
    'not_found' => 'Contenu INSAM-IA introuvable.',
    'student_mode_required' => 'Le Mode Étudiant est requis pour accéder aux ressources INSAM-IA.',
    'attempt_not_found' => 'Tentative introuvable.',
    'attempt_already_submitted' => 'Cette évaluation a déjà été soumise.',
    'generation_queued' => 'La fiche de révision est en cours de génération. Vous serez notifié dès qu\'elle sera prête.',
    'generation_ready_title' => 'Fiche de révision prête',
    'generation_ready_body' => 'Votre fiche de révision « :title » est disponible.',
    'generation_failed_title' => 'Génération impossible',
    'generation_failed_body' => 'La fiche de révision n\'a pas pu être générée. Réessayez plus tard.',
    'progress_saved' => 'Progression enregistrée.',
    'attestation_issued' => 'Votre attestation a été délivrée.',

    'mention' => [
        'excellent' => 'Excellent',
        'tres_bien' => 'Très bien',
        'bien' => 'Bien',
        'assez_bien' => 'Assez bien',
    ],

    'attestation_errors' => [
        'not_submitted' => "L'évaluation doit être soumise avant de délivrer une attestation.",
        'below_threshold' => 'Une note d\'au moins :threshold % est requise pour obtenir l\'attestation.',
        'not_available' => 'Aucune attestation disponible pour cette évaluation.',
        'pdf_unavailable' => "Le document n'a pas pu être généré. Réessayez dans un instant.",
    ],

    'attestation' => [
        'title' => 'ATTESTATION DE RÉUSSITE',
        'subtitle' => 'Parcours de formation',
        'tagline' => 'Plateforme emploi et formation',
        'awarded_to' => 'Décernée à',
        'statement' => 'pour avoir suivi et validé avec succès le parcours :course',
        'specialty' => 'Spécialité',
        'score' => 'Score obtenu',
        'percentage' => 'Résultat',
        'mention' => 'Mention',
        'reference' => 'Référence',
        'issued_on' => 'Délivrée le',
        'partner' => 'En partenariat avec',
        'signatory' => 'La Direction',
        'verify' => 'Document vérifiable auprès d\'Estuaire Emploi à l\'aide de la référence :reference.',
        'default_course' => 'Parcours de formation',
        'unknown_holder' => 'Étudiant',
    ],
];
