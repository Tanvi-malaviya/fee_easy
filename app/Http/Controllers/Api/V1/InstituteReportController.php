<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Institute;
use App\Models\SubscriptionPayment;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use Illuminate\Http\Request;
use App\Enums\Month;
use App\Enums\Year;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class InstituteReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $institute = $request->user();

        $totalFees = Fee::where('institute_id', $institute->id)->sum('total_amount');
        $paidFees = Fee::where('institute_id', $institute->id)->sum('paid_amount');

        // Calculate due fees more comprehensively (Monthly Fee - Total Payments)
        $dueFees = 0;
        foreach ($institute->students as $student) {
            $totalPaid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
            $dueFees += max(0, ($student->monthly_fee ?? 0) - $totalPaid);
        }

        $instituteHomeworkIds = Homework::where('institute_id', $institute->id)->pluck('id');
        $allSubmissions = HomeworkSubmission::whereIn('homework_id', $instituteHomeworkIds)->whereNotNull('score')->get();
        $globalAvg = $allSubmissions->avg('score');
        if ($globalAvg > 0 && $globalAvg <= 10) {
            $globalAvg = $globalAvg * 10;
        }
        $performance = $globalAvg ? round($globalAvg, 1) . '%' : '0%';

        return response()->json([
            'status' => 'success',
            'data' => [
                'students_count' => $institute->students()->count(),
                'batches_count' => $institute->batches()->count(),
                'total_fees' => $totalFees,
                'total_paid_fees' => $paidFees,
                'total_due_fees' => $dueFees,
                'performance' => $performance,
            ],
        ]);
    }

    /**
     * Business analytics dashboard: revenue trends, fee collection %,
     * batch-wise attendance trends, and batch-wise dropout snapshot.
     */
    public function analytics(Request $request)
    {
        $request->validate([
            'months' => 'nullable|integer|min:3|max:12',
        ]);

        $institute_id = $request->user()->id;
        $monthsCount = (int) ($request->input('months') ?: 6);

        $batches = Batch::where('institute_id', $institute_id)->get();

        // --- Revenue & fee collection trend (last N months) ---
        $revenueTrend = [];
        $collectionTrend = [];

        for ($i = $monthsCount - 1; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $start = $monthDate->copy()->startOfMonth()->toDateString();
            $end = $monthDate->copy()->endOfMonth()->toDateString();

            $collected = (float) SubscriptionPayment::query()
                ->whereBetween('created_at', ["$start 00:00:00", "$end 23:59:59"])
                ->whereHas('subscription', function ($q) use ($institute_id) {
                    $q->where('institute_id', $institute_id);
                })
                ->sum('amount');

            $feeCollected = (float) \App\Models\Payment::whereHas('student', function ($q) use ($institute_id) {
                $q->where('institute_id', $institute_id);
            })
                ->whereBetween('paid_at', ["$start 00:00:00", "$end 23:59:59"])
                ->sum('amount');

            $billed = (float) Fee::where('institute_id', $institute_id)
                ->whereBetween('date', [$start, $end])
                ->sum('total_amount');

            $collectionPercent = $billed > 0
                ? round(($feeCollected / $billed) * 100, 1)
                : ($feeCollected > 0 ? 100.0 : 0.0);

            $revenueTrend[] = [
                'month' => $monthDate->format('M Y'),
                'fee_collected' => $feeCollected,
                'billed' => $billed,
                'subscription_revenue' => $collected,
                'total_revenue' => $feeCollected + $collected,
            ];

            $collectionTrend[] = [
                'month' => $monthDate->format('M Y'),
                'percent' => $collectionPercent,
            ];
        }

        $currentMonth = $revenueTrend[count($revenueTrend) - 1] ?? null;
        $previousMonth = count($revenueTrend) >= 2 ? $revenueTrend[count($revenueTrend) - 2] : null;

        $currentRevenue = $currentMonth['total_revenue'] ?? 0;
        $previousRevenue = $previousMonth['total_revenue'] ?? 0;
        $revenueGrowthPercent = $previousRevenue > 0
            ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 1)
            : ($currentRevenue > 0 ? 100.0 : 0.0);

        $totalBilledAllTime = (float) Fee::where('institute_id', $institute_id)->sum('total_amount');
        $totalCollectedAllTime = (float) Fee::where('institute_id', $institute_id)->sum('paid_amount');
        $overallCollectionPercent = $totalBilledAllTime > 0
            ? round(($totalCollectedAllTime / $totalBilledAllTime) * 100, 1)
            : 0.0;

        // --- Batch-wise attendance trend (last N months) ---
        $attendanceBatches = [];
        $overallPresentThisMonth = 0;
        $overallTotalThisMonth = 0;

        foreach ($batches as $batch) {
            $trend = [];
            for ($i = $monthsCount - 1; $i >= 0; $i--) {
                $monthDate = now()->subMonths($i);
                $records = Attendance::where('batch_id', $batch->id)
                    ->whereMonth('date', $monthDate->month)
                    ->whereYear('date', $monthDate->year)
                    ->get();

                $total = $records->count();
                $present = $records->filter(fn($a) => strtolower($a->status) === 'present')->count();
                $pct = $total > 0 ? round(($present / $total) * 100, 1) : null;

                if ($i === 0) {
                    $overallPresentThisMonth += $present;
                    $overallTotalThisMonth += $total;
                }

                $trend[] = ['month' => $monthDate->format('M'), 'percent' => $pct];
            }

            $attendanceBatches[] = [
                'batch_id' => $batch->id,
                'batch_name' => $batch->name,
                'trend' => $trend,
            ];
        }

        $overallAttendancePercent = $overallTotalThisMonth > 0
            ? round(($overallPresentThisMonth / $overallTotalThisMonth) * 100, 1)
            : 0.0;

        // --- Batch-wise dropout snapshot ---
        // Student `status` is stored inconsistently ('active'/'1' for active students created
        // over time); anything else is treated as inactive/dropped out.
        $allStudents = Student::where('institute_id', $institute_id)->get(['id', 'batch_id', 'status']);
        $activeStatuses = ['active', '1', 1, true];

        $dropoutBatches = [];
        $totalActiveAll = 0;
        $totalInactiveAll = 0;

        foreach ($batches as $batch) {
            $batchStudents = $allStudents->where('batch_id', $batch->id);
            $active = $batchStudents->filter(fn($s) => in_array($s->status, $activeStatuses, true))->count();
            $inactive = $batchStudents->count() - $active;
            $total = $active + $inactive;
            $rate = $total > 0 ? round(($inactive / $total) * 100, 1) : 0.0;

            $totalActiveAll += $active;
            $totalInactiveAll += $inactive;

            $dropoutBatches[] = [
                'batch_id' => $batch->id,
                'batch_name' => $batch->name,
                'active_count' => $active,
                'inactive_count' => $inactive,
                'total' => $total,
                'dropout_rate' => $rate,
            ];
        }

        $totalAllStudents = $totalActiveAll + $totalInactiveAll;
        $overallDropoutRate = $totalAllStudents > 0
            ? round(($totalInactiveAll / $totalAllStudents) * 100, 1)
            : 0.0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'revenue' => [
                    'current_month_total' => $currentRevenue,
                    'previous_month_total' => $previousRevenue,
                    'growth_percent' => $revenueGrowthPercent,
                    'monthly_trend' => $revenueTrend,
                ],
                'fee_collection' => [
                    'overall_percent' => $overallCollectionPercent,
                    'current_month_percent' => $collectionTrend[count($collectionTrend) - 1]['percent'] ?? 0,
                    'total_billed' => $totalBilledAllTime,
                    'total_collected' => $totalCollectedAllTime,
                    'monthly_trend' => $collectionTrend,
                ],
                'attendance' => [
                    'overall_percent_this_month' => $overallAttendancePercent,
                    'batches' => $attendanceBatches,
                ],
                'dropout' => [
                    'overall_rate' => $overallDropoutRate,
                    'total_active' => $totalActiveAll,
                    'total_inactive' => $totalInactiveAll,
                    'batches' => $dropoutBatches,
                ],
            ],
        ]);
    }

    public function feeReport(Request $request)
    {
        $request->validate([
            'batch_id' => 'nullable|exists:batches,id',
            'month' => ['nullable', Rule::enum(Month::class)],
            'year' => ['nullable', Rule::enum(Year::class)],
        ]);

        $institute_id = $request->user()->id;

        $query = Fee::with('student:id,name,enrollment_id,batch_id')->where('institute_id', $institute_id);

        if ($request->filled('batch_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('batch_id', $request->batch_id);
            });
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $fees = $query->latest()->get();

        $batchesQuery = Batch::where('institute_id', $institute_id);
        if ($request->filled('batch_id')) {
            $batchesQuery->where('id', $request->batch_id);
        }
        $batches = $batchesQuery->get();
        $batchesData = [];

        $totalExpectedAll = 0;
        $totalCollectedAll = 0;

        foreach ($batches as $batch) {
            $students = Student::where('batch_id', $batch->id)->get();
            $batch_fee_query = Fee::where('institute_id', $institute_id)
                ->whereHas('student', function ($q) use ($batch) {
                    $q->where('batch_id', $batch->id);
                });

            if ($request->filled('month')) {
                $batch_fee_query->where('month', $request->month);
            }
            if ($request->filled('year')) {
                $batch_fee_query->where('year', $request->year);
            }

            $batch_fees = $batch_fee_query->get();

            $totalBilled = $batch->fees * $students->count();
            $totalCollected = $batch_fees->sum('paid_amount');
            $totalDue = max(0, $totalBilled - $totalCollected);

            $totalExpectedAll += $totalBilled;
            $totalCollectedAll += $totalCollected;

            $batchesData[] = [
                'batch_id' => $batch->id,
                'batch_name' => $batch->name,
                'batch_fees' => $batch->fees,
                'total_collected' => $totalCollected,
                'total_due' => $totalDue,
                'students_count' => $students->count()
            ];
        }

        $summary = [
            'total_amount' => $totalExpectedAll,
            'paid_amount' => $totalCollectedAll,
            'due_amount' => max(0, $totalExpectedAll - $totalCollectedAll),
            'count' => $fees->count(),
        ];

        $trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthName = $monthDate->format('M');
            $start = $monthDate->startOfMonth()->toDateString();
            $end = $monthDate->endOfMonth()->toDateString();

            $collectedInMonth = Fee::where('institute_id', $institute_id)
                ->whereBetween('date', [$start, $end])
                ->sum('paid_amount');

            // Divide overall expected revenue evenly across periods for benchmark targets
            $expectedInMonth = $totalExpectedAll / 6;

            $trends[] = [
                'month' => $monthName,
                'collected' => (float) $collectedInMonth,
                'expected' => (float) $expectedInMonth
            ];
        }

        $responseData = [
            'summary' => $summary,
            'batches' => $batchesData,
            'trends' => $trends
        ];

        if ($request->filled('batch_id')) {
            $responseData['fees'] = $fees;
        }

        return response()->json([
            'status' => 'success',
            'data' => $responseData,
        ]);
    }

    public function attendanceReport(Request $request)
    {
        $request->validate([
            'batch_id' => 'nullable|exists:batches,id',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer',
        ]);

        $institute_id = $request->user()->id;
        $batch_ids = Batch::where('institute_id', $institute_id)->pluck('id');

        $query = Attendance::with('student:id,name,enrollment_id,batch_id');

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        } else {
            $query->whereIn('batch_id', $batch_ids);
        }

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $attendance = $query->orderBy('date', 'desc')->get();

        $summary = [
            'present' => $attendance->filter(fn($att) => strtolower($att->status) === 'present')->count(),
            'absent' => $attendance->filter(fn($att) => strtolower($att->status) === 'absent')->count(),
            'leave' => $attendance->filter(fn($att) => strtolower($att->status) === 'leave')->count(),
            'total' => $attendance->count(),
        ];

        $batchesQuery = Batch::where('institute_id', $institute_id);
        if ($request->filled('batch_id')) {
            $batchesQuery->where('id', $request->batch_id);
        }
        $batches = $batchesQuery->get();
        $batchesData = [];

        foreach ($batches as $batch) {
            $students = Student::where('batch_id', $batch->id)->get();
            $batch_att_query = Attendance::where('batch_id', $batch->id);

            if ($request->filled('month')) {
                $batch_att_query->whereMonth('date', $request->month);
            }
            if ($request->filled('year')) {
                $batch_att_query->whereYear('date', $request->year);
            }

            $batch_attendance = $batch_att_query->get();
            $total_records = $batch_attendance->count();
            $present_records = $batch_attendance->filter(fn($att) => strtolower($att->status) === 'present')->count();
            $avg_percentage = $total_records > 0 ? round(($present_records / $total_records) * 100, 2) : 0;

            $batchesData[] = [
                'batch_id' => $batch->id,
                'batch_name' => $batch->name,
                'avg_attendance' => $avg_percentage,
                'students_count' => $students->count()
            ];
        }

        $responseData = [
            'summary' => $summary,
            'batches' => $batchesData
        ];

        $mappedAttendance = $attendance->map(function ($att) use ($request) {
            $student_attendance_query = Attendance::where('student_id', $att->student_id)
                ->where('status', 'present');

            if ($request->filled('month')) {
                $student_attendance_query->whereMonth('date', $request->month);
            }
            if ($request->filled('year')) {
                $student_attendance_query->whereYear('date', $request->year);
            }

            return [
                'id' => $att->id,
                'student_id' => $att->student_id,
                'batch_id' => $att->batch_id,
                'date' => $att->date,
                'status' => $att->status,
                'marked_by' => $att->marked_by,
                'created_at' => $att->created_at,
                'updated_at' => $att->updated_at,
                'student' => [
                    'name' => $att->student->name ?? 'N/A',
                    'present_days' => $student_attendance_query->count()
                ]
            ];
        });

        $responseData['attendance'] = $mappedAttendance;

        // Aggregate unique student records for front-end rendering
        $studentsAttendance = $attendance->groupBy('student_id');
        $studentRoster = [];
        foreach ($studentsAttendance as $studentId => $logs) {
            $firstLog = $logs->first();
            $studentName = $firstLog->student->name ?? 'N/A';
            $batchName = Batch::find($firstLog->batch_id)->name ?? 'N/A';

            $presentCount = $logs->filter(fn($att) => strtolower($att->status) === 'present')->count();
            $absentCount = $logs->filter(fn($att) => strtolower($att->status) === 'absent')->count();
            $leaveCount = $logs->filter(fn($att) => strtolower($att->status) === 'leave')->count();
            $totalLogs = $logs->count();
            $pct = $totalLogs > 0 ? round(($presentCount / $totalLogs) * 100, 1) : 0;

            $studentRoster[] = [
                'student_id' => $studentId,
                'student_name' => $studentName,
                'batch_name' => $batchName,
                'total_logs' => $totalLogs,
                'present' => $presentCount,
                'absent' => $absentCount,
                'leave' => $leaveCount,
                'percentage' => $pct . '%'
            ];
        }
        $responseData['student_roster'] = $studentRoster;

        return response()->json([
            'status' => 'success',
            'data' => $responseData,
        ]);
    }

    public function performanceReport(Request $request)
    {
        $request->validate([
            'batch_id' => 'nullable|exists:batches,id',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer',
        ]);

        $institute_id = $request->user()->id;

        $instituteHomeworkIds = Homework::where('institute_id', $institute_id)->pluck('id');
        $allSubmissions = HomeworkSubmission::whereIn('homework_id', $instituteHomeworkIds)->whereNotNull('score')->get();
        $globalAvg = $allSubmissions->avg('score');

        if ($globalAvg > 0 && $globalAvg <= 10) {
            $globalAvg = $globalAvg * 10;
        }

        $batchesQuery = Batch::where('institute_id', $institute_id);
        if ($request->filled('batch_id')) {
            $batchesQuery->where('id', $request->batch_id);
        }
        $batches = $batchesQuery->get();
        $batchesData = [];

        foreach ($batches as $batch) {
            $students = Student::where('batch_id', $batch->id)->get();

            $batchHomeworkIds = Homework::where('batch_id', $batch->id)->pluck('id');
            $batchSubmissions = HomeworkSubmission::whereIn('homework_id', $batchHomeworkIds)
                ->whereNotNull('score')
                ->get();

            $batchAvg = $batchSubmissions->avg('score');
            if ($batchAvg > 0 && $batchAvg <= 10) {
                $batchAvg = $batchAvg * 10;
            }

            $batchesData[] = [
                'batch_id' => $batch->id,
                'batch_name' => $batch->name,
                'avg_score' => $batchAvg ? round($batchAvg, 2) . '%' : '0%',
                'students_count' => $students->count()
            ];
        }

        $trends = [];
        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];

        foreach ($months as $num => $name) {
            $monthlySubmissions = HomeworkSubmission::whereIn('homework_id', $instituteHomeworkIds)
                ->whereMonth('created_at', $num)
                ->whereNotNull('score')
                ->get();

            $monthlyAvg = $monthlySubmissions->avg('score');
            if ($monthlyAvg > 0 && $monthlyAvg <= 10) {
                $monthlyAvg = $monthlyAvg * 10;
            }

            $trends[] = [
                'month' => $name,
                'avg_score' => $monthlyAvg ? round($monthlyAvg, 1) : 0
            ];
        }

        $studentsData = [];
        $passCount = 0;
        $needsAttentionCount = 0;

        foreach ($batches as $batch) {
            $batchStudents = Student::where('batch_id', $batch->id)->get();
            foreach ($batchStudents as $stu) {
                $stuHomeworkIds = Homework::where('batch_id', $batch->id)->pluck('id');
                $stuSubmissions = HomeworkSubmission::whereIn('homework_id', $stuHomeworkIds)
                    ->where('student_id', $stu->id)
                    ->whereNotNull('score')
                    ->get();

                $stuAvg = $stuSubmissions->avg('score');
                if ($stuAvg > 0 && $stuAvg <= 10) {
                    $stuAvg = $stuAvg * 10;
                }

                $finalScore = $stuAvg ? round($stuAvg, 2) : 0;

                if ($finalScore >= 50) {
                    $passCount++;
                } else {
                    $needsAttentionCount++;
                }

                $logsForStudent = Attendance::where('student_id', $stu->id);
                if ($request->filled('month')) {
                    $logsForStudent->whereMonth('date', $request->month);
                }
                if ($request->filled('year')) {
                    $logsForStudent->whereYear('date', $request->year);
                }
                $logs = $logsForStudent->get();

                $presentCount = $logs->filter(fn($att) => strtolower($att->status) === 'present')->count();
                $totalLogs = $logs->count();
                $attPct = $totalLogs > 0 ? round(($presentCount / $totalLogs) * 100, 1) : 0;

                $studentsData[] = [
                    'student_id' => $stu->id,
                    'student_name' => $stu->name,
                    'batch_name' => $batch->name,
                    'avg_score' => $finalScore,
                    'avg_attendance' => $attPct
                ];
            }
        }

        $totalStudentsCount = count($studentsData);
        $passPercentage = $totalStudentsCount > 0 ? round(($passCount / $totalStudentsCount) * 100, 1) : 0;

        $summary = [
            'average_performance' => $globalAvg ? round($globalAvg, 2) . '%' : '0%',
            'pass_percentage' => $passPercentage . '%',
            'needs_attention' => $needsAttentionCount,
            'average_grade' => $this->calculateGrade($globalAvg),
        ];

        $responseData = [
            'summary' => $summary,
            'batches' => $batchesData,
            'trends' => $trends,
            'student_roster' => $studentsData
        ];

        if ($request->filled('batch_id')) {
            $legacyStudents = [];
            foreach ($studentsData as $s) {
                $legacyStudents[] = [
                    'student_id' => $s['student_id'],
                    'student_name' => $s['student_name'],
                    'avg_score' => $s['avg_score'] . '%'
                ];
            }
            $responseData['students'] = $legacyStudents;
        }

        return response()->json([
            'status' => 'success',
            'data' => $responseData,
        ]);
    }

    private function calculateGrade($score)
    {
        if (!$score)
            return 'N/A';
        if ($score >= 90)
            return 'A+';
        if ($score >= 80)
            return 'A';
        if ($score >= 70)
            return 'B+';
        if ($score >= 60)
            return 'B';
        if ($score >= 50)
            return 'C';
        if ($score >= 35)
            return 'D';
        return 'E';
    }

    public function exportPerformanceReport(Request $request)
    {
        $request->validate([
            'batch_id' => 'nullable|exists:batches,id',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer',
        ]);

        $institute = $request->user();

        $batchesQuery = Batch::where('institute_id', $institute->id);
        if ($request->filled('batch_id')) {
            $batchesQuery->where('id', $request->batch_id);
            $batch = Batch::find($request->batch_id);
        } else {
            $batch = (object) ['name' => 'All Batches'];
        }

        $batches = $batchesQuery->get();
        $batchesData = [];

        foreach ($batches as $b) {
            $students = Student::where('batch_id', $b->id)->get();
            $batchHomeworkIds = Homework::where('batch_id', $b->id)->pluck('id');
            $batchSubmissions = HomeworkSubmission::whereIn('homework_id', $batchHomeworkIds)
                ->whereNotNull('score')
                ->get();

            $batchAvg = $batchSubmissions->avg('score');
            if ($batchAvg > 0 && $batchAvg <= 10) {
                $batchAvg = $batchAvg * 10;
            }

            $batchesData[] = (object) [
                'name' => $b->name,
                'avg_score' => $batchAvg ? round($batchAvg, 2) . '%' : '0%',
                'students_count' => $students->count()
            ];
        }

        $data = [
            'institute' => $institute,
            'batch' => $batch,
            'batchesData' => $batchesData,
            'month' => $request->month ?: 'All',
            'year' => $request->year ?: 'All',
        ];

        $pdf = Pdf::loadView('institute.reports.performance_pdf', $data);
        return $pdf->download("Performance_Report_{$batch->name}.pdf");
    }

    public function exportFeeReport(Request $request)
    {
        $request->validate([
            'batch_id' => 'nullable|exists:batches,id',
            'month' => ['nullable', Rule::enum(Month::class)],
            'year' => ['nullable', Rule::enum(Year::class)],
        ]);

        $institute = $request->user();

        $batchesQuery = Batch::where('institute_id', $institute->id);
        if ($request->filled('batch_id')) {
            $batchesQuery->where('id', $request->batch_id);
            $batch = Batch::find($request->batch_id);
        } else {
            $batch = (object) ['name' => 'All Batches'];
        }

        $batches = $batchesQuery->get();
        $batchesData = [];

        foreach ($batches as $b) {
            $students = Student::where('batch_id', $b->id)->get();
            $batch_fee_query = Fee::where('institute_id', $institute->id)
                ->whereHas('student', function ($q) use ($b) {
                    $q->where('batch_id', $b->id);
                });

            if ($request->filled('month')) {
                $batch_fee_query->where('month', $request->month);
            }
            if ($request->filled('year')) {
                $batch_fee_query->where('year', $request->year);
            }

            $batch_fees = $batch_fee_query->get();

            $batchesData[] = (object) [
                'name' => $b->name,
                'fees' => $b->fees,
                'total_collected' => $batch_fees->sum('paid_amount'),
                'total_due' => $batch_fees->sum('total_amount') - $batch_fees->sum('paid_amount'),
                'students_count' => $students->count()
            ];
        }

        $data = [
            'institute' => $institute,
            'batch' => $batch,
            'batchesData' => $batchesData,
            'month' => $request->month ?: 'All',
            'year' => $request->year ?: 'All',
        ];

        $pdf = Pdf::loadView('institute.reports.fee_pdf', $data);
        return $pdf->download("Fee_Report_{$batch->name}.pdf");
    }

    public function exportAttendanceReport(Request $request)
    {
        $request->validate([
            'batch_id' => 'nullable|exists:batches,id',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer',
        ]);

        $institute = $request->user();

        if ($request->filled('batch_id')) {
            $batch = Batch::find($request->batch_id);
            $query = Attendance::with('student:id,name,enrollment_id,batch_id')->where('batch_id', $request->batch_id);
            if ($request->filled('month')) {
                $query->whereMonth('date', $request->month);
            }
            if ($request->filled('year')) {
                $query->whereYear('date', $request->year);
            }
            $attendance = $query->orderBy('date', 'desc')->get();

            $data = [
                'institute' => $institute,
                'batch' => $batch,
                'attendance' => $attendance,
                'month_name' => $request->filled('month') ? date('F', mktime(0, 0, 0, $request->month, 10)) : 'All',
                'year' => $request->year ?: 'All',
            ];
        } else {
            $batch = (object) ['name' => 'All Batches'];
            $batches = Batch::where('institute_id', $institute->id)->get();
            $batchesData = [];

            foreach ($batches as $b) {
                $students = Student::where('batch_id', $b->id)->get();
                $batch_att_query = Attendance::where('batch_id', $b->id);

                if ($request->filled('month')) {
                    $batch_att_query->whereMonth('date', $request->month);
                }
                if ($request->filled('year')) {
                    $batch_att_query->whereYear('date', $request->year);
                }

                $batch_attendance = $batch_att_query->get();
                $total_records = $batch_attendance->count();
                $present_records = $batch_attendance->filter(fn($att) => strtolower($att->status) === 'present')->count();
                $avg_percentage = $total_records > 0 ? round(($present_records / $total_records) * 100, 2) : 0;

                $batchesData[] = (object) [
                    'name' => $b->name,
                    'avg_attendance' => $avg_percentage . '%',
                    'students_count' => $students->count()
                ];
            }

            $data = [
                'institute' => $institute,
                'batch' => $batch,
                'batchesData' => $batchesData,
                'month_name' => $request->filled('month') ? date('F', mktime(0, 0, 0, $request->month, 10)) : 'All',
                'year' => $request->year ?: 'All',
            ];
        }

        $pdf = Pdf::loadView('institute.reports.attendance_pdf', $data);
        return $pdf->download("Attendance_Report_{$batch->name}.pdf");
    }

    public function studentReport(Request $request)
    {
        $institute = $request->user() ?: auth('institute')->user();
        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // If batch_id is requested to fetch students
        if ($request->filled('batch_id') && !$request->filled('student_id')) {
            $students = Student::where('institute_id', $institute->id)
                ->where('batch_id', $request->batch_id)
                ->orderBy('name')
                ->get(['id', 'name', 'enrollment_id', 'batch_id', 'standard', 'phone', 'email', 'guardian_name', 'dob', 'address_line_1', 'city', 'monthly_fee', 'created_at']);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'items' => $students,
                ]
            ]);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::where('institute_id', $institute->id)->find($request->student_id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found or unauthorized'
            ], 404);
        }

        $data = $this->getStudentReportData($student);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function exportStudentReport(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $institute = $request->user() ?: auth('institute')->user();
        if (!$institute) {
            return redirect()->route('institute.login')->with('error', 'Please login to export report.');
        }

        $student = Student::where('institute_id', $institute->id)->find($request->student_id);

        if (!$student) {
            return back()->with('error', 'Student not found or unauthorized.');
        }

        $pdfOutput = \App\Services\StudentReportService::buildPdf($student, $institute);
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $student->name);
        $fileName = "Student_Report_{$safeName}_{$student->id}.pdf";

        // Automatically email generated PDF report to student if email is present
        \App\Services\StudentReportService::emailReport($student, $institute);

        return response()->streamDownload(function () use ($pdfOutput) {
            echo $pdfOutput;
        }, $fileName, ['Content-Type' => 'application/pdf']);
    }

    public function emailStudentReport(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $institute = $request->user() ?: auth('institute')->user();
        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = Student::where('institute_id', $institute->id)->find($request->student_id);

        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Student not found or unauthorized.'], 404);
        }

        if (empty($student->email)) {
            return response()->json(['status' => 'error', 'message' => 'Student does not have a registered email address.'], 422);
        }

        $sent = \App\Services\StudentReportService::emailReport($student, $institute);

        if ($sent) {
            return response()->json([
                'status' => 'success',
                'message' => "Academic report PDF has been emailed successfully to {$student->email}."
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Failed to send email to {$student->email}."
            ], 500);
        }
    }

    private function getStudentReportData(Student $student)
    {
        return \App\Services\StudentReportService::getData($student);
    }
}
