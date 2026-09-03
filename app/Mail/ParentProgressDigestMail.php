<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParentProgressDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $instituteName;
    public $instituteLogoUrl;
    public $instituteLogoPath;
    public $periodLabel;
    public $attendancePercentage;
    public $homeworkCompletion;
    public $upcomingExams;
    public $feeStatus;
    public $feeBalance;
    public $profileUrl;

    public function __construct(
        string $studentName,
        string $instituteName,
        ?string $instituteLogoPath,
        string $periodLabel,
        float $attendancePercentage,
        array $homeworkCompletion,
        array $upcomingExams,
        string $feeStatus,
        float $feeBalance,
        string $profileUrl
    ) {
        $this->studentName = $studentName;
        $this->instituteName = $instituteName;
        $this->instituteLogoPath = $instituteLogoPath;
        $this->instituteLogoUrl = $instituteLogoPath ? asset('storage/' . $instituteLogoPath) : null;
        $this->periodLabel = $periodLabel;
        $this->attendancePercentage = $attendancePercentage;
        $this->homeworkCompletion = $homeworkCompletion;
        $this->upcomingExams = $upcomingExams;
        $this->feeStatus = $feeStatus;
        $this->feeBalance = $feeBalance;
        $this->profileUrl = $profileUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->studentName}'s Weekly Progress - {$this->instituteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.parent_progress_digest',
            with: [
                'studentName' => $this->studentName,
                'instituteName' => $this->instituteName,
                'instituteLogoUrl' => $this->instituteLogoUrl,
                'instituteLogoPath' => $this->instituteLogoPath,
                'periodLabel' => $this->periodLabel,
                'attendancePercentage' => $this->attendancePercentage,
                'homeworkCompletion' => $this->homeworkCompletion,
                'upcomingExams' => $this->upcomingExams,
                'feeStatus' => $this->feeStatus,
                'feeBalance' => $this->feeBalance,
                'profileUrl' => $this->profileUrl,
            ],
        );
    }
}
