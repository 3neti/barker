<?php

namespace App\Actions\Hyperverge;


use Illuminate\Notifications\AnonymousNotifiable;
use App\Notifications\CampaignNotification;
use App\Events\Hyperverge\ResultProcessed;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Classes\CampaignCheckin;
use App\Classes\Barker;
use App\Models\Checkin;

class SendCampaignNotifications
{
    use AsAction;

    public function __construct(public Barker $barker){}

    public function handle(Checkin $checkin)
    {
        foreach ($checkin->campaign->campaignItems as $campaignItem) {
            (new AnonymousNotifiable)->notify(new CampaignNotification(new CampaignCheckin($checkin, $campaignItem)));
        }
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
