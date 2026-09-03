<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeeReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $instituteName;
    public $instituteLogoUrl;
    public $instituteLogoPath;
    public $dueAmount;
    public $dueDate;
    public $stage; // 'upcoming' | 'due_today' | 'overdue'
    public $daysOverdue;
    public $paymentUrl;

    public function __construct(
        string $studentName,
        string $instituteName,
        ?string $instituteLogoPath,
        float $dueAmount,
        string $dueDate,
        string $stage,
        int $daysOverdue,
        string $paymentUrl
    ) {
        $this->studentName = $studentName;
        $this->instituteName = $instituteName;
        $this->instituteLogoPath = $instituteLogoPath;
        $this->instituteLogoUrl = $instituteLogoPath ? asset('storage/' . $instituteLogoPath) : null;
        $this->dueAmount = $dueAmount;
        $this->dueDate = $dueDate;
        $this->stage = $stage;
        $this->daysOverdue = $daysOverdue;
        $this->paymentUrl = $paymentUrl;
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'upcoming' => "Fee Due Soon - {$this->instituteName}",
            'due_today' => "Fee Due Today - {$this->instituteName}",
            'overdue' => "Fee Overdue - {$this->instituteName}",
        ];

        return new Envelope(
            subject: $subjects[$this->stage] ?? "Fee Payment Reminder - {$this->instituteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.fee_reminder',
            with: [
                'studentName' => $this->studentName,
                'instituteName' => $this->instituteName,
                'instituteLogoUrl' => $this->instituteLogoUrl,
                'instituteLogoPath' => $this->instituteLogoPath,
                'dueAmount' => $this->dueAmount,
                'dueDate' => $this->dueDate,
                'stage' => $this->stage,
                'daysOverdue' => $this->daysOverdue,
                'paymentUrl' => $this->paymentUrl,
            ],
        );
    }
}
