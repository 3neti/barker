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
            new PrivateChannel('agent.'. $this->checkin->agent->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'result.processed';
    }
}
