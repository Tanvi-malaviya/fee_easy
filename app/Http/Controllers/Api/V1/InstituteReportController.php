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
        if ($globalAvg > 0 && $globalAvg <= 10) { $globalAvg = $globalAvg * 10; }
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
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
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
                if ($request->filled('month')) { $logsForStudent->whereMonth('date', $request->month); }
                if ($request->filled('year')) { $logsForStudent->whereYear('date', $request->year); }
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
        if (!$score) return 'N/A';
        if ($score >= 90) return 'A+';
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B+';
        if ($score >= 60) return 'B';
        if ($score >= 50) return 'C';
        if ($score >= 35) return 'D';
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
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $institute = $request->user() ?: auth('institute')->user();
        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

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

        $reportData = $this->getStudentReportData($student);

        $data = [
            'institute' => $institute,
            'student' => $reportData['student'],
            'financial' => $reportData['financial'],
            'attendance' => $reportData['attendance'],
            'exams' => $reportData['exams'],
            'homework' => $reportData['homework'],
        ];

        $pdf = Pdf::loadView('institute.reports.student_pdf', $data);
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $student->name);
        return $pdf->download("Student_Report_{$safeName}_{$student->id}.pdf");
    }

    private function getStudentReportData(Student $student)
    {
        $student->loadMissing([
            'batch',
            'parent',
            'fees.payments',
            'attendance.batch',
            'homeworkSubmissions.homework',
            'examMarks.exam'
        ]);

        // Calculate balance (Monthly Fee - Total Payments)
        $totalPaid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
        $balance = max(0, ($student->monthly_fee ?? 0) - $totalPaid);

        // Fee status calculation
        $feeStatus = 'Full Paid';
        if (!$student->monthly_fee || $student->monthly_fee == 0) {
            $feeStatus = 'No Fee';
        } elseif ($balance >= $student->monthly_fee) {
            $feeStatus = 'Pending';
        } elseif ($balance > 0) {
            $feeStatus = 'Partial Dues';
        }

        // Attendance stats & records
        $totalDays = $student->attendance()->count();
        $presentDays = $student->attendance()->where('status', 'Present')->count();
        $absentDays = $student->attendance()->where('status', 'Absent')->count();
        $lateDays = $student->attendance()->where('status', 'Late')->count();
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
        $attendanceRecords = $student->attendance()->with('batch')->orderBy('date', 'desc')->get()->map(function($att) {
            return [
                'id' => $att->id,
                'date' => $att->date,
                'formatted_date' => \Carbon\Carbon::parse($att->date)->format('M d, Y'),
                'day' => \Carbon\Carbon::parse($att->date)->format('l'),
                'batch_name' => $att->batch ? $att->batch->name : 'N/A',
                'status' => $att->status,
            ];
        });

        // Homework stats & submissions
        $homeworkSubmissions = $student->homeworkSubmissions()->with('homework')->latest()->get();
        $averageGrade = $homeworkSubmissions->whereNotNull('score')->avg('score');
        $averageGrade = $averageGrade ? round($averageGrade, 1) : 0;
        $submittedHomeworkCount = $homeworkSubmissions->whereIn('status', ['Submitted', 'Reviewed'])->count();

        $homeworkList = $homeworkSubmissions->map(function($sub) use ($student) {
            $hw = $sub->homework;
            return [
                'id' => $sub->id,
                'homework_id' => $sub->homework_id,
                'title' => $hw ? $hw->title : 'Homework #' . $sub->homework_id,
                'subject' => ($hw && $hw->subject) ? $hw->subject : ($student->batch ? $student->batch->subject : 'General'),
                'due_date' => ($hw && $hw->due_date) ? \Carbon\Carbon::parse($hw->due_date)->format('M d, Y') : '—',
                'status' => $sub->status ?: 'Submitted',
                'score' => !is_null($sub->score) ? (float)$sub->score : null,
                'feedback' => $sub->feedback ?: '—',
            ];
        });

        // Exams & Marks stats
        $examMarks = $student->examMarks()->with('exam')->get()->sortByDesc(function ($mark) {
            return $mark->exam->date ?? $mark->created_at;
        });
        $totalExams = $examMarks->count();
        $passedExams = $examMarks->filter(function ($mark) {
            return !$mark->is_absent && $mark->exam && $mark->marks_obtained >= ($mark->exam->passing_marks ?? 0);
        })->count();
        $failedExams = $examMarks->filter(function ($mark) {
            return !$mark->is_absent && $mark->exam && $mark->marks_obtained < ($mark->exam->passing_marks ?? 0);
        })->count();
        $absentExams = $examMarks->where('is_absent', true)->count();
        $averageExamMarks = $examMarks->where('is_absent', false)->count() > 0 
            ? round($examMarks->where('is_absent', false)->avg('marks_obtained'), 1) 
            : 0;

        $examList = $examMarks->values()->map(function($mark) use ($student) {
            $exam = $mark->exam;
            $passingMarks = $exam ? (float)$exam->passing_marks : 0;
            $totalMarks = $exam ? (float)$exam->total_marks : 100;
            $scored = $mark->is_absent ? 0 : (float)$mark->marks_obtained;
            $percentage = $totalMarks > 0 ? round(($scored / $totalMarks) * 100) : 0;
            $isPassed = !$mark->is_absent && $scored >= $passingMarks;

            return [
                'id' => $mark->id,
                'exam_id' => $mark->exam_id,
                'title' => $exam ? $exam->title : 'Exam #' . $mark->exam_id,
                'subject' => ($exam && $exam->subject) ? $exam->subject : ($student->batch ? $student->batch->subject : 'General'),
                'date' => ($exam && $exam->date) ? \Carbon\Carbon::parse($exam->date)->format('M d, Y') : $mark->created_at->format('M d, Y'),
                'marks_scored' => $scored,
                'total_marks' => $totalMarks,
                'passing_marks' => $passingMarks,
                'percentage' => $percentage,
                'is_absent' => (bool)$mark->is_absent,
                'is_passed' => $isPassed,
                'remarks' => $mark->remarks ?: '—',
            ];
        });

        // Fee History
        $feeList = $student->fees->sortByDesc('date')->values()->map(function($fee) {
            $remaining = max(0, $fee->total_amount - $fee->paid_amount);
            return [
                'id' => $fee->id,
                'month_year' => \Carbon\Carbon::parse($fee->date)->format('M Y'),
                'total_amount' => (float)$fee->total_amount,
                'paid_amount' => (float)$fee->paid_amount,
                'remaining' => (float)$remaining,
                'status' => $fee->status ?: ($remaining == 0 ? 'Paid' : 'Unpaid'),
                'receipt_url' => route('institute.fees.receipts.show', $fee->id),
            ];
        });

        return [
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'enrollment_id' => $student->enrollment_id,
                'email' => $student->email,
                'phone' => $student->phone,
                'standard' => $student->standard,
                'monthly_fee' => (float)($student->monthly_fee ?? 0),
                'guardian_name' => $student->guardian_name ?: 'N/A',
                'dob' => $student->dob ? \Carbon\Carbon::parse($student->dob)->format('M d, Y') : 'N/A',
                'admission_date' => $student->created_at->format('M d, Y'),
                'profile_image_url' => $student->profile_image_url,
                'address' => trim(implode(', ', array_filter([
                    $student->address_line_1,
                    $student->address_line_2,
                    $student->city,
                    $student->state,
                    $student->pincode ? "- {$student->pincode}" : null
                ]))) ?: 'N/A',
                'batch_id' => $student->batch_id,
                'batch_name' => $student->batch ? $student->batch->name : 'Unassigned',
                'profile_url' => route('institute.students.show', $student->id),
            ],
            'financial' => [
                'balance' => $balance,
                'total_paid' => $totalPaid,
                'monthly_fee' => (float)($student->monthly_fee ?? 0),
                'fee_status' => $feeStatus,
                'fees_history' => $feeList,
            ],
            'attendance' => [
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'late_days' => $lateDays,
                'percentage' => $attendancePercentage,
                'records' => $attendanceRecords,
            ],
            'exams' => [
                'total_exams' => $totalExams,
                'passed_exams' => $passedExams,
                'failed_exams' => $failedExams,
                'absent_exams' => $absentExams,
                'average_score' => $averageExamMarks,
                'list' => $examList,
            ],
            'homework' => [
                'total_submissions' => $homeworkSubmissions->count(),
                'submitted_count' => $submittedHomeworkCount,
                'average_grade' => $averageGrade,
                'list' => $homeworkList,
            ]
        ];
    }
}
