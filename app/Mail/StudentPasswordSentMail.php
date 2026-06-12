<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentPasswordSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $studentEmail;
    public $password;
    public $instituteName;
    public $instituteLogoPath;
    public $instituteLogoUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $studentName,
        $studentEmail,
        $password,
        $instituteName,
        $instituteLogoUrl = null
    ) {
        $this->studentName = $studentName;
        $this->studentEmail = $studentEmail;
        $this->password = $password;
        $this->instituteName = $instituteName;
        $this->instituteLogoPath = $instituteLogoUrl; // Raw path
        $this->instituteLogoUrl = $instituteLogoUrl ? asset('storage/' . $instituteLogoUrl) : null;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.support_address'), config('mail.from.name')),
            subject: 'Your Student Account Password - ' . $this->instituteName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.student_password_sent',
            with: [
                'studentName' => $this->studentName,
                'studentEmail' => $this->studentEmail,
                'password' => $this->password,
                'instituteName' => $this->instituteName,
                'instituteLogoUrl' => $this->instituteLogoUrl,
                'instituteLogoPath' => $this->instituteLogoPath,
            ],
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
