<?php

namespace App\Console\Commands;

use App\Mail\InstituteWeeklySummaryMail;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Fee;
use App\Models\Institute;
use App\Models\Lead;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInstituteWeeklySummary extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'digest:institute-summary';

    /**
     * The console command description.
     */
    protected $description = 'Email each institute owner a weekly summary: fees, attendance trend, upcoming exams, birthdays, and low-attendance flags.';

    public function handle(): void
    {
        $today = Carbon::today();
        $weekStart = $today->copy()->subDays(7);
        $prevWeekStart = $today->copy()->subDays(14);
        $periodLabel = $weekStart->format('M d') . ' - ' . $today->format('M d, Y');

        $institutes = Institute::where('status', 'active')
            ->whereNotNull('email')
            ->get();

        $count = 0;

        foreach ($institutes as $institute) {
            $feesCollected = Fee::where('institute_id', $institute->id)
                ->whereDate('updated_at', '>=', $weekStart)
                ->sum('paid_amount');

            $feesPending = Fee::where('institute_id', $institute->id)
                ->whereIn('status', ['Unpaid', 'Partial'])
                ->get()
                ->sum(fn ($fee) => max(0, $fee->total_amount - $fee->paid_amount));

            $thisWeekTotal = Attendance::whereHas('student', fn ($q) => $q->where('institute_id', $institute->id))
                ->whereDate('date', '>=', $weekStart)->count();
            $thisWeekPresent = Attendance::whereHas('student', fn ($q) => $q->where('institute_id', $institute->id))
                ->whereDate('date', '>=', $weekStart)->where('status', 'Present')->count();
            $attendancePercentage = $thisWeekTotal > 0 ? round(($thisWeekPresent / $thisWeekTotal) * 100, 1) : 0;

            $prevWeekTotal = Attendance::whereHas('student', fn ($q) => $q->where('institute_id', $institute->id))
                ->whereDate('date', '>=', $prevWeekStart)->whereDate('date', '<', $weekStart)->count();
            $prevWeekPresent = Attendance::whereHas('student', fn ($q) => $q->where('institute_id', $institute->id))
                ->whereDate('date', '>=', $prevWeekStart)->whereDate('date', '<', $weekStart)->where('status', 'Present')->count();
            $prevAttendancePercentage = $prevWeekTotal > 0 ? round(($prevWeekPresent / $prevWeekTotal) * 100, 1) : 0;

            $upcomingExamCount = Exam::where('institute_id', $institute->id)
                ->where('status', 'scheduled')
                ->whereDate('exam_date', '>=', $today)
                ->whereDate('exam_date', '<=', $today->copy()->addDays(14))
                ->count();

            $newLeadsCount = Lead::where('institute_id', $institute->id)
                ->whereDate('created_at', '>=', $weekStart)
                ->count();

            $birthdaysThisWeek = Student::where('institute_id', $institute->id)
                ->whereNotNull('dob')
                ->get()
                ->filter(function ($student) use ($today) {
                    try {
                        $dob = Carbon::parse($student->dob);
                        $nextBirthday = $dob->copy()->year($today->year);
                        if ($nextBirthday->lt($today)) {
                            $nextBirthday->addYear();
                        }
                        return $nextBirthday->between($today, $today->copy()->addDays(7));
                    } catch (\Exception $e) {
                        return false;
                    }
                })
                ->map(fn ($student) => [
                    'name' => $student->name,
                    'date' => Carbon::parse($student->dob)->format('M d'),
                ])
                ->values()
                ->toArray();

            $lowAttendanceCount = Student::where('institute_id', $institute->id)
                ->whereNotNull('last_low_attendance_alert_at')
                ->whereDate('last_low_attendance_alert_at', '>=', $weekStart)
                ->count();

            try {
                Mail::to($institute->email)->send(new InstituteWeeklySummaryMail(
                    $institute->institute_name ?? $institute->name ?? 'Institute',
                    $periodLabel,
                    (float) $feesCollected,
                    (float) $feesPending,
                    $attendancePercentage,
                    round($attendancePercentage - $prevAttendancePercentage, 1),
                    $upcomingExamCount,
                    $birthdaysThisWeek,
                    $lowAttendanceCount,
                    $newLeadsCount,
                    route('institute.dashboard')
                ));
                $count++;
            } catch (\Throwable $e) {
                Log::error("Failed to send weekly summary to institute #{$institute->id}: " . $e->getMessage());
            }
        }

        $this->info("Sent {$count} institute weekly summary email(s).");
    }
}
