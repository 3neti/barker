<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\User;

class AddingCheckin
{
    use Dispatchable;

    public function __construct(public User $agent){}
}
