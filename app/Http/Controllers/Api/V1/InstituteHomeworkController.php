<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Homework;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteHomeworkController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homeworks = $request->user()
            ->homeworks()
            ->with('batch')
            ->orderByDesc('due_date')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $homeworks,
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'batch_id' => 'required|integer|exists:batches,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ]);

        $institute = $request->user();
        $batch = Batch::where('id', $request->batch_id)
            ->where('institute_id', $institute->id)
            ->first();

        if (! $batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found for this institute.',
            ], 404);
        }

        $homework = Homework::create([
            'batch_id' => $batch->id,
            'institute_id' => $institute->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Homework created successfully.',
            'data' => $homework,
        ], 201);
    }
}
