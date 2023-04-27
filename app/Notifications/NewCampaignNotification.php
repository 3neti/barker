<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use LBHurtado\EngageSpark\EngageSparkMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use App\Models\Campaign;

class NewCampaignNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Campaign $campaign)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['engage_spark', 'mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->subject(__('New Campaign: :campaign', ['campaign' => $this->campaign->name]))
                    ->line('The quick brown fox...')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'campaign' => $this->campaign
        ];
    }

    public function toEngageSpark(object $notifiable): EngageSparkMessage
    {
        $header = 'New Campaign';
        $body = $this->campaign->name;
        $footer = config('app.name');
        $message = __('barker.notification.format.sms',
            compact('header', 'body', 'footer')
        );

        return (new EngageSparkMessage())
            ->content($message);
    }
}
