<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\StudentParent;
use Illuminate\Http\Request;

class ParentReceiptsController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof StudentParent)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $studentIds = $request->user()->students()->pluck('id');

        $receipts = Receipt::whereHas('payment', function ($query) use ($studentIds) {
                $query->whereIn('student_id', $studentIds);
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
