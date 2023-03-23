<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\User;

class AddingCampaign
{
    use Dispatchable;

    public function __construct(public User $owner){}
}
