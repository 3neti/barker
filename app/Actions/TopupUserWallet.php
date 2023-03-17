<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Bavix\Wallet\External\Dto\Option;
use Bavix\Wallet\External\Dto\Extra;
use Illuminate\Support\Arr;
use App\Models\User;

class TopupUserWallet
{
    use AsAction;

    public function handle(User $destination, float $amount, string $slug = 'default', $meta = ['transaction' => 'topup'])
    {
        tap(app(User::class)->system()->getWallet($slug), function ($system_wallet) use ($destination, $amount, $slug, $meta) {
            $system_wallet->transferFloat(
                $destination->getWallet($slug),
                $amount,
                new Extra (
                    deposit: new Option (
                        meta: Arr::get($meta, 'deposit') ?? $meta,
                        confirmed: true
                    ),
                    withdraw: new Option (
                        meta: Arr::get($meta, 'withdraw') ?? $meta,
                        confirmed: true
                    )
                ));
        });
    }

    protected function asJob(User $destination, float $amount, string $slug = 'default', $meta = ['transaction' => 'topup'])
    {
        $this->handle($destination, $amount, $slug, $meta);
    }
}
