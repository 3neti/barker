<?php

namespace App\Events\Hyperverge;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Checkin;

class ResultRetrieved
{
    use Dispatchable;

    public function __construct(public Checkin $checkin){}
}
