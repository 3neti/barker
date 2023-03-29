<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\{CampaignItem, Contact};
use Illuminate\Bus\Queueable;

class ContactCheckin extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public CampaignItem $campaignItem, public Contact $contact){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('http://localhost/dashboard');
        $type = 'accounting';
        $data = __($this->campaignItem->template,[
            'name' => $this->contact->name,
            'birthdate' => $this->contact->birthdate,
            'address' => $this->contact->address,
            'reference' => $url
        ]);

        return (new MailMessage)
            ->subject('Contact Checked In')
            ->markdown('mail.contact.checkin', compact('url', 'type'))
            ->attachData($data, 'data.csv', ['mime' => 'text/csv']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
