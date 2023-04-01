<?php

namespace App\Actions\Hyperverge;

use App\Events\Hyperverge\ResultRetrieved;
use App\Pipes\HydrateCheckinPersonData;
use App\Pipes\HydrateCheckinPersonHandle;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Pipeline\Pipeline;
use App\Models\Checkin;

class ProcessResult
{
    use AsAction;

    public function __construct(protected Pipeline $pipeline) {}

    public function handle(Checkin $checkin): bool
    {
        $checkin = $this->pipeline
            ->send($checkin)
            ->through([
                HydrateCheckinPersonData::class,
                HydrateCheckinPersonHandle::class
            ])
            ->thenReturn();
        $checkin->person->save();

        return (null !== $checkin->person->data);
    }

    public function asListener(ResultRetrieved $event)
    {
        $this->handle($event->checkin);
    }
}
