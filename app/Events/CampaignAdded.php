<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\{Campaign, User};

class CampaignAdded
{
    use Dispatchable;

    public function __construct(
        public User $owner,
        public Campaign $campaign,
        public string $type,
        public ?array $channels = [],
        public ?array $missives = [],
        public ?array $profiles = [],
    ){}
}
