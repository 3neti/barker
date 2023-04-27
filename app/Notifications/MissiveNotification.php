<?php

namespace App\Notifications;

use LBHurtado\EngageSpark\EngageSparkMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;
use App\Models\CampaignMissive;
use Illuminate\Bus\Queueable;
use App\Models\Checkin;

class MissiveNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Checkin $checkin, public CampaignMissive $campaignMissive){}

    public function via(object $notifiable): array
    {
        return ['engage_spark', 'database'];
    }

    #[ArrayShape(['route' => "mixed", 'message' => "string"])]
    public function toArray(object $notifiable): array
    {
        return [
            'route' => $notifiable->routeNotificationFor('engage_spark'),
            'message' => $this->toEngageSpark($notifiable)->content
        ];
    }

    public function toEngageSpark(object $notifiable): EngageSparkMessage
    {
        return (new EngageSparkMessage())
            ->content($this->getMessage());
    }

    protected function getMessage(): string
    {
        return __($this->campaignMissive->template, [
            'app' => config('app.name'),
            'campaign' => $this->checkin->campaign->name,
            'otp' => 'ABC123',//TODO: figure this out
            'url' => $this->checkin->url,
        ]);
    }
}
