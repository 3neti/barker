<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use JetBrains\PhpStorm\Pure;
use Illuminate\Support\Str;

class ContactCheckinMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $_subject;

    protected string $_body;

    protected string $_type;

    /**
     * Create a new message instance.
     */
    public function __construct(object $notifiable, array $payload = [])
    {
        $this->to($notifiable->routes['mail']);
        list('subject' => $this->_subject, 'body' => $this->_body, 'type' => $this->_type) = $payload;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('3neti@lyflyn.net', '3neti'),
            subject: $this->_subject,
        );
    }

    /**
     * Get the message content definition.
     */
    #[Pure]
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-checkin',
            with: [
                'type' => Str::title($this->_type),
                'message' => 'The quick brown fox jumps over the lazy dog.',
                'url' => $this->getURL(),
            ],

        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->_body, 'data.csv')
                ->withMime('text/csv'),
        ];
    }

    protected function getURL(): string
    {
        $data = str_getcsv($this->_body);

        return array_pop($data);
    }
}
