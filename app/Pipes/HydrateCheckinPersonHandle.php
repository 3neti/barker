<?php

namespace App\Pipes;

use Illuminate\Support\Str;
use App\Models\Checkin;
use Closure;

class HydrateCheckinPersonHandle
{
    public function handle(Checkin $checkin, Closure $next)
    {
        if (!empty($checkin->data)) {
            $checkin->person->setAttribute('handle', $checkin->data->getName());
        }

        return $next($checkin);
    }
}
