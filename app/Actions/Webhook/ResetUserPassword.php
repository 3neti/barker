<?php

namespace App\Actions\Webhook;

use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Arr;

class ResetUserPassword
{
    use AsAction;

    public function handle(array $attribs): void
    {
        Validator::make($attribs, [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
        ])->validate();

        tap(Password::broker(config('fortify.passwords')), function ($broker) use ($attribs) {
            $broker->sendResetLink(Arr::only($attribs, 'email'));
        });
    }

    public function asJob(array $attribs): void
    {
        $this->handle($attribs);
    }
}
