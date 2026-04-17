<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentHomeworkController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();

        $homeworks = Homework::where('batch_id', $student->batch_id)
            ->with('submissions')
            ->orderByDesc('due_date')
            ->get();

        $homeworksWithSubmission = $homeworks->map(function ($homework) use ($student) {
            $submission = HomeworkSubmission::where('homework_id', $homework->id)
                ->where('student_id', $student->id)
                ->first();

            return [
                'id' => $homework->id,
                'title' => $homework->title,
                'description' => $homework->description,
                'due_date' => $homework->due_date,
                'submission_status' => $submission ? 'submitted' : 'not_submitted',
                'submission_date' => $submission->created_at ?? null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $homeworksWithSubmission,
        ]);
    }
}
