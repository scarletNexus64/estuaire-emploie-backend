<?php

return [

    'default' => env('QUEUE_CONNECTION', 'database'),

    'connections' => [

        'database' => [
            'driver' => 'database',
            'table' => 'job_queue', // <- ta table personnalisée
            'queue' => 'default',
            'retry_after' => 90,
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