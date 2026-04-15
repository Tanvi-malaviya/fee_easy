<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Illuminate\Http\Request;

class StudentReceiptsController extends Controller
{
    public function index(Request $request)
    {
        $receipts = Receipt::whereHas('payment', function ($query) use ($request) {
                $query->where('student_id', $request->user()->id);
            })
            ->with(['payment:id,student_id,amount,payment_method,transaction_id,paid_at'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $receipts,
        ]);
    }
}
