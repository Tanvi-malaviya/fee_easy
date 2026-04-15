<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\DailyUpdate;
use Illuminate\Http\Request;

class InstituteDailyUpdateController extends Controller
{
    public function index(Request $request)
    {
        $updates = $request->user()
            ->dailyUpdates()
            ->with('batch')
            ->orderByDesc('date')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $updates,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|integer|exists:batches,id',
            'topic' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
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

        $update = DailyUpdate::create([
            'batch_id' => $batch->id,
            'institute_id' => $institute->id,
            'topic' => $request->topic,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Daily update created successfully.',
            'data' => $update,
        ], 201);
    }
}
