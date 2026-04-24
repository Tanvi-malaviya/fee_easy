<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Institute;
use Illuminate\Http\Request;

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
            'month' => 'required|string',
            'year' => 'required|integer',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $paidAmount = $request->input('paid_amount', 0);
            $totalAmount = $request->total_amount;
            $dueAmount = $totalAmount - $paidAmount;
            $status = $dueAmount <= 0 ? 'Paid' : ($paidAmount > 0 ? 'Partial' : 'Unpaid');

            $fee = Fee::create([
                'institute_id' => $request->user()->id,
                'student_id' => $request->student_id,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
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
     * Export current month's fees as PDF.
     */
    public function export(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $institute = $request->user();
        $month = now()->format('F');
        $year = now()->format('Y');

        $fees = Fee::where('institute_id', $institute->id)
            ->where('month', $month)
            ->where('year', $year)
            ->with('student:id,name')
            ->latest()
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('institute.fees.report_pdf', [
            'institute' => $institute,
            'fees' => $fees,
            'month' => $month,
            'year' => $year
        ]);

        $filename = "Fee_Report_{$month}_{$year}.pdf";
        return $pdf->download($filename);
    }
}
