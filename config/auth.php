<?php

return [



    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],



    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'contratante' => [
            'driver' => 'session',
            'provider' => 'contratantes',
        ],
        'profissional' => [
            'driver' => 'session',
            'provider' => 'profissionais',
        ],
    ],


    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'contratantes' => [
            'driver' => 'eloquent',
            'model' => App\Models\Contratante::class,
        ],
        'profissionais' => [
            'driver' => 'eloquent',
            'model' => App\Models\Profissional::class,
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
