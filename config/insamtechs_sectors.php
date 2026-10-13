<?php

/**
 * Secteurs d'activité de la vidéothèque InsamTechs.
 *
 * L'API InsamTechs ne connaît que des catégories à plat (« comptabilite »,
 * « accounting », « mobile development »…) : on les regroupe ici en secteurs.
 * Une catégorie appartient au PREMIER secteur (dans l'ordre du fichier) dont
 * un mot-clé apparaît dans son intitulé (comparaison sans accents, en minuscules). Les catégories non
 * reconnues tombent dans « other ».
 *
 * Pour classer une nouvelle catégorie InsamTechs, il suffit d'ajouter un
 * mot-clé au bon secteur.
 */
return [
    'it' => [
        'label' => ['fr' => 'Informatique & numérique', 'en' => 'IT & digital'],
        'keywords' => [
            'informatique', 'computer', 'programm', 'programmation', 'web', 'mobile',
            'reseau', 'network', 'microsoft', 'intelligence artificielle',
            'artificial intelligence', 'business tools', 'monnaie virtuelle', 'crypto',
        ],
    ],
    'business' => [
        'label' => ['fr' => 'Gestion, finance & comptabilité', 'en' => 'Business, finance & accounting'],
        'keywords' => [
            'comptab', 'accounting', 'finance', 'gestion', 'management', 'ressource humaine',
            'human resource', 'logisti', 'transport',
        ],
    ],
    'marketing' => [
        'label' => ['fr' => 'Commerce & marketing', 'en' => 'Sales & marketing'],
        'keywords' => ['marketing', 'commerc', 'communication', 'vente', 'sales'],
    ],
    'engineering' => [
        'label' => ['fr' => 'Ingénierie & industrie', 'en' => 'Engineering & industry'],
        'keywords' => [
            'genie civil', 'civil eng', 'electro', 'electronic', 'automati', 'engen',
            'engineer', 'dessin technique', 'mathemat', 'science',
        ],
    ],
    'languages' => [
        'label' => ['fr' => 'Langues', 'en' => 'Languages'],
        'keywords' => ['anglais', 'english', 'francais', 'french', 'langue', 'language'],
    ],
    'personal' => [
        'label' => ['fr' => 'Développement personnel & éducation', 'en' => 'Personal development & education'],
        'keywords' => [
            'developpement personnel', 'personal development', 'soft skill', 'career',
            'carriere', 'pedagog', 'humanities', 'livre audio',
        ],
    ],
    'arts' => [
        'label' => ['fr' => 'Arts, design & médias', 'en' => 'Arts, design & media'],
        'keywords' => [
            'dessin', 'drawing', 'cinema', 'photo', 'infograph', 'music', 'dj ', 'audio',
            'design',
        ],
    ],
    'health' => [
        'label' => ['fr' => 'Santé, beauté & sport', 'en' => 'Health, beauty & sport'],
        'keywords' => ['health', 'sante', 'beaut', 'sport', 'body building', 'fitness'],
    ],
    'other' => [
        'label' => ['fr' => 'Autres', 'en' => 'Other'],
        'keywords' => [],
    ],
];
