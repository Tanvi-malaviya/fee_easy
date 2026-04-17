<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\StudentParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentPaymentController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof StudentParent)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([

            'fee_id' => 'required|exists:fees,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        $parent = $request->user();
        $studentIds = $parent->students()->pluck('id');

        $fee = Fee::whereIn('student_id', $studentIds)->find($request->fee_id);

        if (! $fee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fee record not found or unauthorized.',
            ], 404);
        }

        return DB::transaction(function () use ($request, $fee) {
            $payment = Payment::create([
                'fee_id' => $fee->id,
                'student_id' => $fee->student_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'paid_at' => $request->paid_at ?? now(),
            ]);

            $fee->paid_amount += $request->amount;
            $fee->due_amount = $fee->total_amount - $fee->paid_amount;

            if ($fee->due_amount <= 0) {
                $fee->status = 'Paid';
                $fee->due_amount = 0;
            } elseif ($fee->paid_amount > 0) {
                $fee->status = 'Partial';
            }

            $fee->save();

            Receipt::create([
                'payment_id' => $payment->id,
                'receipt_number' => 'RE-' . strtoupper(substr(uniqid(), -6)) . '-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT),
                'file_url' => null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment recorded successfully',
                'data' => [
                    'payment_id' => $payment->id,
                    'fee_id' => $payment->fee_id,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'transaction_id' => $payment->transaction_id,
                    'paid_at' => $payment->paid_at,
                    'total_fee' => $fee->total_amount,
                    'paid_so_far' => $fee->paid_amount,
                    'remaining_due' => $fee->due_amount,
                    'fee_status' => $fee->status,
                ],
            ], 201);
        });
    }
}
