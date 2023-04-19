<?php

namespace App\Actions\Hyperverge;

use App\Notifications\MissiveNotification;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Events\Hyperverge\URLGenerated;
use App\Models\Checkin;

class SendCampaignMissives
{
    use AsAction;

    public function handle(Checkin $checkin)
    {
        foreach ($checkin->campaign->campaignMissives as $campaignMissive) {
            switch ($campaignMissive->missive) {
                case 'instruction':
                    $checkin->person->notify(new MissiveNotification($checkin, $campaignMissive));
                    break;
            }
        }
    }

    public function asListener(URLGenerated $event)
    {
        $checkin = Checkin::find($event->transactionId);
        $this->handle($checkin);
    }
}
