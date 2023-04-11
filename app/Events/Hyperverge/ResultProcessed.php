<?php

namespace App\Events\Hyperverge;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;
use App\Models\Checkin;

class ResultProcessed
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
}
