<?php

namespace App\Actions\Hyperverge;

use App\Events\Hyperverge\ResultProcessed;
use App\Notifications\CheckinNotification;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Checkin;
use App\Classes\Barker;

class SendCheckinNotification
{
    use AsAction;

    public function __construct(public Barker $barker){}

    public function handle(Checkin $checkin)
    {
        $checkin->person->notify(new CheckinNotification($checkin));
    }

    public function asJob(Checkin $checkin)
    {
        $this->handle($checkin);
    }

    public function asListener(ResultProcessed $event)
    {
        self::dispatch($event->checkin);
    }
}
