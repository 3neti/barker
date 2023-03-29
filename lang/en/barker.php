<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Barker Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during checkin for various
    | messages that we need to display to the user.
    |
    */
    'checkin' => [
        'header' => [
            'accounting' => [
                'mobile' => 'Accounted via mobile',
                'email' => 'Accounted via email',
                'url' => 'accounted',
            ],
            'authentication' => [
                'mobile' => 'Authenticated via mobile',
                'email' => 'Authenticated via email',
                'url' => 'authenticated',
            ],
            'authorization' => [
                'mobile' => 'Authorized via mobile',
                'email' => 'Authorized via email',
                'url' => 'authorized',
            ],
        ],
        'body' => [
            'accounting' => [
                'mobile' => 'name: :name, birthdate: :birthdate, address: :address, reference: :reference',
                'email' => '":name", ":birthdate", ":address", ":reference"',
                'url' => '{name: ":name", birthdate: ":birthdate", address: ":address", reference: ":reference"}',
            ],
            'authentication' => [
                'mobile' => 'name: :name, birthdate: :birthdate, address: :address, reference: :reference',
                'email' => '":name", ":birthdate", ":address", ":reference"',
                'url' => '{name: ":name", birthdate: ":birthdate", address: ":address", reference: ":reference"}',
            ],
            'authorization' => [
                'mobile' => 'name: :name, birthdate: :birthdate, address: :address, reference: :reference',
                'email' => '":name", ":birthdate", ":address", ":reference"',
                'url' => '{name: ":name", birthdate: ":birthdate", address: ":address", reference: ":reference"}',
            ],
        ],

    ],
];
