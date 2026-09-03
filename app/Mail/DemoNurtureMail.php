<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemoNurtureMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullName;
    public $instituteName;
    public $stage; // 1, 3, or 7

    public function __construct(string $fullName, string $instituteName, int $stage)
    {
        $this->fullName = $fullName;
        $this->instituteName = $instituteName;
        $this->stage = $stage;
    }

    public function envelope(): Envelope
    {
        $subjects = [
            1 => 'Still interested in Tuoora ERP? Let\'s get you started',
            3 => 'Did you get a chance to try Tuoora?',
            7 => 'Here\'s what other institutes use most on Tuoora',
        ];

        return new Envelope(
            from: new Address(config('mail.info_address'), config('mail.from.name')),
            subject: $subjects[$this->stage] ?? 'Tuoora ERP - Following Up',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.demo_nurture',
            with: [
                'fullName' => $this->fullName,
                'instituteName' => $this->instituteName,
                'stage' => $this->stage,
            ],
        );
    }
}
