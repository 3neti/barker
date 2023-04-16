<?php

namespace App\Pipes;

use App\Models\Checkin;
use Closure;

class HydrateCheckinPersonData
{
    public function handle(Checkin $checkin, Closure $next)
    {
        if (!empty($checkin->data)) {
            $checkin->person->setData(
                idType: $checkin->data->getIdType(),
                fieldsExtracted: $checkin->data->getFieldsExtracted()
            );
        }

        return $next($checkin);
    }
}
