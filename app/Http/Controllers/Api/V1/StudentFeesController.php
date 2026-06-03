<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StudentFeesController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();
        $fees = Fee::where('student_id', $student->id)
            ->orderByDesc('date')
            ->get();

        // total_fees = student's monthly_fee (fixed), not sum of fee records
        $totalFees = (float) ($student->monthly_fee > 0 ? $student->monthly_fee : $fees->sum('total_amount'));
        $paidFees = (float) $fees->sum('paid_amount');
        $dueFees = max(0.0, $totalFees - $paidFees);

        $summary = [
            'total_fees' => $totalFees,
            'paid_fees' => $paidFees,
            'due_fees' => $dueFees,
        ];

        $feeList = $fees->map(function ($fee) {
            $dueDate = Carbon::parse($fee->date);
            $dueAmount = max(0.0, (float) $fee->total_amount - (float) $fee->paid_amount);
            $diffDays = Carbon::today()->diffInDays($dueDate, false);

            $daysLabel = match (true) {
                $diffDays > 0 => "{$diffDays} days left",
                $diffDays == 0 => 'Due today',
                default => 'Overdue by ' . abs($diffDays) . ' days',
            };

            return [
                'id' => $fee->id,
                'month_year' => $dueDate->format('F Y'),
                'due_date' => $fee->date,
                'paid_amount' => (float) $fee->paid_amount,
                'status' => ucfirst($fee->status),
                'is_overdue' => $diffDays < 0,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => $summary,
                'fees' => $feeList,
            ],
        ]);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();
        $fee = Fee::where('student_id', $student->id)->find($id);

        if (!$fee) {
            // As a fallback/demo mode: Create a dynamic fee model on-the-fly for the current month
            // based on the student's monthly fee.
            $currentMonthStart = Carbon::now()->startOfMonth();
            $dueDate = $currentMonthStart->copy()->addDays(24); // 25th of the month

            $dueAmount = $student->monthly_fee > 0 ? (float) $student->monthly_fee : 4500.00;
            $paidAmount = 0.0;

            // Check if there is any payment recorded in the database
            $totalPaid = (float) \App\Models\Payment::where('student_id', $student->id)->sum('amount');
            if ($totalPaid > 0) {
                $paidAmount = $totalPaid;
                $dueAmount = max(0.0, $dueAmount - $paidAmount);
            }

            // Create a temporary mock fee object to use below
            $fee = new Fee([
                'id' => 0,
                'student_id' => $student->id,
                'institute_id' => $student->institute_id,
                'total_amount' => $student->monthly_fee > 0 ? (float) $student->monthly_fee : 4500.00,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'status' => $paidAmount > 0 ? 'Partial' : 'Pending',
                'date' => $dueDate->toDateString(),
                'created_at' => Carbon::now()->subHours(2),
            ]);
        }

        $dueDate = Carbon::parse($fee->date);
        $dueAmount = $fee->total_amount - $fee->paid_amount;

        // Calculate days left or overdue
        $diffInDays = Carbon::today()->diffInDays($dueDate, false);
        if ($diffInDays > 0) {
            $daysLeftLabel = "{$diffInDays} days left";
        } elseif ($diffInDays == 0) {
            $daysLeftLabel = "Today";
        } else {
            $daysLeftLabel = "Overdue by " . abs($diffInDays) . " days";
        }

        $formattedDueDate = $dueDate->format('d M Y');
        $dueLabel = "{$formattedDueDate} · {$daysLeftLabel}";

        // Invoice Number format: INV-YYYY-MM
        // Use 5 as default sequence or fee ID
        $seq = $fee->id > 0 ? $fee->id : 5;
        $invoiceNo = "INV-" . $dueDate->format('Y-m') . "-" . str_pad($seq, 2, '0', STR_PAD_LEFT);

        // Posted time label
        $postedLabel = $fee->created_at ? $fee->created_at->diffForHumans() : '2h ago';

        // Payment / Open Invoice link
        $openInvoiceUrl = route('institute.fees.receipts.show', ['receipt' => $fee->id > 0 ? $fee->id : 1]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'title' => "Fee reminder · ₹" . number_format($dueAmount) . " due " . $dueDate->format('j M'),
                'card' => [
                    'month_year' => strtoupper($dueDate->format('F Y')),
                    'due_amount' => "₹" . number_format($dueAmount),
                    'due_amount_raw' => $dueAmount,
                    'due_label' => $dueLabel,
                ],
                'details' => [
                    [
                        'label' => 'Invoice no.',
                        'value' => $invoiceNo,
                    ],
                    [
                        'label' => 'Status',
                        'value' => ucfirst($fee->status),
                        'badge_color' => $fee->status == 'Paid' ? 'green' : ($fee->status == 'Partial' ? 'orange' : 'red'),
                    ],
                    [
                        'label' => 'Due date',
                        'value' => $dueDate->format('j M Y'),
                    ],
                    [
                        'label' => 'Posted',
                        'value' => $postedLabel,
                    ]
                ],
                'notice' => 'A late fee of ₹100 applies after the due date. Pay in cash at the institute or transfer via UPI.',
                'actions' => [
                    'open_invoice_url' => $openInvoiceUrl,
                    'download_url' => route('student.fees.download', ['id' => $id])
                ]
            ]
        ]);
    }

    public function download(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();
        $fee = Fee::with(['institute'])->where('student_id', $student->id)->find($id);

        if (!$fee) {
            // For demo fallback (ID 1 or any missing fee)
            $html = '
            <div style="font-family: DejaVu Sans, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 30px; border-radius: 8px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h1 style="color: #333; margin: 0 0 10px 0;">Fee Invoice</h1>
                </div>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Student:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $student->name . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Total Amount:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">₹4500.00</td>
                    </tr>
                </table>
            </div>';
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            return $pdf->download('Fee-Invoice-' . $id . '.pdf');
        }

        // Normally you'd load a real PDF view like 'pdf.fee_invoice'
        // Since we don't have that specific view yet, we can render a simple HTML or use a fallback
        $data = [
            'fee' => $fee,
            'student' => $student,
            'institute' => $fee->institute ?? $student->institute,
        ];

        // If you have a specific view for fee invoice, use it here, otherwise fallback to basic HTML
        if (view()->exists('pdf.fee_invoice')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.fee_invoice', $data);
        } else {
            $html = '
            <div style="font-family: DejaVu Sans, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 30px; border-radius: 8px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h1 style="color: #333; margin: 0 0 10px 0;">Fee Invoice</h1>
                    <h3 style="color: #666; margin: 0;">' . ($data['institute']->name ?? 'Institute') . '</h3>
                </div>
                
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Student:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $student->name . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Month:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">' . $fee->month . ' ' . $fee->year . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Total Amount:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">₹' . number_format($fee->total_amount, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Paid Amount:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">₹' . number_format($fee->paid_amount, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Status:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right; text-transform: capitalize;">' . $fee->status . '</td>
                    </tr>
                </table>
                
                <div style="text-align: center; margin-top: 40px; color: #888; font-size: 12px;">
                    <p>This is a computer-generated document. No signature is required.</p>
                </div>
            </div>';
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        }

        return $pdf->download('Fee-Invoice-' . $fee->month . '-' . $fee->year . '.pdf');
    }
}
