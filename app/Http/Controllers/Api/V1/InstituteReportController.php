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
        $dueFees = $totalFees - $paidFees;

        return response()->json([
            'status' => 'success',
            'data' => [
                'students_count' => $institute->students()->count(),
                'batches_count' => $institute->batches()->count(),
                'total_fees' => $totalFees,
                'total_paid_fees' => $paidFees,
                'total_due_fees' => $dueFees,
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

        $query = Fee::with('student')->where('institute_id', $institute_id);

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

        $summary = [
            'total_amount' => $fees->sum('total_amount'),
            'paid_amount' => $fees->sum('paid_amount'),
            'due_amount' => $fees->sum('total_amount') - $fees->sum('paid_amount'),
            'count' => $fees->count(),
        ];

        $batchesQuery = Batch::where('institute_id', $institute_id);
        if ($request->filled('batch_id')) {
            $batchesQuery->where('id', $request->batch_id);
        }
        $batches = $batchesQuery->get();
        $batchesData = [];

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

            $batchesData[] = [
                'batch_id' => $batch->id,
                'batch_name' => $batch->name,
                'batch_fees' => $batch->fees,
                'total_collected' => $batch_fees->sum('paid_amount'),
                'total_due' => $batch_fees->sum('total_amount') - $batch_fees->sum('paid_amount'),
                'students_count' => $students->count()
            ];
        }

        $responseData = [
            'summary' => $summary,
            'batches' => $batchesData
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

        $query = Attendance::with('student');

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

        if ($request->filled('batch_id')) {
            $mappedAttendance = $attendance->map(function($att) use ($request) {
                $student_attendance_query = Attendance::where('student_id', $att->student_id)
                    ->where('status', 'present');
                
                if ($request->filled('month')) { $student_attendance_query->whereMonth('date', $request->month); }
                if ($request->filled('year')) { $student_attendance_query->whereYear('date', $request->year); }

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
        }

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

        $summary = [
            'average_performance' => $globalAvg ? round($globalAvg, 2) . '%' : '0%',
        ];

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

        $responseData = [
            'summary' => $summary,
            'batches' => $batchesData
        ];

        if ($request->filled('batch_id')) {
            $studentsData = [];
            $batch_id = $request->batch_id;
            $batchStudents = Student::where('batch_id', $batch_id)->get();

            foreach ($batchStudents as $stu) {
                $stuHomeworkIds = Homework::where('batch_id', $batch_id)->pluck('id');
                $stuSubmissions = HomeworkSubmission::whereIn('homework_id', $stuHomeworkIds)
                    ->where('student_id', $stu->id)
                    ->whereNotNull('score')
                    ->get();
                
                $stuAvg = $stuSubmissions->avg('score');
                if ($stuAvg > 0 && $stuAvg <= 10) {
                    $stuAvg = $stuAvg * 10;
                }

                $studentsData[] = [
                    'student_id' => $stu->id,
                    'student_name' => $stu->name,
                    'avg_score' => $stuAvg ? round($stuAvg, 2) . '%' : '0%'
                ];
            }
            $responseData['students'] = $studentsData;
        }

        return response()->json([
            'status' => 'success',
            'data' => $responseData,
        ]);
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
                ->whereHas('student', function($q) use ($b) {
                    $q->where('batch_id', $b->id);
                });

            if ($request->filled('month')) { $batch_fee_query->where('month', $request->month); }
            if ($request->filled('year')) { $batch_fee_query->where('year', $request->year); }

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
            'batch_id' => 'required|exists:batches,id',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer',
        ]);

        $institute = $request->user();
        $batch = Batch::find($request->batch_id);

        $query = Attendance::with('student')->where('batch_id', $request->batch_id);
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

        $pdf = Pdf::loadView('institute.reports.attendance_pdf', $data);
        return $pdf->download("Attendance_Report_{$batch->name}.pdf");
    }
}
