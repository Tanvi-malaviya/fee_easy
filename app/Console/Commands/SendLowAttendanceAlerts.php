<?php

namespace App\Console\Commands;

use App\Mail\LowAttendanceAlertMail;
use App\Models\Student;
use App\Services\InstituteMailService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendLowAttendanceAlerts extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'attendance:low-alerts';

    /**
     * The console command description.
     */
    protected $description = 'Auto-email the parent (and CC the institute) when a student\'s recent attendance drops below the threshold.';

    protected const WINDOW_DAYS = 30;
    protected const THRESHOLD_PERCENT = 75.0;
    protected const MIN_RECORDS = 5;
    protected const RE_ALERT_AFTER_DAYS = 14;

    public function handle(): void
    {
        $windowStart = Carbon::now()->subDays(self::WINDOW_DAYS)->startOfDay();
        $reAlertCutoff = Carbon::now()->subDays(self::RE_ALERT_AFTER_DAYS);

        $students = Student::whereNotNull('batch_id')
            ->where(function ($q) use ($reAlertCutoff) {
                $q->whereNull('last_low_attendance_alert_at')
                  ->orWhere('last_low_attendance_alert_at', '<=', $reAlertCutoff);
            })
            ->with(['institute', 'parent'])
            ->get();

        $count = 0;

        foreach ($students as $student) {
            $institute = $student->institute;
            if (!$institute) {
                continue;
            }

            $totalDays = $student->attendance()->whereDate('date', '>=', $windowStart)->count();
            if ($totalDays < self::MIN_RECORDS) {
                continue;
            }

            $presentDays = $student->attendance()->whereDate('date', '>=', $windowStart)->where('status', 'Present')->count();
            $percentage = round(($presentDays / $totalDays) * 100, 1);

            if ($percentage >= self::THRESHOLD_PERCENT) {
                continue;
            }

            $recipient = $student->parent->email ?? $student->email ?? null;
            if (!$recipient) {
                continue;
            }

            try {
                InstituteMailService::send(
                    $institute,
                    $recipient,
                    new LowAttendanceAlertMail(
                        $student->name,
                        $institute->institute_name ?? $institute->name ?? 'Institute',
                        $institute->logo,
                        $percentage,
                        self::WINDOW_DAYS,
                        self::THRESHOLD_PERCENT,
                        route('institute.students.show', $student->id)
                    ),
                    $institute->email ?: null
                );

                $student->update(['last_low_attendance_alert_at' => now()]);
                $count++;
            } catch (\Throwable $e) {
                Log::error("Failed to send low-attendance alert for student #{$student->id}: " . $e->getMessage());
            }
        }

        $this->info("Sent {$count} low-attendance alert email(s).");
    }
}
