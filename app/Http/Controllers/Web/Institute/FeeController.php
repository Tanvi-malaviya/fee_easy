<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        return view('institute.fees.index');
    }

    public function collect()
    {
        return view('institute.fees.collect');
    }

    public function showReceipt($id)
    {
        $fee = Fee::with(['student.batch', 'institute', 'payments.receipt'])->find($id);

        if (!$fee) {
            abort(404, 'Fee record not found.');
        }

        $student = $fee->student;
        $institute = $fee->institute;
        
        // Find the latest payment or create a fallback dummy payment if unpaid
        $payment = $fee->payments()->latest()->first();
        if (!$payment) {
            $payment = new Payment([
                'fee_id' => $fee->id,
                'student_id' => $student ? $student->id : 0,
                'amount' => 0,
                'payment_method' => 'Pending',
                'paid_at' => null,
            ]);
        }

        // Find or create a dynamic fallback receipt number
        $receipt = $payment->id ? Receipt::where('payment_id', $payment->id)->first() : null;
        if (!$receipt) {
            $receipt = new Receipt([
                'payment_id' => $payment->id ?: 0,
                'receipt_number' => 'REC-' . date('Ymd', strtotime($fee->date)) . '-' . str_pad($fee->id, 4, '0', STR_PAD_LEFT),
            ]);
        }

        return view('institute.fees.receipts.show', compact('fee', 'student', 'institute', 'payment', 'receipt'));
    }

    public function downloadReceipt($id)
    {
        $fee = Fee::with(['student.batch', 'institute', 'payments.receipt'])->find($id);

        if (!$fee) {
            abort(404, 'Fee record not found.');
        }

        $student = $fee->student;
        $institute = $fee->institute;
        
        $payment = $fee->payments()->latest()->first();
        if (!$payment) {
            $payment = new Payment([
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

        $data = compact('fee', 'student', 'institute', 'payment', 'receipt');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', $data);
        return $pdf->download('Receipt-' . $receipt->receipt_number . '.pdf');
    }
}
