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

        $query = Fee::where('institute_id', $request->user()->id)
            ->with(['student' => function($q) {
                $q->select('id', 'name', 'email', 'batch_id', 'monthly_fee');
            }, 'student.batch', 'payments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('batch_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('batch_id', $request->batch_id);
            });
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->whereHas('student', function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm);
            });
        }

        $paginator = $query->latest()->paginate(12);

        $paginator->getCollection()->transform(function($fee) {
            if ($fee->student) {
                $fee->student->total_paid = \App\Models\Payment::where('student_id', $fee->student_id)->sum('amount');
                $fee->student->total_due = ($fee->student->monthly_fee ?? 0) - $fee->student->total_paid;
            }
            return $fee;
        });


        $currentMonthTotal = Fee::where('institute_id', $request->user()->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
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
            'date' => 'required|date',
            'status' => 'nullable|string|in:Paid,Partial,Unpaid',
            'payment_method' => 'nullable|string|in:Cash,Online',
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
                'date' => $request->date,
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
            
            // Load student and payments relations for the frontend
            $fee->load(['student:id,name', 'payments']);

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
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
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

        $month = now()->format('F');
        $year = now()->format('Y');

        if ($request->filled('date')) {
            $carbonDate = \Carbon\Carbon::parse($request->date);
            $month = $carbonDate->format('F');
            $year = $carbonDate->format('Y');
        }

        $data = [
            'institute' => $institute,
            'fees' => $fees,
            'date' => $request->input('date', 'All'),
            'month' => $month,
            'year' => $year,
            'student' => $request->filled('student_id') ? \App\Models\Student::find($request->student_id)->name : 'All',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('institute.fees.report_pdf', $data);

        $filename = "Fee_Report_" . now()->format('Y-m-d_His') . ".pdf";
        return $pdf->download($filename);
    }
}
