<?php

return [

    'default' => env('QUEUE_CONNECTION', 'redis'),

    'connections' => [

        'database' => [
            'driver' => 'database',
            'table' => 'job_queue', // <- ta table personnalisée
            'queue' => 'default',
            'retry_after' => 90,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_QUEUE_CONNECTION', 'default'),
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
            'after_commit' => false,
        ],

        'notifications' => [
            'driver' => 'redis',
            'connection' => env('REDIS_QUEUE_CONNECTION', 'default'),
            'queue' => 'notifications', // Queue séparée pour les notifications d'emploi
            'retry_after' => 300,
            'block_for' => null,
            'after_commit' => false,
        ],

        'sync' => [
            'driver' => 'sync',
        ],

        // tu peux ajouter d'autres drivers si besoin
    ],

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

];