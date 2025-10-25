<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',    // Vite React dev server
        
        'https://e-medo.vercel.app',

        'https://thebookingnest.com',
        'https://the-booking-nest.netlify.app',
        'https://bachelorgirl.softvencefsd.xyz',  // Your live frontend domain
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
