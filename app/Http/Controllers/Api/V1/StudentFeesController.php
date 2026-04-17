<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentFeesController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $fees = Fee::where('student_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'total_fees' => $fees->sum('total_amount'),
            'paid_fees' => $fees->sum('paid_amount'),
            'due_fees' => $fees->sum('due_amount'),
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
