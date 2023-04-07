<?php

namespace App\Events\Hyperverge;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Checkin;

class ResultProcessed
{
    use Dispatchable;

    public function __construct(public Checkin $checkin){}
}
