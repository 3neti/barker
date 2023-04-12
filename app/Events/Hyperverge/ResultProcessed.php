<?php

namespace App\Events\Hyperverge;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;
use JetBrains\PhpStorm\ArrayShape;
use App\Models\Checkin;

class ResultProcessed implements ShouldBroadcast
{
    use Dispatchable;

    public function __construct(public Checkin $checkin){}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('checkin.'. $this->checkin->uuid),
        ];
    }

    public function broadcastAs(): string
    {
        return 'result.processed';
    }

    #[ArrayShape(['transactionId' => "mixed"])]
    public function broadcastWith(): array
    {
        return [
            'transactionId' => $this->checkin->uuid,
        ];
    }
}
