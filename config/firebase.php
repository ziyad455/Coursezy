<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | Firebase service account credentials for authentication
    |
    */

    'projects' => [
        'default' => [
            'credentials' => env('FIREBASE_CREDENTIALS', base_path('firebase-credentials.json')),
            'database_url' => env('FIREBASE_DATABASE_URL'),
            'storage' => [
                'default_bucket' => env('FIREBASE_STORAGE_BUCKET'),
            ],
        ],
    ],
];