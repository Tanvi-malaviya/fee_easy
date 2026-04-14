<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use Illuminate\Http\Request;

class InstituteFeeController extends Controller
{
    /**
     * Display a listing of all fees for the authenticated institute.
     */
    public function index(Request $request)
    {
        $paginator = Fee::where('institute_id', $request->user()->id)
            ->with('student:id,name,email')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $paginator->items(),
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
            ]
        ]);
    }

    /**
     * Store a newly created fee for a student.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'total_amount' => 'required|numeric|min:0',
            'month' => 'required|string',
            'year' => 'required|integer',
        ]);

        $fee = Fee::create([
            'institute_id' => $request->user()->id,
            'student_id' => $request->student_id,
            'total_amount' => $request->total_amount,
            'paid_amount' => 0,
            'due_amount' => $request->total_amount,
            'status' => 'Unpaid', // Default status
            'month' => $request->month,
            'year' => $request->year,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Fee record created successfully',
            'data' => $fee
        ], 201);
    }

    /**
     * Display fees for a specific student.
     */
    public function getStudentFees(Request $request, $student_id)
    {
        $fees = Fee::where('institute_id', $request->user()->id)
            ->where('student_id', $student_id)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $fees
        ]);
    }
}
