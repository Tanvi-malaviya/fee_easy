<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $studentEmail;
    public $tempPassword;
    public $instituteName;
    public $instituteLogoUrl;
    public $instituteLogoPath;
    public $studentAppUrl;
    public $playstoreUrl;
    public $appstoreUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $studentName,
        $studentEmail,
        $tempPassword,
        $instituteName,
        $instituteLogoUrl = null,
        $studentAppUrl = '#',
        $playstoreUrl = '#',
        $appstoreUrl = '#'
    ) {
        $this->studentName = $studentName;
        $this->studentEmail = $studentEmail;
        $this->tempPassword = $tempPassword;
        $this->instituteName = $instituteName;
        $this->instituteLogoPath = $instituteLogoUrl; // Raw path
        $this->instituteLogoUrl = $instituteLogoUrl ? asset('storage/' . $instituteLogoUrl) : null;
        $this->studentAppUrl = $studentAppUrl ?: '#';
        $this->playstoreUrl = $playstoreUrl ?: '#';
        $this->appstoreUrl = $appstoreUrl ?: '#';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Student Account Credentials - Tuoora',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.student_added',
            with: [
                'studentName' => $this->studentName,
                'studentEmail' => $this->studentEmail,
                'tempPassword' => $this->tempPassword,
                'instituteName' => $this->instituteName,
                'instituteLogoUrl' => $this->instituteLogoUrl,
                'instituteLogoPath' => $this->instituteLogoPath,
                'studentAppUrl' => $this->studentAppUrl,
                'playstoreUrl' => $this->playstoreUrl,
                'appstoreUrl' => $this->appstoreUrl,
            ],
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
