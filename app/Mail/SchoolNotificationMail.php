<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SchoolNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $title,
        public string $content,   // ← sudah di-parse variabelnya oleh NotificationService
        public array  $variables = [],
        public string $eventType  = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->title);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.school-notification',  // ← resources/views/emails/school-notification.blade.php
            with: [
                'title'     => $this->title,
                'content'   => $this->content,
                'variables' => $this->variables,
                'eventType' => $this->eventType,
            ],
        );
    }
}