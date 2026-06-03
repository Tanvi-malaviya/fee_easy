<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentReceiptsController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student  = $request->user();
        $institute = $student->institute;

        $receipts = Receipt::whereHas('payment', function ($query) use ($request) {
                $query->where('student_id', $request->user()->id);
            })
            ->with(['payment' => function($q) {
                $q->select('id', 'student_id', 'fee_id', 'amount', 'payment_method', 'transaction_id', 'paid_at');
                $q->with('fee:id,month,year,date');
            }])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($receipt) use ($student, $institute) {
                $date = \Carbon\Carbon::parse($receipt->created_at);
                return [
                    'id'             => $receipt->id,
                    'receipt_number' => $receipt->receipt_number,
                    'amount'         => $receipt->payment->amount ?? 0,
                    'payment_method' => $receipt->payment->payment_method ?? 'Cash',
                    'date'           => $date->format('j M Y'),
                    'student_name'   => $student->name,
                    'roll_no'        => $student->id,
                    'institute_name' => $institute->institute_name ?? $institute->name ?? null,
                ];
            });

        // Add dummy data for demonstration if empty but student has payments
        if ($receipts->isEmpty()) {
            $payments = \App\Models\Payment::with('fee')
                ->where('student_id', $request->user()->id)
                ->orderByDesc('paid_at')
                ->get();
                
            if ($payments->isNotEmpty()) {
                $receipts = $payments->map(function($payment) use ($student, $institute) {
                    $date = \Carbon\Carbon::parse($payment->paid_at ?? $payment->created_at);
                    return [
                        'id'             => $payment->id,
                        'receipt_number' => 'RE-DEMO-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT),
                        'amount'         => $payment->amount,
                        'payment_method' => $payment->payment_method ?? 'Cash',
                        'date'           => $date->format('j M Y'),
                        'student_name'   => $student->name,
                        'roll_no'        => $student->id,
                        'institute_name' => $institute->institute_name ?? $institute->name ?? null,
                    ];
                });
            } else {
                // If no payments, check if there are fees with paid_amount > 0
                $fees = \App\Models\Fee::where('student_id', $request->user()->id)
                    ->where('paid_amount', '>', 0)
                    ->orderByDesc('date')
                    ->get();
                    
                if ($fees->isNotEmpty()) {
                    $receipts = $fees->map(function($fee) use ($student, $institute) {
                        $date = \Carbon\Carbon::parse($fee->updated_at ?? $fee->date);
                        return [
                            'id'             => $fee->id,
                            'receipt_number' => 'RE-FEE-' . str_pad($fee->id, 4, '0', STR_PAD_LEFT),
                            'amount'         => $fee->paid_amount,
                            'payment_method' => 'Cash',
                            'date'           => $date->format('j M Y'),
                            'student_name'   => $student->name,
                            'roll_no'        => $student->id,
                            'institute_name' => $institute->institute_name ?? $institute->name ?? null,
                        ];
                    });
                } else {
                    $receipts = collect([]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $receipts,
        ]);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student  = $request->user();
        $institute = $student->institute;

        // Try to fetch actual receipt first
        $receipt = Receipt::whereHas('payment', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->with(['payment' => function($q) {
                $q->select('id', 'student_id', 'fee_id', 'amount', 'payment_method', 'transaction_id', 'paid_at');
                $q->with('fee:id,month,year,date');
            }])
            ->find($id);

        if ($receipt) {
            $date = \Carbon\Carbon::parse($receipt->created_at);
            $data = [
                'id'             => $receipt->id,
                'receipt_number' => $receipt->receipt_number,
                'amount'         => $receipt->payment->amount ?? 0,
                'payment_method' => $receipt->payment->payment_method ?? 'Cash',
                'date'           => $date->format('j M Y'),
                'student_name'   => $student->name,
                'roll_no'        => $student->id,
                'institute_name' => $institute->institute_name ?? $institute->name ?? null,
            ];
            return response()->json(['status' => 'success', 'data' => $data]);
        }

        // Fallback to payment-based receipt if the ID is standard payment ID or starting with 'demo-'
        $paymentId = str_replace('demo-', '', $id);
        $payment = \App\Models\Payment::where('student_id', $student->id)->find($paymentId);

        if ($payment) {
            $date = \Carbon\Carbon::parse($payment->paid_at ?? $payment->created_at);
            $data = [
                'id'             => $payment->id,
                'receipt_number' => 'RE-DEMO-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT),
                'amount'         => $payment->amount,
                'payment_method' => $payment->payment_method ?? 'Cash',
                'date'           => $date->format('j M Y'),
                'student_name'   => $student->name,
                'roll_no'        => $student->id,
                'institute_name' => $institute->institute_name ?? $institute->name ?? null,
            ];
            return response()->json(['status' => 'success', 'data' => $data]);
        }

        // Fallback to fee-based receipt
        $feeId = str_replace('fee-', '', $id);
        $fee = \App\Models\Fee::where('student_id', $student->id)->find($feeId);

        if ($fee && $fee->paid_amount > 0) {
            $date = \Carbon\Carbon::parse($fee->updated_at ?? $fee->date);
            $data = [
                'id'             => $fee->id,
                'receipt_number' => 'RE-FEE-' . str_pad($fee->id, 4, '0', STR_PAD_LEFT),
                'amount'         => $fee->paid_amount,
                'payment_method' => 'Cash',
                'date'           => $date->format('j M Y'),
                'student_name'   => $student->name,
                'roll_no'        => $student->id,
                'institute_name' => $institute->institute_name ?? $institute->name ?? null,
            ];
            return response()->json(['status' => 'success', 'data' => $data]);
        }

        return response()->json(['status' => 'error', 'message' => 'Receipt not found'], 404);
    }

    public function download(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();

        // For demo fallback from payments
        if (str_starts_with($id, 'demo-')) {
            $paymentId = str_replace('demo-', '', $id);
            $payment = \App\Models\Payment::with('fee.institute')->find($paymentId);
            
            if ($payment) {
                $receipt = new Receipt([
                    'payment_id' => $payment->id,
                    'receipt_number' => 'RE-DEMO-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT),
                ]);
                $data = [
                    'receipt' => $receipt,
                    'payment' => $payment,
                    'fee' => $payment->fee,
                    'student' => $student,
                    'institute' => $payment->fee->institute ?? $student->institute,
                ];
                
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', $data);
                return $pdf->download('Receipt-' . $receipt->receipt_number . '.pdf');
            }
        }

        // For demo fallback from fees
        if (str_starts_with($id, 'fee-')) {
            $feeId = str_replace('fee-', '', $id);
            $fee = \App\Models\Fee::with('institute')->find($feeId);
            
            if ($fee) {
                $payment = $fee->payments()->latest()->first();
                if (!$payment) {
                    $payment = new \App\Models\Payment([
                        'fee_id' => $fee->id,
                        'student_id' => $student->id,
                        'amount' => $fee->paid_amount,
                        'payment_method' => 'Cash',
                        'paid_at' => $fee->updated_at ?? $fee->date,
                    ]);
                }
                $receipt = new Receipt([
                    'payment_id' => $payment->id ?: 0,
                    'receipt_number' => 'RE-FEE-' . str_pad($fee->id, 4, '0', STR_PAD_LEFT),
                ]);
                $data = [
                    'receipt' => $receipt,
                    'payment' => $payment,
                    'fee' => $fee,
                    'student' => $student,
                    'institute' => $fee->institute ?? $student->institute,
                ];
                
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', $data);
                return $pdf->download('Receipt-' . $receipt->receipt_number . '.pdf');
            }
        }

        // Standard lookup: Try to find by Receipt ID first
        $receipt = Receipt::with(['payment.fee.institute', 'payment.student'])
            ->whereHas('payment', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->find($id);

        if ($receipt) {
            $payment = $receipt->payment;
            $fee = $payment->fee;
            $institute = $fee->institute ?? $student->institute;
        } else {
            // Try to resolve by Fee ID for this student
            $fee = \App\Models\Fee::where('student_id', $student->id)
                ->with(['student.batch', 'institute', 'payments.receipt'])
                ->find($id);

            if (!$fee) {
                return response()->json(['status' => 'error', 'message' => 'Receipt/Fee not found'], 404);
            }

            $payment = $fee->payments()->latest()->first();
            if (!$payment) {
                $payment = new \App\Models\Payment([
                    'fee_id' => $fee->id,
                    'student_id' => $student->id,
                    'amount' => $fee->paid_amount,
                    'payment_method' => 'Cash',
                    'paid_at' => $fee->updated_at ?? $fee->date,
                ]);
            }
            $receipt = $payment->id ? Receipt::where('payment_id', $payment->id)->first() : null;
            if (!$receipt) {
                $receipt = new Receipt([
                    'payment_id' => $payment->id ?: 0,
                    'receipt_number' => 'REC-' . date('Ymd', strtotime($fee->date)) . '-' . str_pad($fee->id, 4, '0', STR_PAD_LEFT),
                ]);
            }
            $institute = $fee->institute ?? $student->institute;
        }

        $data = [
            'receipt' => $receipt,
            'payment' => $payment,
            'fee' => $fee,
            'student' => $student,
            'institute' => $institute,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', $data);
        return $pdf->download('Receipt-' . $receipt->receipt_number . '.pdf');
    }
}
