<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $name;
    public $doctorName;
    public $time;
    public $address;
    public $city;
    public $state;
    public $provider_name;
    public $type;

    public function __construct($name, $doctorName, $time, $address, $city, $state, $provider_name, $type)
    {
        $this->name = $name;
        $this->doctorName = $doctorName;
        $this->time = $time;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->provider_name = $provider_name;
        $this->type = $type;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
