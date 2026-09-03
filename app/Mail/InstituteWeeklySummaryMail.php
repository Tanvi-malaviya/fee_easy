<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstituteWeeklySummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $instituteName;
    public $periodLabel;
    public $feesCollected;
    public $feesPending;
    public $attendancePercentage;
    public $attendanceTrendDelta;
    public $upcomingExamCount;
    public $birthdaysThisWeek;
    public $lowAttendanceCount;
    public $newLeadsCount;
    public $dashboardUrl;

    public function __construct(
        string $instituteName,
        string $periodLabel,
        float $feesCollected,
        float $feesPending,
        float $attendancePercentage,
        float $attendanceTrendDelta,
        int $upcomingExamCount,
        array $birthdaysThisWeek,
        int $lowAttendanceCount,
        int $newLeadsCount,
        string $dashboardUrl
    ) {
        $this->instituteName = $instituteName;
        $this->periodLabel = $periodLabel;
        $this->feesCollected = $feesCollected;
        $this->feesPending = $feesPending;
        $this->attendancePercentage = $attendancePercentage;
        $this->attendanceTrendDelta = $attendanceTrendDelta;
        $this->upcomingExamCount = $upcomingExamCount;
        $this->birthdaysThisWeek = $birthdaysThisWeek;
        $this->lowAttendanceCount = $lowAttendanceCount;
        $this->newLeadsCount = $newLeadsCount;
        $this->dashboardUrl = $dashboardUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your Weekly Summary - {$this->instituteName} - Tuoora",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.institute_weekly_summary',
            with: [
                'instituteName' => $this->instituteName,
                'periodLabel' => $this->periodLabel,
                'feesCollected' => $this->feesCollected,
                'feesPending' => $this->feesPending,
                'attendancePercentage' => $this->attendancePercentage,
                'attendanceTrendDelta' => $this->attendanceTrendDelta,
                'upcomingExamCount' => $this->upcomingExamCount,
                'birthdaysThisWeek' => $this->birthdaysThisWeek,
                'lowAttendanceCount' => $this->lowAttendanceCount,
                'newLeadsCount' => $this->newLeadsCount,
                'dashboardUrl' => $this->dashboardUrl,
            ],
        );
    }
}
