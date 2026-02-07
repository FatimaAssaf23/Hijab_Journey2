<?php

namespace App\Mail;

use App\Models\TeacherRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewTeacherRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public TeacherRequest $teacherRequest;

    /**
     * Create a new message instance.
     */
    public function __construct(TeacherRequest $teacherRequest)
    {
        $this->teacherRequest = $teacherRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '👨‍🏫 New Teacher Application Request - ' . config('app.name', 'Hijab Journey'),
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
            view: 'emails.new-teacher-request',
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
