<?php

namespace App\Actions\Webhook;

use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Laravel\Jetstream\Jetstream;
use App\Actions\TopupUserWallet;
use Illuminate\Support\Arr;

class UpdateUserWallet
{
    use AsAction;

    const DEFAULT_USER_WALLET = 'default';

    public function handle(array $attribs)
    {
        Validator::make($attribs, [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            'amount' => ['required', 'numeric', 'between:0,999999999.99'],
        ])->validate();

        $user = Jetstream::findUserByEmailOrFail(Arr::get($attribs, 'email'));

        TopupUserWallet::run($user, $attribs['amount'], self::DEFAULT_USER_WALLET, [
            'deposit' => ['to' => 'user credits'],
            'withdraw' => ['from' => 'Paynamics']
        ]);
    }
}
