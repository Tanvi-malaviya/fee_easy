<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use Illuminate\Http\Request;

class StudentFeesController extends Controller
{
    public function index(Request $request)
    {
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
