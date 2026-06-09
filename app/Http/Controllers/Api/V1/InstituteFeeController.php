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
                $q->select('id', 'name', 'email', 'batch_id', 'monthly_fee', 'profile_image', 'enrollment_id');
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
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('enrollment_id', 'like', $searchTerm);
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

        $totalCollected = Fee::where('institute_id', $request->user()->id)
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
                'total_collected' => $totalCollected,
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
            'total_amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'status' => 'nullable|string|in:Paid,Partial,Unpaid',
            'payment_method' => 'nullable|string|in:Cash,Online',
        ], [
            'student_id.required' => 'Please select a student.',
            'student_id.exists'   => 'The selected student does not exist.',
            'total_amount.required' => 'Please enter the fee amount.',
            'total_amount.numeric'  => 'Amount must be a valid number.',
            'total_amount.min'      => 'Amount must be greater than zero.',
            'date.required' => 'Please select a fee date.',
            'date.date'     => 'Please enter a valid date.',
        ]);

        // Ensure the student belongs to this institute and the amount does not exceed pending fees
        $student = \App\Models\Student::where('id', $request->student_id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'The selected student does not belong to your institute.',
            ], 422);
        }

        $totalPaid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
        $pending   = ($student->monthly_fee ?? 0) - $totalPaid;

        if ($pending <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'This student has no pending fees.',
            ], 422);
        }

        if ($request->total_amount > $pending) {
            return response()->json([
                'status' => 'error',
                'message' => 'Amount cannot be greater than the pending fees of ₹' . number_format($pending) . '.',
            ], 422);
        }

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

            // Send invoice email to the student
            try {
                $student = \App\Models\Student::find($request->student_id);
                $institute = $request->user();
                if ($student && $student->email) {
                    $invoiceNo = "INV-" . \Carbon\Carbon::parse($fee->date)->format('Ymd') . "-" . str_pad($fee->id, 4, '0', STR_PAD_LEFT);
                    $invoiceDate = \Carbon\Carbon::parse($fee->date)->format('d M, Y');
                    $dueDate = \Carbon\Carbon::parse($fee->date)->addDays(10)->format('d M, Y');
                    $paymentUrl = url("/institute/fees/receipts/" . $fee->id);

                    \Illuminate\Support\Facades\Mail::to($student->email)->send(new \App\Mail\FeeInvoiceMail(
                        $student->name,
                        $student->email,
                        $invoiceNo,
                        $invoiceDate,
                        $dueDate,
                        $fee->status,
                        "Monthly Academic Fee",
                        $fee->total_amount,
                        "",
                        0,
                        0,
                        $fee->total_amount,
                        $paymentUrl,
                        $institute->institute_name,
                        $institute->logo
                    ));
                }
            } catch (\Exception $e) {
                \Log::error("Failed to send Fee Invoice email: " . $e->getMessage());
            }
            
            // Load student and payments relations for the frontend
            $fee->load(['student:id,name,profile_image,enrollment_id', 'payments']);

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
            ->with('student:id,name,enrollment_id');

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

    /**
     * Display a single fee receipt details for the API.
     */
    public function showReceipt(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $fee = Fee::where('institute_id', $request->user()->id)
            ->with(['student.batch', 'institute', 'payments.receipt'])
            ->find($id);

        if (!$fee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fee record not found.'
            ], 404);
        }

        $student = $fee->student;
        $institute = $fee->institute;
        
        $payment = $fee->payments()->latest()->first();
        if (!$payment) {
            $payment = new \App\Models\Payment([
                'fee_id' => $fee->id,
                'student_id' => $student ? $student->id : 0,
                'amount' => 0,
                'payment_method' => 'Pending',
                'paid_at' => null,
            ]);
        }

        $receipt = $payment->id ? \App\Models\Receipt::where('payment_id', $payment->id)->first() : null;
        if (!$receipt) {
            $receipt = new \App\Models\Receipt([
                'payment_id' => $payment->id ?: 0,
                'receipt_number' => 'REC-' . date('Ymd', strtotime($fee->date)) . '-' . str_pad($fee->id, 4, '0', STR_PAD_LEFT),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'fee' => [
                    'id' => $fee->id,
                    'total_amount' => (float) $fee->total_amount,
                    'paid_amount' => (float) $fee->paid_amount,
                    'due_amount' => (float) ($fee->total_amount - $fee->paid_amount),
                    'status' => $fee->status,
                    'date' => $fee->date,
                    'month' => $fee->month,
                    'year' => $fee->year,
                ],
                'student' => $student ? [
                    'id' => $student->id,
                    'name' => $student->name,
                    'enrollment_id' => $student->enrollment_id ?? 'N/A',
                    'standard' => $student->standard ?? 'N/A',
                    'batch_name' => $student->batch ? $student->batch->batch_name : 'N/A',
                    'email' => $student->email,
                ] : null,
                'institute' => $institute ? [
                    'id' => $institute->id,
                    'institute_name' => $institute->institute_name,
                    'logo_url' => $institute->logo_url,
                    'logo' => $institute->logo,
                    'address' => $institute->address ?? 'Main Campus, India',
                    'email' => $institute->email,
                    'phone' => $institute->phone ?? 'Support Contact',
                ] : null,
                'payment' => [
                    'id' => $payment->id,
                    'amount' => (float) $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'transaction_id' => $payment->transaction_id ?? 'N/A',
                    'paid_at' => $payment->paid_at,
                ],
                'receipt' => [
                    'id' => $receipt->id,
                    'receipt_number' => $receipt->receipt_number,
                ]
            ]
        ]);
    }
}
