<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SalarySlipMail extends Mailable
{
    use Queueable, SerializesModels;

    public $staffName;
    public $staffEmail;
    public $employeeId;
    public $departmentName;
    public $roleName;
    public $periodText;
    public $paymentDate;
    public $paymentMethod;
    public $status;
    public $baseSalary;
    public $bonus;
    public $deductions;
    public $netSalary;
    public $notes;
    public $instituteName;
    public $instituteLogoUrl;
    public $instituteLogoPath;
    protected $pdfContent;
    protected $pdfFilename;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $salary,
        $staff,
        $institute,
        $pdfContent = null
    ) {
        $this->staffName = $staff->full_name;
        $this->staffEmail = $staff->email;
        $this->employeeId = !empty($staff->employee_id) ? $staff->employee_id : $staff->formatted_employee_id;
        $this->departmentName = $staff->departments && $staff->departments->count() > 0 
            ? $staff->departments->pluck('name')->implode(', ') 
            : ($staff->department ? $staff->department->name : 'N/A');
        $this->roleName = $staff->role ? $staff->role->name : 'Staff';

        $this->periodText = Carbon::createFromDate($salary->year, $salary->month, 1)->format('F Y');
        $this->paymentDate = $salary->payment_date ? Carbon::parse($salary->payment_date)->format('d M, Y') : Carbon::now()->format('d M, Y');
        $this->paymentMethod = $salary->payment_method ?: 'Cash';
        $this->status = $salary->status ?: 'Paid';
        $this->baseSalary = (float) $salary->base_salary;
        $this->bonus = (float) ($salary->bonus ?? 0);
        $this->deductions = (float) ($salary->deductions ?? 0);
        $this->netSalary = (float) $salary->net_salary;
        $this->notes = $salary->notes;

        $this->instituteName = $institute->institute_name ?? 'Institute';
        $this->instituteLogoPath = $institute->logo;
        $this->instituteLogoUrl = $institute->logo ? asset('storage/' . $institute->logo) : null;

        $this->pdfContent = $pdfContent;
        $cleanStaffName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $staff->full_name);
        $this->pdfFilename = "SalarySlip_{$salary->month}_{$salary->year}_{$cleanStaffName}.pdf";
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Salary Slip for {$this->periodText} - {$this->instituteName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.salary_slip',
            with: [
                'staffName' => $this->staffName,
                'staffEmail' => $this->staffEmail,
                'employeeId' => $this->employeeId,
                'departmentName' => $this->departmentName,
                'roleName' => $this->roleName,
                'periodText' => $this->periodText,
                'paymentDate' => $this->paymentDate,
                'paymentMethod' => $this->paymentMethod,
                'status' => $this->status,
                'baseSalary' => $this->baseSalary,
                'bonus' => $this->bonus,
                'deductions' => $this->deductions,
                'netSalary' => $this->netSalary,
                'notes' => $this->notes,
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
        if ($this->pdfContent) {
            return [
                Attachment::fromData(fn () => $this->pdfContent, $this->pdfFilename)
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
