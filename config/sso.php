<?php
return [
    'adfs_login_url' => env('ADFS_LOGIN_URL', 'https://adfs.company.local/adfs/ls/'),
    'adfs_jwks_url'  => env('ADFS_JWKS_URL', 'https://adfs.company.local/federationmetadata/2007-06/federationmetadata.xml'),
    'client_id'      => env('ADFS_CLIENT_ID'),
    'client_secret'  => env('ADFS_CLIENT_SECRET'),
    'redirect_uri'   => env('ADFS_REDIRECT_URI'),
];
