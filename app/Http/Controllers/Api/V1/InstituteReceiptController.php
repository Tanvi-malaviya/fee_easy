<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
     * Download a specific receipt as PDF.
     */
    public function downloadReceipt(Request $request, $id)
    {
        $receipt = Receipt::with(['payment.fee', 'payment.student', 'payment.fee.institute'])->find($id);

        if (!$receipt || $receipt->payment->fee->institute_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Receipt not found or unauthorized'
            ], 404);
        }

        $data = [
            'receipt' => $receipt,
            'payment' => $receipt->payment,
            'fee' => $receipt->payment->fee,
            'student' => $receipt->payment->student,
            'institute' => $receipt->payment->fee->institute,
        ];

        $pdf = Pdf::loadView('pdf.receipt', $data);
        
        $filename = 'Receipt-' . $receipt->receipt_number . '.pdf';

        return $pdf->download($filename);
    }
}
