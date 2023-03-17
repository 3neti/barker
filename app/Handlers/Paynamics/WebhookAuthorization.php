<?php

namespace App\Handlers\Paynamics;

use Laravel\Jetstream\Jetstream;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class WebhookAuthorization implements WebhookProfile
{
    public function shouldProcess(Request $request): bool
    {
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required',
            'customer_info.name' => ['required', 'string', 'max:255'],
            'customer_info.email' => ['required', 'email'],
            'customer_info.mobile' => ['required' , 'phone:PH'],
            'customer_info.amount' => ['required', 'numeric', 'between:10,100000'],
            'pay_reference' => 'required',
            'response_code' => 'required'
        ]);

        return $validator->passes();
    }
}
