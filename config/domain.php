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
            'general' => [
                'name' => env('DOMAIN_DEFAULT_TEAM_NAME',  'General Membership'),
                'personal_team' =>  false,
            ],
            'standby' => [
                'name' => env('DOMAIN_STANDBY_TEAM_NAME',  'Standby'),
                'personal_team' =>  true,
                'switchable' => false,
            ],
        ],
        'wallet' => [
            'default' => env('DOMAIN_DEFAULT_WALLET_BALANCE', 10000000),
        ],
    ],
    'defaults' => [
        'user' => [
            'role' => env('DOMAIN_DEFAULT_USER_ROLE',  'agent'),
            'team' => [
                'name' =>  env('DOMAIN_DEFAULT_TEAM_NAME',  'Standby'),
            ],
        ],
//        'campaign' => [
//            'role' => env('DOMAIN_DEFAULT_CAMPAIGN_ROLE',  'agent'),
//        ],
    ],
    'hyperverge' => [
        'api' => [
            'id' => env('HYPERVERGE_API_ID'),
            'key' => env('HYPERVERGE_API_KEY'),
            'expiry' => env('HYPERVERGE_API_EXPIRY', 300),
            'url' => [
                'login' => env('HYPERVERGE_API_URL_LOGIN', 'https://auth.hyperverge.co/login'),
                'kyc' => env('HYPERVERGE_API_URL_KYC', 'https://ind.idv.hyperverge.co/v1/link-kyc/start'),
                'result' => env('HYPERVERGE_API_URL_RESULT', 'https://ind.idv.hyperverge.co/v1/link-kyc/results'),
            ],
        ],
        'url' => [
            'workflow' => env('HYPERVERGE_URL_WORKFLOW', 'default'),
        ],
        'mapping' => [
            'workflow_id' => env('HYPERVERGE_MAP_WORKFLOW_ID','result.workflowDetails.workflowId'),
            'application_status' => env('HYPERVERGE_MAP_APPLICATION_STATUS','result.applicationStatus'),
            'country' => env('HYPERVERGE_MAP_COUNTRY','result.results.0.countrySelected'),
            'id_image_url' => env('HYPERVERGE_MAP_ID_IMAGE_URL','result.results.0.imageUrl'),
            'id_type' => env('HYPERVERGE_MAP_ID_TYPE','result.results.0.apiResponse.result.details.0.idType'),
            'id_number' => env('HYPERVERGE_MAP_ID_NUMBER','result.results.0.apiResponse.result.details.0.fieldsExtracted.idNumber.value'),
            'id_expiry' => env('HYPERVERGE_MAP_ID_EXPIRY','result.results.0.apiResponse.result.details.0.fieldsExtracted.dateOfExpiry.value'),
            'id_full_name' => env('HYPERVERGE_MAP_ID_FULL_NAME','result.results.0.apiResponse.result.details.0.fieldsExtracted.fullName.value'),
            'id_birthdate' => env('HYPERVERGE_MAP_ID_BIRTH_DATE','result.results.0.apiResponse.result.details.0.fieldsExtracted.dateOfBirth.value'),
            'id_address' => env('HYPERVERGE_MAP_ID_ADDRESS','result.results.0.apiResponse.result.details.0.fieldsExtracted.address.value'),
            'id_gender' => env('HYPERVERGE_MAP_ID_GENDER','result.results.0.apiResponse.result.details.0.fieldsExtracted.gender.value'),
            'id_nationality' => env('HYPERVERGE_MAP_ID_NATIONALITY','result.results.0.apiResponse.result.details.0.fieldsExtracted.nationality.value'),
            'face_image_url' => env('HYPERVERGE_MAP_FACE_IMAGE_URL','result.results.1.imageUrl'),
            'face_check_status' => env('HYPERVERGE_MAP_FACE_CHECK_STATUS','result.results.1.apiResponse.result.summary.action'),
            'face_check_details' => env('HYPERVERGE_MAP_FACE_CHECK_DETAILS','result.results.1.apiResponse.result.details'),
            'face_id_match_status' => env('HYPERVERGE_MAP_FACE_ID_MATCH_STATUS','result.results.2.apiResponse.result.summary.action'),
            'face_id_match_details' => env('HYPERVERGE_MAP_FACE_ID_MATCH_DETAILS','result.results.2.apiResponse.result.details'),
        ],
    ],
    'dictionary' => [
        'id_type' => [
            'phl_dl' => 'Philippine Driver License',
            'passport' => 'Passport'
        ],
    ],
];
