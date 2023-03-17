<?php

namespace App\Events;

use Illuminate\Broadcasting\{Channel, InteractsWithSockets, PresenceChannel, PrivateChannel};
use Bavix\Wallet\Internal\Events\BalanceUpdatedEventInterface;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Bavix\Wallet\Models\Wallet;
use DateTimeImmutable;

final class BalanceUpdated implements BalanceUpdatedEventInterface, ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(private Wallet $wallet, private DateTimeImmutable $updatedAt){}
    public function getWalletId(): int { return $this->wallet->getKey(); }
    public function getWalletUuid(): string { return $this->wallet->uuid; }
    public function getBalance(): string { return $this->wallet->balanceInt; }
    public function getBalanceFloat(): string { return $this->wallet->balanceFloat; }
    public function getUpdatedAt(): DateTimeImmutable { return $this->updatedAt; }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('wallet.holder.'.$this->wallet->holder->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'balance.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'walletId' => $this->getWalletId(),
            'balanceFloat' => $this->getBalanceFloat(),
            'updatedAt' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
