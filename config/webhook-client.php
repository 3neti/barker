<?php

return [
    'configs' => [
        [
            'name' => 'paynamics-paybiz',
            'signing_secret' => env('WEBHOOK_CLIENT_SECRET_FOR_PAYNAMICS_PAYBIZ'),
            'signature_header_name' => 'Secret',
            'signature_validator' => \App\Handlers\Paynamics\WebhookValidation::class,
            'webhook_profile' => \App\Handlers\Paynamics\WebhookAuthorization::class,
            'webhook_response' => \Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'store_headers' => [],
            'process_webhook_job' => \App\Handlers\Paynamics\WebhookHandler::class,
        ],
    ],
];
