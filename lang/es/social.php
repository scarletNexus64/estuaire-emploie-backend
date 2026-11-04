<?php

return [
    'deleted_user' => 'Usuario eliminado',

    'errors' => [
        'not_found' => 'Recurso no encontrado.',
        'item_not_found' => 'Esta publicación no existe o ya no está disponible.',
        'comment_not_found' => 'Este comentario no existe o ha sido eliminado.',
        'parent_not_found' => 'El comentario al que responde ya no existe.',
        'company_not_found' => 'Empresa no encontrada.',
        'forbidden' => 'No está autorizado a realizar esta acción.',
        'not_comment_author' => 'Solo el autor puede modificar este comentario.',
        'not_company_member' => 'Esta información está reservada a los miembros de la empresa.',
        'cannot_repost_own' => 'No puede volver a publicar su propia publicación.',
        'cannot_follow_own_company' => 'No puede seguir a su propia empresa.',
        'user_not_found' => 'Usuario no encontrado.',
        'cannot_follow_self' => 'No puedes seguirte a ti mismo.',
    ],

    'validation' => [
        'body_required' => 'El comentario no puede estar vacío.',
        'body_max' => 'El comentario no puede superar :max caracteres.',
        'parent_invalid' => 'Comentario principal no válido.',
        'repost_comment_max' => 'El texto de la republicación no puede superar :max caracteres.',
        'search_min' => 'La búsqueda debe contener al menos 2 caracteres.',
    ],

    'push' => [
        'like_job' => [
            'title' => 'Nuevo Me gusta',
            'body' => 'A :name le gusta su oferta «:title»',
        ],
        'like_service' => [
            'title' => 'Nuevo Me gusta',
            'body' => 'A :name le gusta su servicio «:title»',
        ],
        'comment' => [
            'title' => 'Nuevo comentario',
            'body' => ':name comentó «:title»: :excerpt',
        ],
        'reply' => [
            'title' => 'Nueva respuesta',
            'body' => ':name respondió a su comentario',
        ],
        'repost' => [
            'title' => 'Nueva republicación',
            'body' => ':name volvió a publicar «:title»',
        ],
        'follow' => [
            'title' => 'Nuevo seguidor',
            'body' => ':name ahora sigue a :company',
        ],
        'user_follow' => [
            'title' => 'Nuevo seguidor',
            'body' => ':name comenzó a seguirte',
        ],
        'comment_like' => [
            'title' => 'Nuevo Me gusta',
            'body' => 'A :name le gusta tu comentario',
        ],
    ],
];
