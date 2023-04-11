<?php

namespace App\Notifications;

use LBHurtado\EngageSpark\EngageSparkMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use App\Models\Checkin;

class CheckinNotification extends Notification
{
    use Queueable;

    public function __construct(public Checkin $checkin){}

    public function via(object $notifiable): array
    {
        return ['engage_spark'];
    }

    public function toEngageSpark(object $notifiable): EngageSparkMessage
    {
        $subject = $this->checkin->campaign->name;
        $body = 'The quick brown fox';
        $message = __('barker.notification.checkin.campaign.sms',
            compact('subject', 'body')
        );

        return (new EngageSparkMessage())
            ->content($message);
    }
}
