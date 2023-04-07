<?php

namespace App\Notifications;

use LBHurtado\EngageSpark\EngageSparkMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Mail\ContactCheckinMail;
use App\Classes\CampaignCheckin;
use Illuminate\Bus\Queueable;

class CampaignNotification extends Notification
{
    use Queueable;

    public function __construct(public CampaignCheckin $campaignCheckin){}

    public function via(object $notifiable): array
    {
        $notifiable->route(...$this->campaignCheckin->channeledRoute()); //crux :-)

        return [$this->campaignCheckin->channel()];
    }

    public function toMail(object $notifiable): ContactCheckinMail
    {
        return (new ContactCheckinMail($notifiable, $this->campaignCheckin->payload()));
    }

    public function toEngageSpark(object $notifiable): EngageSparkMessage
    {
        $subject = $body = '';
        extract($this->campaignCheckin->payload());
        $message = __('barker.notification.checkin.campaign.sms',
            compact('subject', 'body')
        );

        return (new EngageSparkMessage())
            ->content($message);
    }
}
