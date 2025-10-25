<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Authentication Guard
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => 'web',   // admin backend default
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
    'guards' => [

        // Admin (backend login via sessions)
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // API Users (frontend login via JWT)
        'api' => [
            'driver' => 'jwt',
            'provider' => 'user_apis',
        ],

        // LDAP Users
        'ldap' => [
            'driver' => 'session',    // for session login
            'provider' => 'ldap_users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [

        // LDAP Users
        'ldap_users' => [
            'driver' => 'ldap',
            'model'  => LdapRecord\Models\OpenLDAP\User::class,
        ],

        // Local Eloquent Users (admin)
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        // API Users
        'user_apis' => [
            'driver' => 'eloquent',
            'model' => App\Models\UserApi::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */
    'password_timeout' => 10800,

];
