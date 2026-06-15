<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $appointment;
    public $trackingUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, string $trackingUrl)
    {
        $this->appointment = $appointment;
        $this->trackingUrl = $trackingUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Jadwal Janji Temu B-ONE',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment_created',
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
