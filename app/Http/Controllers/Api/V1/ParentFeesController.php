<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\StudentParent;
use Illuminate\Http\Request;

class ParentFeesController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof StudentParent)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $parent = $request->user();
        $studentIds = $parent->students()->pluck('id');

        $fees = Fee::with('student:id,name,batch_id')
            ->whereIn('student_id', $studentIds)
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'total_fees' => $fees->sum('total_amount'),
            'paid_fees' => $fees->sum('paid_amount'),
            'due_fees' => $fees->sum('total_amount') - $fees->sum('paid_amount'),
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => $summary,
                'fees' => $fees,
            ],
        ]);
    }
}
