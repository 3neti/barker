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
                'mobile' => ":campaign - accounted via mobile",
                'email' => ':campaign - accounted via email',
                'url' => ':campaign - accounted',
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
                'mobile' => "name: :name,\nbirthdate: :birthdate,\naddress: :address,\nreference: :reference",
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
    'missive' => [
        'content' => ":subject\r\n:body\r\n:signature",
        'components' => [
            'subject' => [
                'otp' => 'OTP Header',
                'instruction' => ':campaign',
                'rider' => 'Rider Header'
            ],
            'body' => [
                'otp' => "OTP Body",
                'instruction' => 'Click the ff: :url',
                'rider' => 'OTP Body'
            ],
            'signature' => [
                'otp' => "OTP signature",
                'instruction' => "- :app",
                'rider' => "Rider signature",
            ],
        ],
        'payload' => [
            'otp' => 'otp: :otp',
            'instruction' => ':reference',
            'rider' => "name: :name\r\nreference: :reference"
        ],
    ],
    'notification' => [
        'format' => [
            'header' => ":subject",
            'body' => "Fields extracted:\n\nname: :name,\nbirthdate: :birthdate,\naddress: :address",
            'footer' => "- :from",
            'sms' => ":header\r\n:body\r\n:footer",
        ],
        'checkin' => [
            'campaign' => [
                'sms' => ":subject\r\n:body",
            ]
        ],
    ],
];
