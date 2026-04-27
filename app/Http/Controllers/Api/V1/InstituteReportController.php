<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Institute;
use App\Models\SubscriptionPayment;
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
            'batch_id' => 'required|exists:batches,id',
            'month' => ['nullable', Rule::enum(Month::class)],
            'year' => ['nullable', Rule::enum(Year::class)],
        ]);

        $query = Fee::with('student')->where('institute_id', $request->user()->id);

        if ($request->filled('batch_id')) {
            $query->whereHas('student', function($q) use ($request) {
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

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => $summary,
                'fees' => $fees
            ],
        ]);
    }

    public function attendanceReport(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer',
        ]);

        $query = Attendance::with('student')->where('batch_id', $request->batch_id);

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $attendance = $query->orderBy('date', 'desc')->get();

        $summary = [
            'present' => $attendance->where('status', 'Present')->count(),
            'absent' => $attendance->where('status', 'Absent')->count(),
            'leave' => $attendance->where('status', 'Leave')->count(),
            'total' => $attendance->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => $summary,
                'attendance' => $attendance
            ],
        ]);
    }

    public function exportFeeReport(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'month' => ['nullable', Rule::enum(Month::class)],
            'year' => ['nullable', Rule::enum(Year::class)],
        ]);

        $institute = $request->user();
        $batch = Batch::find($request->batch_id);

        $query = Fee::with('student')->where('institute_id', $institute->id);
        $query->whereHas('student', function($q) use ($request) {
            $q->where('batch_id', $request->batch_id);
        });

        if ($request->filled('month')) { $query->where('month', $request->month); }
        if ($request->filled('year')) { $query->where('year', $request->year); }

        $fees = $query->latest()->get();

        $data = [
            'institute' => $institute,
            'batch' => $batch,
            'fees' => $fees,
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
        if ($request->filled('month')) { $query->whereMonth('date', $request->month); }
        if ($request->filled('year')) { $query->whereYear('date', $request->year); }

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
