<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Checkin;

class CheckinAdded
{
    use Dispatchable;

    public function __construct(public Checkin $checkin){}
}
