<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Homework;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'attachment' => 'nullable|file|max:10240', // Max 10MB
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

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('homework_attachments', $filename, 'public');
        }

        $homework = Homework::create([
            'batch_id' => $batch->id,
            'institute_id' => $institute->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'attachment' => $attachmentPath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Homework created successfully.',
            'data' => $homework,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = Homework::with(['batch.students', 'submissions'])
            ->where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $homework,
        ]);
    }

    public function updateGrades(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = Homework::where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|integer|exists:students,id',
            'grades.*.score' => 'nullable|numeric',
            'grades.*.status' => 'required|in:Pending,Missing,Late,Submitted',
        ]);

        foreach ($request->grades as $gradeData) {
            $homework->submissions()->updateOrCreate(
                ['student_id' => $gradeData['student_id']],
                [
                    'score' => $gradeData['score'],
                    'status' => $gradeData['status'],
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Grades updated successfully',
        ]);
    }
}
