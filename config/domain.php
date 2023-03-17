<?php

use Laravel\Fortify\Rules\Password;
use Laravel\Jetstream\Jetstream;

return [
    'seeds' => [
        'user' => [
            'system' => [
                'name' => env('DOMAIN_SYSTEM_USER_NAME',  'Lester B. Hurtado'),
                'email' => env('DOMAIN_SYSTEM_USER_EMAIL',  '3neti@lyflyn.net'),
                'mobile' => env('DOMAIN_SYSTEM_USER_MOBILE', '+639173011987'),
                'password' => env('DOMAIN_SYSTEM_USER_PASSWORD', '#Password1'),
                'role' => env('DOMAIN_SYSTEM_USER_ROLE', 'admin'),
            ],
        ],
        'teams' => [
            'default' => env('DOMAIN_DEFAULT_TEAM_NAME',  'General Membership'),
            'standby' => env('DOMAIN_STANDBY_TEAM_NAME',  'Standby'),
        ],
        'wallet' => [
            'default' => env('DOMAIN_DEFAULT_WALLET_BALANCE', 10000000),
        ],
    ],
];
