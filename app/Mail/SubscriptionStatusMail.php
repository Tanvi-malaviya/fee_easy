<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $instituteName;
    public $planName;
    public $endDate;
    public $amount;
    public $type; // 'assigned', 'extended', 'changed', 'converted', 'approved'

    /**
     * Create a new message instance.
     */
    public function __construct($instituteName, $planName, $endDate, $amount, $type)
    {
        $this->instituteName = $instituteName;
        $this->planName = $planName;
        $this->endDate = $endDate;
        $this->amount = $amount;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjects = [
            'assigned' => '🎉 New Subscription Plan Assigned! - Tuoora',
            'extended' => '📅 Subscription Validity Extended! - Tuoora',
            'changed' => '🔄 Subscription Plan Upgraded! - Tuoora',
            'converted' => '✨ Trial Plan Converted to Paid! - Tuoora',
            'approved' => '✅ Subscription Renewal Approved! - Tuoora',
        ];

        return new Envelope(
            from: new Address(config('mail.support_address'), config('mail.from.name')),
            subject: $subjects[$this->type] ?? 'Subscription Status Update - Tuoora',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription_status',
            with: [
                'instituteName' => $this->instituteName,
                'planName' => $this->planName,
                'endDate' => $this->endDate,
                'amount' => $this->amount,
                'type' => $this->type,
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
