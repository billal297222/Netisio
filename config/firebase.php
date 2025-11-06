<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | Path to your Firebase service account JSON file.
    |
    */
    'credentials' => [
        'file' => env('FIREBASE_CREDENTIALS', storage_path('firebase/netisio-firebase-adminsdk-fbsvc-3c1792f39d.json')),
    ],
];
