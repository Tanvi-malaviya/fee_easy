<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $institute;
    public $pdfContent;
    public $fileName;

    /**
     * Create a new message instance.
     */
    public function __construct($student, $institute, $pdfContent, $fileName = null)
    {
        $this->student = $student;
        $this->institute = $institute;
        $this->pdfContent = $pdfContent;
        $this->fileName = $fileName ?: 'Student_Report_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $student->name) . '.pdf';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $fromName = $this->institute->institute_name ?? $this->institute->name ?? config('mail.from.name', 'Tuoora');
        $fromAddress = ($this->institute && $this->institute->hasCustomSmtp())
            ? ($this->institute->mail_from_address ?: ($this->institute->email ?: config('mail.from.address')))
            : config('mail.from.address');

        return $this->from($fromAddress, $fromName)
            ->subject('Student Academic & Comprehensive Performance Report - ' . $fromName)
            ->view('emails.student_report', [
                'studentName' => $this->student->name,
                'studentEmail' => $this->student->email,
                'enrollmentId' => $this->student->enrollment_id ?? '#ST-' . $this->student->id,
                'instituteName' => $this->institute->institute_name ?? $this->institute->name,
                'instituteLogoPath' => $this->institute->logo,
                'instituteLogoUrl' => $this->institute->logo ? asset('storage/' . $this->institute->logo) : null,
                'generatedAt' => now()->format('M d, Y h:i A'),
            ])
            ->attachData($this->pdfContent, $this->fileName, [
                'mime' => 'application/pdf',
            ]);
    }
}
