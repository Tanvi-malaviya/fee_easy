<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Institute;
use Illuminate\Http\Request;
use App\Enums\Month;
use App\Enums\Year;
use Illuminate\Validation\Rules\Enum;

class InstituteFeeController extends Controller
{
    /**
     * Display a listing of all fees for the authenticated institute.
     */
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $paginator = Fee::where('institute_id', $request->user()->id)
            ->with('student:id,name,email')
            ->latest()
            ->paginate(10);

        $currentMonthTotal = Fee::where('institute_id', $request->user()->id)
            ->where('month', now()->format('F'))
            ->where('year', now()->format('Y'))
            ->sum('paid_amount');

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $paginator->items(),
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'current_month_total' => $currentMonthTotal,
            ]
        ]);
    }

    /**
     * Store a newly created fee for a student.
     */
    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'total_amount' => 'required|numeric|min:0',
            'month' => ['required', new Enum(Month::class)],
            'year' => ['required', new Enum(Year::class)],
            'status' => 'nullable|string|in:Paid,Partial,Unpaid',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $paidAmount = $request->input('paid_amount', 0);
            $totalAmount = $request->total_amount;
            
            // Auto-calculate status unless explicitly provided and valid
            $status = $request->input('status') ?: ($totalAmount - $paidAmount <= 0 ? 'Paid' : ($paidAmount > 0 ? 'Partial' : 'Unpaid'));

            $fee = Fee::create([
                'institute_id' => $request->user()->id,
                'student_id' => $request->student_id,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'status' => $status,
                'month' => $request->month,
                'year' => $request->year,
            ]);

            if ($paidAmount > 0) {
                \App\Models\Payment::create([
                    'fee_id' => $fee->id,
                    'student_id' => $request->student_id,
                    'amount' => $paidAmount,
                    'payment_method' => $request->input('payment_method', 'Cash'),
                    'paid_at' => now(),
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            
            // Load student relation for the frontend
            $fee->load('student:id,name');

            return response()->json([
                'status' => 'success',
                'message' => 'Fee record created and collected successfully',
                'data' => $fee
            ], 201);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display fees for a specific student.
     */
    public function getStudentFees(Request $request, $student_id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $fees = Fee::where('institute_id', $request->user()->id)
            ->where('student_id', $student_id)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $fees
        ]);
    }
    /**
     * Export fee records as PDF with optional filters.
     */
    public function export(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $institute = $request->user();
        
        $query = Fee::where('institute_id', $institute->id)
            ->with('student:id,name');

        // Apply Filters
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('batch_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('batch_id', $request->batch_id);
            });
        }

        $fees = $query->latest()->get();

        $data = [
            'institute' => $institute,
            'fees' => $fees,
            'month' => $request->input('month', 'All'),
            'year' => $request->input('year', 'All'),
            'student' => $request->filled('student_id') ? \App\Models\Student::find($request->student_id)->name : 'All',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('institute.fees.report_pdf', $data);

        $filename = "Fee_Report_" . now()->format('Y-m-d_His') . ".pdf";
        return $pdf->download($filename);
    }
}
