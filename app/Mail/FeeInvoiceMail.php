<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeeInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $studentEmail;
    public $invoiceNo;
    public $invoiceDate;
    public $dueDate;
    public $status;
    public $feeItem1;
    public $amount1;
    public $feeItem2;
    public $amount2;
    public $tax;
    public $total;
    public $paymentUrl;
    public $instituteName;
    public $instituteLogoUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $studentName,
        $studentEmail,
        $invoiceNo,
        $invoiceDate,
        $dueDate,
        $status,
        $feeItem1,
        $amount1,
        $feeItem2 = '',
        $amount2 = 0,
        $tax = 0,
        $total = 0,
        $paymentUrl = '#',
        $instituteName = 'Institute',
        $instituteLogoUrl = null
    ) {
        $this->studentName = $studentName;
        $this->studentEmail = $studentEmail;
        $this->invoiceNo = $invoiceNo;
        $this->invoiceDate = $invoiceDate;
        $this->dueDate = $dueDate;
        $this->status = $status;
        $this->feeItem1 = $feeItem1;
        $this->amount1 = $amount1;
        $this->feeItem2 = $feeItem2;
        $this->amount2 = $amount2;
        $this->tax = $tax;
        $this->total = $total ?: ($amount1 + $amount2 + $tax);
        $this->paymentUrl = $paymentUrl ?: '#';
        $this->instituteName = $instituteName;
        $this->instituteLogoUrl = $instituteLogoUrl ? asset('storage/' . $instituteLogoUrl) : null;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Fee Invoice - ' . $this->invoiceNo . ' - ' . $this->instituteName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.fee_invoice',
            with: [
                'studentName' => $this->studentName,
                'studentEmail' => $this->studentEmail,
                'invoiceNo' => $this->invoiceNo,
                'invoiceDate' => $this->invoiceDate,
                'dueDate' => $this->dueDate,
                'status' => $this->status,
                'feeItem1' => $this->feeItem1,
                'amount1' => $this->amount1,
                'feeItem2' => $this->feeItem2,
                'amount2' => $this->amount2,
                'tax' => $this->tax,
                'total' => $this->total,
                'paymentUrl' => $this->paymentUrl,
                'instituteName' => $this->instituteName,
                'instituteLogoUrl' => $this->instituteLogoUrl,
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
