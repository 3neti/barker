<?php

namespace App\Events\Hyperverge;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;
use App\Models\Checkin;

class URLGenerated implements ShouldBroadcast
{
    use Dispatchable;

    public function __construct(public string $transactionId, public string $url){}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('checkin.'. $this->transactionId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'url.generated';
    }
}
