<?php

namespace App\Console\Commands;

use App\Mail\ParentProgressDigestMail;
use App\Models\Exam;
use App\Models\Homework;
use App\Models\Payment;
use App\Models\Student;
use App\Services\InstituteMailService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendParentProgressDigest extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'digest:parent-progress';

    /**
     * The console command description.
     */
    protected $description = 'Email a weekly consolidated progress digest (attendance, homework, upcoming exams, fees) to every parent.';

    public function handle(): void
    {
        $weekStart = Carbon::now()->subDays(7)->startOfDay();
        $today = Carbon::today();
        $periodLabel = $weekStart->format('M d') . ' - ' . $today->format('M d, Y');

        $students = Student::whereNotNull('batch_id')
            ->with(['batch', 'institute', 'parent'])
            ->get();

        $count = 0;

        foreach ($students as $student) {
            $recipientEmail = $student->parent->email ?? $student->email ?? null;
            $institute = $student->institute;

            if (!$recipientEmail || !$institute) {
                continue;
            }

            $totalDays = $student->attendance()->whereDate('date', '>=', $weekStart)->count();
            $presentDays = $student->attendance()->whereDate('date', '>=', $weekStart)->where('status', 'Present')->count();
            $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

            $homeworkAssigned = Homework::where('batch_id', $student->batch_id)
                ->whereDate('due_date', '>=', $weekStart)
                ->whereDate('due_date', '<=', $today)
                ->get();
            $submittedIds = $student->homeworkSubmissions()
                ->whereIn('homework_id', $homeworkAssigned->pluck('id'))
                ->whereIn('status', ['Submitted', 'Reviewed'])
                ->pluck('homework_id');

            $upcomingExams = Exam::where('batch_id', $student->batch_id)
                ->where('status', 'scheduled')
                ->whereDate('exam_date', '>=', $today)
                ->whereDate('exam_date', '<=', $today->copy()->addDays(14))
                ->orderBy('exam_date')
                ->get()
                ->map(fn ($exam) => [
                    'title' => $exam->title,
                    'date' => Carbon::parse($exam->exam_date)->format('M d, Y'),
                ])
                ->toArray();

            $totalPaid = Payment::where('student_id', $student->id)->sum('amount');
            $balance = max(0, ($student->monthly_fee ?? 0) - $totalPaid);
            $feeStatus = 'Full Paid';
            if (!$student->monthly_fee || $student->monthly_fee == 0) {
                $feeStatus = 'No Fee';
            } elseif ($balance >= $student->monthly_fee) {
                $feeStatus = 'Pending';
            } elseif ($balance > 0) {
                $feeStatus = 'Partial Dues';
            }

            try {
                InstituteMailService::send(
                    $institute,
                    $recipientEmail,
                    new ParentProgressDigestMail(
                        $student->name,
                        $institute->institute_name ?? $institute->name ?? 'Institute',
                        $institute->logo,
                        $periodLabel,
                        $attendancePercentage,
                        ['completed' => $submittedIds->count(), 'total' => $homeworkAssigned->count()],
                        $upcomingExams,
                        $feeStatus,
                        (float) $balance,
                        route('institute.students.show', $student->id)
                    )
                );
                $count++;
            } catch (\Throwable $e) {
                Log::error("Failed to send parent progress digest for student #{$student->id}: " . $e->getMessage());
            }
        }

        $this->info("Sent {$count} parent progress digest email(s).");
    }
}
