<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Bavix\Wallet\External\Dto\Option;
use Bavix\Wallet\External\Dto\Extra;
use App\Models\User;

class TopupUserWallet
{
    use AsAction;

    public function handle(User $destination, float $amount, $slug = 'default')
    {
        tap(app(User::class)->system()->getWallet($slug), function ($system_wallet) use ($destination, $amount, $slug) {
            $system_wallet->transferFloat(
                $destination->getWallet($slug),
                $amount,
                new Extra (
                    deposit: ['message' => 'Hello, secondWallet!'],
                    withdraw: new Option (
                        meta: ['something' => 'anything'],
                        confirmed: true
                    )
                ));
        });
    }
}
