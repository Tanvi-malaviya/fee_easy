<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\DailyUpdate;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteDailyUpdateController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $updates = $request->user()
            ->dailyUpdates()
            ->with('batch')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $updates,
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'category' => 'required|string',
            'recipient' => 'required|string|in:students,parents',
            'target_type' => 'required_if:recipient,students|string|in:all,batch,standard',
            'batch_id' => 'required_if:target_type,batch|nullable|exists:batches,id',
            'standard' => 'required_if:target_type,standard|nullable|string',
            'topic' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('updates', 'public');
            $attachmentPath = asset('storage/' . $path);
        }

        $update = DailyUpdate::create([
            'institute_id' => $request->user()->id,
            'recipient' => $request->recipient,
            'batch_id' => ($request->recipient === 'students' && $request->target_type === 'batch') ? $request->batch_id : null,
            'target_type' => $request->recipient === 'students' ? $request->target_type : 'all',
            'standard' => ($request->recipient === 'students' && $request->target_type === 'standard') ? $request->standard : null,
            'category' => $request->category,
            'topic' => $request->topic,
            'description' => $request->description,
            'attachment' => $attachmentPath,
            'date' => now()->toDateString(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Update published successfully.',
            'data' => $update->load('batch'),
        ], 201);
    }
}
