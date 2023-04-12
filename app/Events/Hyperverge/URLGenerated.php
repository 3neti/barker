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
//        $checkin = app(Checkin::class)->find($this->transactionId);

        return [
            new PrivateChannel('checkin.'. $this->transactionId),
//            new PrivateChannel('agent.'. $checkin->agent->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'url.generated';
    }
}
