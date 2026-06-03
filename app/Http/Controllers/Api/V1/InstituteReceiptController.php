<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Receipt;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InstituteReceiptController extends Controller
{
    /**
     * Get all receipts for a specific student belonging to the institute.
     */
    public function getStudentReceipts(Request $request, $student_id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $receipts = Receipt::whereHas('payment', function ($query) use ($request, $student_id) {
                $query->where('student_id', $student_id)
                      ->whereHas('fee', function ($q) use ($request) {
                          $q->where('institute_id', $request->user()->id);
                      });
            })
            ->with(['payment:id,amount,payment_method,paid_at,fee_id', 'payment.fee:id,month,year'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $receipts
        ]);
    }

    /**
     * Download a specific receipt as PDF. Supports both Fee ID and Receipt ID.
     */
    public function downloadReceipt(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Try to find Fee by ID first (compatible with web panel's behavior)
        $fee = \App\Models\Fee::where('institute_id', $request->user()->id)
            ->with(['student.batch', 'institute', 'payments.receipt'])
            ->find($id);

        if ($fee) {
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
            $receipt = $payment->id ? Receipt::where('payment_id', $payment->id)->first() : null;
            if (!$receipt) {
                $receipt = new Receipt([
                    'payment_id' => $payment->id ?: 0,
                    'receipt_number' => 'REC-' . date('Ymd', strtotime($fee->date)) . '-' . str_pad($fee->id, 4, '0', STR_PAD_LEFT),
                ]);
            }
        } else {
            // Fallback: search by Receipt ID
            $receipt = Receipt::with(['payment.fee', 'payment.student', 'payment.fee.institute'])->find($id);
            
            if (!$receipt || $receipt->payment->fee->institute_id !== $request->user()->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Receipt not found or unauthorized'
                ], 404);
            }
            
            $payment = $receipt->payment;
            $fee = $payment->fee;
            $student = $payment->student;
            $institute = $fee->institute;
        }

        $data = [
            'receipt' => $receipt,
            'payment' => $payment,
            'fee' => $fee,
            'student' => $student,
            'institute' => $institute,
        ];

        $pdf = Pdf::loadView('pdf.receipt', $data);
        
        $filename = 'Receipt-' . $receipt->receipt_number . '.pdf';

        return $pdf->download($filename);
    }
}
