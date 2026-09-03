<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowAttendanceAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $instituteName;
    public $instituteLogoUrl;
    public $instituteLogoPath;
    public $attendancePercentage;
    public $windowDays;
    public $threshold;
    public $profileUrl;

    public function __construct(
        string $studentName,
        string $instituteName,
        ?string $instituteLogoPath,
        float $attendancePercentage,
        int $windowDays,
        float $threshold,
        string $profileUrl
    ) {
        $this->studentName = $studentName;
        $this->instituteName = $instituteName;
        $this->instituteLogoPath = $instituteLogoPath;
        $this->instituteLogoUrl = $instituteLogoPath ? asset('storage/' . $instituteLogoPath) : null;
        $this->attendancePercentage = $attendancePercentage;
        $this->windowDays = $windowDays;
        $this->threshold = $threshold;
        $this->profileUrl = $profileUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Attendance Alert: {$this->studentName} - {$this->instituteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.low_attendance_alert',
            with: [
                'studentName' => $this->studentName,
                'instituteName' => $this->instituteName,
                'instituteLogoUrl' => $this->instituteLogoUrl,
                'instituteLogoPath' => $this->instituteLogoPath,
                'attendancePercentage' => $this->attendancePercentage,
                'windowDays' => $this->windowDays,
                'threshold' => $this->threshold,
                'profileUrl' => $this->profileUrl,
            ],
        );
    }
}
