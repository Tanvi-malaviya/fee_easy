<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Homework;
use App\Models\Staff;
use Illuminate\Http\Request;

class TeacherHomeworkController extends Controller
{
    protected function getTeacher(Request $request)
    {
        $user = $request->user();
        if ($user instanceof Staff) {
            return $user;
        }

        if (auth('teacher')->check()) {
            return auth('teacher')->user();
        }

        return null;
    }

    public function index(Request $request)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batchIds = Batch::where('staff_id', $teacher->id)->pluck('id');

        $query = Homework::whereIn('batch_id', $batchIds);

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $homeworks = $query->with(['batch:id,name'])
            ->withCount('submissions')
            ->orderByDesc('created_at')
            ->paginate(12);

        return response()->json([
            'status' => 'success',
            'data' => $homeworks,
        ]);
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'batch_id' => 'required|integer|exists:batches,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after_or_equal:today',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $batch = Batch::where('id', $request->batch_id)->where('staff_id', $teacher->id)->first();
        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found or not assigned to you.'], 404);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('homework_attachments', $filename, 'public');
        }

        $homework = Homework::create([
            'batch_id' => $batch->id,
            'institute_id' => $batch->institute_id,
            'staff_id' => $teacher->id,
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

    protected function findOwnedHomework(Staff $teacher, $id): ?Homework
    {
        $batchIds = Batch::where('staff_id', $teacher->id)->pluck('id');
        return Homework::where('id', $id)->whereIn('batch_id', $batchIds)->first();
    }

    public function show(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = $this->findOwnedHomework($teacher, $id);
        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found.'], 404);
        }

        $homework->load(['batch:id,name,subject', 'submissions.student:id,name,profile_image']);

        return response()->json(['status' => 'success', 'data' => $homework]);
    }

    public function update(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = $this->findOwnedHomework($teacher, $id);
        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found.'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'due_date' => 'sometimes|required|date',
        ]);

        $homework->update($request->only(['title', 'description', 'due_date']));

        return response()->json([
            'status' => 'success',
            'message' => 'Homework updated successfully.',
            'data' => $homework->fresh(),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = $this->findOwnedHomework($teacher, $id);
        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found.'], 404);
        }

        $homework->delete();

        return response()->json(['status' => 'success', 'message' => 'Homework deleted successfully.']);
    }

    public function updateGrades(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = $this->findOwnedHomework($teacher, $id);
        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found.'], 404);
        }

        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|integer|exists:students,id',
            'grades.*.score' => 'nullable|numeric',
            'grades.*.status' => 'required|string',
        ]);

        foreach ($request->grades as $gradeData) {
            $status = ucfirst(strtolower($gradeData['status']));
            if ($gradeData['score'] !== null && $gradeData['score'] !== '') {
                $status = 'Reviewed';
            }
            if (!in_array($status, ['Pending', 'Missing', 'Late', 'Submitted', 'Reviewed'])) {
                continue;
            }

            $homework->submissions()->updateOrCreate(
                ['student_id' => $gradeData['student_id']],
                ['score' => $gradeData['score'], 'status' => $status]
            );
        }

        return response()->json(['status' => 'success', 'message' => 'Grades updated successfully.']);
    }
}
