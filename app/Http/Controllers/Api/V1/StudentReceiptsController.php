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

        $receipts = Receipt::whereHas('payment', function ($query) use ($request) {
                $query->where('student_id', $request->user()->id);
            })
            ->with(['payment' => function($q) {
                $q->select('id', 'student_id', 'fee_id', 'amount', 'payment_method', 'transaction_id', 'paid_at');
                $q->with('fee:id,month,year,date');
            }])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($receipt) {
                $date = \Carbon\Carbon::parse($receipt->created_at);
                return [
                    'id' => $receipt->id,
                    'receipt_number' => $receipt->receipt_number,
                    'amount' => $receipt->payment->amount ?? 0,
                    'payment_method' => $receipt->payment->payment_method ?? 'Cash',
                    'date' => $date->format('j M Y'),
                    'time' => $date->format('g:i A'),
                    'month_year' => $receipt->payment->fee->month ?? $date->format('F'),
                    'download_url' => route('student.receipts.download', ['id' => $receipt->id]),
                ];
            });

        // Add dummy data for demonstration if empty but student has payments
        if ($receipts->isEmpty()) {
            $payments = \App\Models\Payment::with('fee')
                ->where('student_id', $request->user()->id)
                ->orderByDesc('paid_at')
                ->get();
                
            if ($payments->isNotEmpty()) {
                $receipts = $payments->map(function($payment) {
                    $date = \Carbon\Carbon::parse($payment->paid_at ?? $payment->created_at);
                    return [
                        'id' => $payment->id,
                        'receipt_number' => 'RE-DEMO-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT),
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method ?? 'Cash',
                        'date' => $date->format('j M Y'),
                        'time' => $date->format('g:i A'),
                        'month_year' => $payment->fee->month ?? $date->format('F'),
                        'download_url' => route('student.receipts.download', ['id' => 'demo-' . $payment->id]),
                    ];
                });
            } else {
                // If no payments, check if there are fees with paid_amount > 0
                $fees = \App\Models\Fee::where('student_id', $request->user()->id)
                    ->where('paid_amount', '>', 0)
                    ->orderByDesc('date')
                    ->get();
                    
                if ($fees->isNotEmpty()) {
                    $receipts = $fees->map(function($fee) {
                        $date = \Carbon\Carbon::parse($fee->updated_at ?? $fee->date);
                        return [
                            'id' => $fee->id, // Use fee ID
                            'receipt_number' => 'RE-FEE-' . str_pad($fee->id, 4, '0', STR_PAD_LEFT),
                            'amount' => $fee->paid_amount,
                            'payment_method' => 'Cash',
                            'date' => $date->format('j M Y'),
                            'time' => $date->format('g:i A'),
                            'month_year' => $fee->month ?? $date->format('F'),
                            'download_url' => route('student.receipts.download', ['id' => 'fee-' . $fee->id]),
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
                $amount = number_format($payment->amount, 2);
                $method = $payment->payment_method ?? 'Cash';
                $date = \Carbon\Carbon::parse($payment->paid_at ?? $payment->created_at)->format('d M Y, h:i A');
                $receiptNo = 'RE-DEMO-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT);
                $instituteName = $payment->fee->institute->name ?? 'Institute';
                
                $html = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 30px; border-radius: 8px;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h1 style="color: #333; margin: 0 0 10px 0;">Payment Receipt</h1>
                        <h3 style="color: #666; margin: 0;">' . $instituteName . '</h3>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Receipt No:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $receiptNo . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Student:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $student->name . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Date:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $date . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Payment Method:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $method . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-size: 18px;"><strong>Amount Paid:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right; font-size: 18px;"><strong>₹' . $amount . '</strong></td>
                        </tr>
                    </table>
                    <div style="text-align: center; margin-top: 40px; color: #888; font-size: 12px;">
                        <p>Thank you for your payment.</p>
                        <p>This is a computer-generated document. No signature is required.</p>
                    </div>
                </div>';
                
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                return $pdf->download('Receipt-' . $receiptNo . '.pdf');
            }
        }

        // For demo fallback from fees
        if (str_starts_with($id, 'fee-')) {
            $feeId = str_replace('fee-', '', $id);
            $fee = \App\Models\Fee::find($feeId);
            
            if ($fee) {
                $amount = number_format($fee->paid_amount, 2);
                $method = 'Cash';
                $date = \Carbon\Carbon::parse($fee->updated_at ?? $fee->date)->format('d M Y, h:i A');
                $receiptNo = 'RE-FEE-' . str_pad($fee->id, 4, '0', STR_PAD_LEFT);
                $instituteName = $fee->institute->name ?? 'Institute';
                
                $html = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 30px; border-radius: 8px;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h1 style="color: #333; margin: 0 0 10px 0;">Payment Receipt</h1>
                        <h3 style="color: #666; margin: 0;">' . $instituteName . '</h3>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Receipt No:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $receiptNo . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Student:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $student->name . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Date:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $date . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Payment Method:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $method . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-size: 18px;"><strong>Amount Paid:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right; font-size: 18px;"><strong>₹' . $amount . '</strong></td>
                        </tr>
                    </table>
                    <div style="text-align: center; margin-top: 40px; color: #888; font-size: 12px;">
                        <p>Thank you for your payment.</p>
                        <p>This is a computer-generated document. No signature is required.</p>
                    </div>
                </div>';
                
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                return $pdf->download('Receipt-' . $receiptNo . '.pdf');
            }
        }

        $receipt = Receipt::with(['payment.fee.institute', 'payment.student'])
            ->whereHas('payment', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->find($id);

        if (!$receipt) {
            return response()->json(['status' => 'error', 'message' => 'Receipt not found'], 404);
        }

        $data = [
            'receipt' => $receipt,
            'payment' => $receipt->payment,
            'fee' => $receipt->payment->fee,
            'student' => $receipt->payment->student,
            'institute' => $receipt->payment->fee->institute ?? $student->institute,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', $data);
        return $pdf->download('Receipt-' . $receipt->receipt_number . '.pdf');
    }
}
