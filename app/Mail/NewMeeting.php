<?php

namespace App\Mail;

use App\Models\Meeting;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewMeeting extends Mailable
{
    public function __construct(public Meeting $meeting) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->meeting->email, $this->meeting->name)],
            subject: 'Yeni görüşme: '.$this->meeting->starts_at->setTimezone('Europe/Istanbul')->locale('tr')->translatedFormat('j F l, H:i').' · '.$this->meeting->name,
        );
    }

    public function content(): Content
    {
        return new Content(text: 'mail.meeting');
    }
}
