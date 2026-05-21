<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentReportController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();
        $period  = $request->query('period', '4_weeks'); // this_week | 4_weeks | 12_weeks

        [$weeks, $startDate] = $this->resolvePeriod($period);

        $attendance  = $this->attendanceReport($student, $weeks, $startDate);
        $assignments = $this->assignmentsReport($student, $weeks, $startDate);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'period'      => $period,
                'attendance'  => $attendance,
                'assignments' => $assignments,
            ],
        ]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Returns [weekCount, startDate (Carbon)] for the given period key.
     */
    private function resolvePeriod(string $period): array
    {
        $today = Carbon::today();

        return match ($period) {
            'this_week' => [1,  $today->copy()->startOfWeek()],
            '12_weeks'  => [12, $today->copy()->subWeeks(11)->startOfWeek()],
            default     => [4,  $today->copy()->subWeeks(3)->startOfWeek()],  // 4_weeks
        };
    }

    /**
     * Label for a week slot: "W1", "W2" ... or date range for this_week.
     */
    private function weekLabel(int $index, int $total, Carbon $weekStart): string
    {
        if ($total === 1) {
            return $weekStart->format('d M') . '–' . $weekStart->copy()->endOfWeek()->format('d M');
        }
        return 'W' . $index;
    }

    // ─── Attendance ───────────────────────────────────────────────────────────

    private function attendanceReport(Student $student, int $weeks, Carbon $startDate): array
    {
        $endDate = $startDate->copy()->addWeeks($weeks)->subDay();
        $today   = Carbon::today();

        // Never count days before the student was enrolled
        $enrollStart    = Carbon::parse($student->created_at)->startOfDay();
        $effectiveStart = $startDate->lt($enrollStart) ? $enrollStart->copy() : $startDate->copy();
        $effectiveEnd   = $endDate->lt($today) ? $endDate->copy() : $today->copy();

        // All attendance records: from enrollment to today
        $records = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$effectiveStart->toDateString(), $effectiveEnd->toDateString()])
            ->get()
            ->keyBy('date');

        $totalPresent = $records->whereIn('status', ['present', 'late'])->count();
        $totalAbsent  = $records->where('status', 'absent')->count();

        // Total = max(scheduled batch days, actual marked days) — handles non-batch day markings
        $scheduledDays  = $this->countBatchDays($student, $effectiveStart, $effectiveEnd);
        $totalClassDays = max($scheduledDays, $totalPresent + $totalAbsent);
        $overallPct     = $totalClassDays > 0
            ? min(100, round(($totalPresent / $totalClassDays) * 100))
            : 0;

        // Per-week breakdown
        $weeklyData = [];
        for ($w = 0; $w < $weeks; $w++) {
            $wStart = $startDate->copy()->addWeeks($w);
            $wEnd   = $wStart->copy()->endOfWeek();

            // Respect enrollment date and cap at today
            $wEffStart = $wStart->lt($enrollStart) ? $enrollStart->copy() : $wStart->copy();
            $wEffEnd   = $wEnd->lt($today) ? $wEnd->copy() : $today->copy();

            // Week entirely before enrollment — skip
            if ($wEffStart->gt($wEffEnd)) {
                $weeklyData[] = [
                    'label'   => $this->weekLabel($w + 1, $weeks, $wStart),
                    'present' => 0,
                    'total'   => 0,
                    'pct'     => 0,
                ];
                continue;
            }

            $wPresent = $records->filter(
                fn($r) => $r->date >= $wEffStart->toDateString()
                       && $r->date <= $wEffEnd->toDateString()
                       && in_array($r->status, ['present', 'late'])
            )->count();

            $wAbsent = $records->filter(
                fn($r) => $r->date >= $wEffStart->toDateString()
                       && $r->date <= $wEffEnd->toDateString()
                       && $r->status === 'absent'
            )->count();

            $wScheduled = $this->countBatchDays($student, $wEffStart, $wEffEnd);
            $wTotal     = max($wScheduled, $wPresent + $wAbsent);
            $wPct       = $wTotal > 0 ? min(100, round(($wPresent / $wTotal) * 100)) : 0;

            $weeklyData[] = [
                'label'   => $this->weekLabel($w + 1, $weeks, $wStart),
                'present' => $wPresent,
                'total'   => $wTotal,
                'pct'     => $wPct,
            ];
        }

        return [
            'pct'     => $overallPct,
            'summary' => $totalPresent . ' of ' . $totalClassDays . ' days',
            'weeks'   => $weeklyData,
        ];
    }

    // ─── Batch Day Counter ────────────────────────────────────────────────────

    /**
     * Count actual scheduled class days for the student's batch
     * between $from and $to (inclusive), excluding Sundays.
     */
    private function countBatchDays(Student $student, Carbon $from, Carbon $to): int
    {
        $batchDays = $student->batch->days ?? [];
        if (empty($batchDays)) {
            return 0;
        }

        $count   = 0;
        $current = $from->copy();
        $end     = $to->copy()->endOfDay();

        while ($current->lte($end)) {
            $dayName = $current->format('l');      // "Monday"
            $dayAbbr = substr($dayName, 0, 3);     // "Mon"

            if (
                !$current->isSunday() &&
                (in_array($dayName, $batchDays) || in_array($dayAbbr, $batchDays))
            ) {
                $count++;
            }
            $current->addDay();
        }

        return $count;
    }

    // ─── Assignments ──────────────────────────────────────────────────────────

    private function assignmentsReport(Student $student, int $weeks, Carbon $startDate): array
    {
        $endDate = $startDate->copy()->addWeeks($weeks)->subDay();

        // Homeworks for the student's batch in the period
        $batchId   = $student->batch_id;
        $homeworks = Homework::where('batch_id', $batchId)
            ->whereBetween('due_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->pluck('id');

        // Student submissions
        $submissions = HomeworkSubmission::where('student_id', $student->id)
            ->whereIn('homework_id', $homeworks)
            ->get()
            ->keyBy('homework_id');

        $totalAssigned  = $homeworks->count();
        $totalCompleted = $submissions->count();
        $totalPending   = $totalAssigned - $totalCompleted;
        $overallPct     = $totalAssigned > 0 ? round(($totalCompleted / $totalAssigned) * 100) : 0;

        // Per-week breakdown
        $weeklyData = [];
        for ($w = 0; $w < $weeks; $w++) {
            $wStart = $startDate->copy()->addWeeks($w);
            $wEnd   = $wStart->copy()->endOfWeek();

            $wHomeworks = Homework::where('batch_id', $batchId)
                ->whereBetween('due_date', [$wStart->toDateString(), $wEnd->toDateString()])
                ->pluck('id');

            $wCompleted = HomeworkSubmission::where('student_id', $student->id)
                ->whereIn('homework_id', $wHomeworks)
                ->count();

            $wTotal   = $wHomeworks->count();
            $wPending = $wTotal - $wCompleted;

            $weeklyData[] = [
                'label'     => $this->weekLabel($w + 1, $weeks, $wStart),
                'completed' => $wCompleted,
                'pending'   => max(0, $wPending),
                'total'     => $wTotal,
            ];
        }

        return [
            'pct'     => $overallPct,
            'summary' => $totalCompleted . ' completed · ' . max(0, $totalPending) . ' pending',
            'weeks'   => $weeklyData,
        ];
    }
}
