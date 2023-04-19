<?php

namespace App\Notifications;

use LBHurtado\EngageSpark\EngageSparkMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Arr;
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
        $header = $this->getDefaultHeader();
        $body = $this->getDefaultBody();
        $footer = $this->getDefaultFooter();
        $message = __('barker.notification.format.sms',
            compact('header', 'body', 'footer')
        );

        return (new EngageSparkMessage())
            ->content($message);
    }

    protected function getDefaultHeader(): string
    {
        return __('barker.notification.format.header', [
            'subject' => $this->checkin->campaign->name,
        ]);
    }

    protected function getDefaultBody(): string
    {
        $arr = Arr::only($this->checkin->person->toArray(), $this->checkin->person->getAppends());

        return __('barker.notification.format.body', $arr);
    }

    protected function getDefaultFooter(): string
    {
        return __('barker.notification.format.footer', [
            'from' => config('app.name')
        ]);
    }
}
