<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $staffName;
    public $staffEmail;
    public $employeeId;
    public $roleName;
    public $departmentName;
    public $instituteName;
    public $instituteLogoUrl;
    public $instituteLogoPath;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $staffName,
        $staffEmail,
        $employeeId,
        $roleName,
        $departmentName,
        $instituteName,
        $instituteLogoUrl = null
    ) {
        $this->staffName = $staffName;
        $this->staffEmail = $staffEmail;
        $this->employeeId = $employeeId;
        $this->roleName = $roleName ?: 'Staff';
        $this->departmentName = $departmentName;
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
            subject: "Welcome to {$this->instituteName} - Staff Profile Created",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.staff_added',
            with: [
                'staffName' => $this->staffName,
                'staffEmail' => $this->staffEmail,
                'employeeId' => $this->employeeId,
                'roleName' => $this->roleName,
                'departmentName' => $this->departmentName,
                'instituteName' => $this->instituteName,
                'instituteLogoUrl' => $this->instituteLogoUrl,
                'instituteLogoPath' => $this->instituteLogoPath,
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
