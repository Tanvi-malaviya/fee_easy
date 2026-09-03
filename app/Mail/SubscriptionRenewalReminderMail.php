<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionRenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $instituteName;
    public $planName;
    public $endDate;
    public $daysRemaining;
    public $renewUrl;

    public function __construct(string $instituteName, string $planName, string $endDate, int $daysRemaining, string $renewUrl)
    {
        $this->instituteName = $instituteName;
        $this->planName = $planName;
        $this->endDate = $endDate;
        $this->daysRemaining = $daysRemaining;
        $this->renewUrl = $renewUrl;
    }

    public function envelope(): Envelope
    {
        $subject = $this->daysRemaining === 0
            ? "Your Plan Expires Today - Renew Now - Tuoora"
            : "Your Plan Expires in {$this->daysRemaining} Day" . ($this->daysRemaining === 1 ? '' : 's') . " - Tuoora";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription_renewal_reminder',
            with: [
                'instituteName' => $this->instituteName,
                'planName' => $this->planName,
                'endDate' => $this->endDate,
                'daysRemaining' => $this->daysRemaining,
                'renewUrl' => $this->renewUrl,
            ],
        );
    }
}
