<?php

namespace App\Classes;

use Bavix\Wallet\Internal\Assembler\BalanceUpdatedEventAssemblerInterface;
use Bavix\Wallet\Internal\Events\BalanceUpdatedEventInterface;
use Bavix\Wallet\Models\Wallet;
use App\Events\BalanceUpdated;
use DateTimeImmutable;

class BalanceUpdatedAssembler implements BalanceUpdatedEventAssemblerInterface
{

    public function create(Wallet $wallet): BalanceUpdatedEventInterface
    {
        return new BalanceUpdated($wallet, new DateTimeImmutable());
    }
}
