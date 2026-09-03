<?php

namespace App\Console\Commands;

use App\Mail\FeeReminderMail;
use App\Models\Fee;
use App\Services\InstituteMailService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendFeeReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fees:send-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Email overdue/upcoming fee reminders: 3 days before due, on due date, and 3/7 days overdue. Due date comes from the student\'s batch (Batch::fees_last_date) — batches without one configured are skipped entirely, no fallback.';

    public function handle(): void
    {
        $today = Carbon::today();

        $pendingFees = Fee::whereIn('status', ['Unpaid', 'Partial'])
            ->with(['student.batch', 'institute'])
            ->get();

        $count = 0;
        $skippedNoDueDate = 0;

        foreach ($pendingFees as $fee) {
            $student = $fee->student;
            $institute = $fee->institute;

            if (!$student || !$institute || empty($student->email)) {
                continue;
            }

            $batch = $student->batch;
            if (!$batch || !$batch->fees_last_date) {
                $skippedNoDueDate++;
                continue;
            }

            $dueDate = Carbon::parse($batch->fees_last_date)->startOfDay();
            $daysUntilDue = $today->diffInDays($dueDate, false); // positive = future, negative = past

            $stage = null;
            $daysOverdue = 0;

            if ($daysUntilDue === 3) {
                $stage = 'upcoming';
            } elseif ($daysUntilDue === 0) {
                $stage = 'due_today';
            } elseif ($daysUntilDue === -3 || $daysUntilDue === -7) {
                $stage = 'overdue';
                $daysOverdue = abs($daysUntilDue);
            }

            if (!$stage) {
                continue;
            }

            $remaining = max(0, $fee->total_amount - $fee->paid_amount);
            if ($remaining <= 0) {
                continue;
            }

            try {
                InstituteMailService::send(
                    $institute,
                    $student->email,
                    new FeeReminderMail(
                        $student->name,
                        $institute->institute_name ?? $institute->name ?? 'Institute',
                        $institute->logo,
                        (float) $remaining,
                        $dueDate->format('d M, Y'),
                        $stage,
                        $daysOverdue,
                        url("/institute/fees/receipts/" . $fee->id)
                    )
                );
                $count++;
            } catch (\Throwable $e) {
                Log::error("Failed to send fee reminder for fee #{$fee->id}: " . $e->getMessage());
            }
        }

        $this->info("Sent {$count} fee reminder email(s). Skipped {$skippedNoDueDate} pending fee(s) whose batch has no fees_last_date configured.");
    }
}
