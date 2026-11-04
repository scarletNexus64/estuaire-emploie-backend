<?php

return [
    'deleted_user' => 'Utilisateur supprimé',

    'errors' => [
        'not_found' => 'Ressource introuvable.',
        'item_not_found' => 'Cette publication n\'existe pas ou n\'est plus disponible.',
        'comment_not_found' => 'Ce commentaire n\'existe pas ou a été supprimé.',
        'parent_not_found' => 'Le commentaire auquel vous répondez n\'existe plus.',
        'company_not_found' => 'Entreprise introuvable.',
        'forbidden' => 'Vous n\'êtes pas autorisé à effectuer cette action.',
        'not_comment_author' => 'Seul l\'auteur peut modifier ce commentaire.',
        'not_company_member' => 'Cette information est réservée aux membres de l\'entreprise.',
        'cannot_repost_own' => 'Vous ne pouvez pas republier votre propre publication.',
        'cannot_follow_own_company' => 'Vous ne pouvez pas vous abonner à votre propre entreprise.',
        'user_not_found' => 'Utilisateur introuvable.',
        'cannot_follow_self' => 'Vous ne pouvez pas vous abonner à vous-même.',
    ],

    'validation' => [
        'body_required' => 'Le commentaire ne peut pas être vide.',
        'body_max' => 'Le commentaire ne peut pas dépasser :max caractères.',
        'parent_invalid' => 'Commentaire parent invalide.',
        'repost_comment_max' => 'Le texte de republication ne peut pas dépasser :max caractères.',
        'search_min' => 'La recherche doit contenir au moins 2 caractères.',
    ],

    'push' => [
        'like_job' => [
            'title' => 'Nouveau J\'aime',
            'body' => ':name aime votre offre « :title »',
        ],
        'like_service' => [
            'title' => 'Nouveau J\'aime',
            'body' => ':name aime votre service « :title »',
        ],
        'comment' => [
            'title' => 'Nouveau commentaire',
            'body' => ':name a commenté « :title » : :excerpt',
        ],
        'reply' => [
            'title' => 'Nouvelle réponse',
            'body' => ':name a répondu à votre commentaire',
        ],
        'repost' => [
            'title' => 'Nouvelle republication',
            'body' => ':name a republié « :title »',
        ],
        'follow' => [
            'title' => 'Nouvel abonné',
            'body' => ':name suit désormais :company',
        ],
        'user_follow' => [
            'title' => 'Nouvel abonné',
            'body' => ':name s\'est abonné à vous',
        ],
        'comment_like' => [
            'title' => 'Nouveau J\'aime',
            'body' => ':name aime votre commentaire',
        ],
    ],
];
