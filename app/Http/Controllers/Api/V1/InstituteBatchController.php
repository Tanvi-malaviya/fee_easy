<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteBatchController extends Controller
{
    /**
     * Display a listing of batches belonging to the authenticated institute.
     */
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $paginator = Batch::where('institute_id', $request->user()->id)->paginate(10);

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

    public function show(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batch = Batch::where('institute_id', $request->user()->id)
            ->withCount('students')
            ->find($id);

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found or unauthorized'
            ], 404);
        }

        // Calculate total fees paid by students in this batch
        $studentIds = $batch->students()->pluck('id');
        $totalPaid = \App\Models\Fee::whereIn('student_id', $studentIds)->sum('paid_amount');

        $batch->total_paid = $totalPaid;

        return response()->json([
            'status' => 'success',
            'data' => $batch
        ]);
    }

    /**
     * Store a newly created batch for the institute.
     */
    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fees' => 'nullable|numeric|min:0',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'days' => 'nullable|array',
        ]);

        $batch = Batch::create([
            'institute_id' => $request->user()->id,
            'name' => $request->name,
            'subject' => $request->subject,
            'description' => $request->description,
            'fees' => $request->fees,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'days' => $request->days,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Batch created successfully',
            'data' => $batch
        ], 201);
    }

    /**
     * Update the specified batch.
     */
    public function update(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batch = Batch::where('institute_id', $request->user()->id)->find($id);

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found or unauthorized'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'subject' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'fees' => 'nullable|numeric|min:0',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'days' => 'nullable|array',
        ]);

        $batch->update($request->only(['name', 'subject', 'description', 'fees', 'start_time', 'end_time', 'days']));

        return response()->json([
            'status' => 'success',
            'message' => 'Batch updated successfully',
            'data' => $batch
        ]);
    }

    /**
     * Remove the specified batch.
     */
    public function destroy(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batch = Batch::where('institute_id', $request->user()->id)->find($id);

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found or unauthorized'
            ], 404);
        }

        $batch->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Batch deleted successfully'
        ]);
    }
}
