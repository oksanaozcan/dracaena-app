<?php

return [

    'passport' => [
        'private_key' => storage_path('oauth-private.key'),
        'public_key' => storage_path('oauth-public.key'),
        // Other configuration options...
    ],

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],


    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'passport',
            'provider' => 'customers',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],
    ],


    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
