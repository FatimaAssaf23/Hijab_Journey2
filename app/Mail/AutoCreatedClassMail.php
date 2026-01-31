<?php

namespace App\Mail;

use App\Models\StudentClass;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AutoCreatedClassMail extends Mailable
{
    use Queueable, SerializesModels;

    public StudentClass $class;

    /**
     * Create a new message instance.
     */
    public function __construct(StudentClass $class)
    {
        $this->class = $class;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📚 New Class Automatically Created - ' . $this->class->class_name,
            from: new \Illuminate\Mail\Mailables\Address(
                config('mail.from.address'),
                config('mail.from.name', 'Hijab Journey')
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.auto-created-class',
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
