<?php

namespace App\Mail;

use App\Models\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QueueCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $queueTicket;
    public $trackingUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Queue $queue, string $trackingUrl)
    {
        $this->queueTicket = $queue;
        $this->trackingUrl = $trackingUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tiket Antrean B-ONE Anda: ' . $this->queueTicket->queue_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.queue_created',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
