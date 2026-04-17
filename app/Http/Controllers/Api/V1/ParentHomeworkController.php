<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\StudentParent;
use Illuminate\Http\Request;

class ParentHomeworkController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof StudentParent)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $parent = $request->user();
        $students = $parent->students;
        $studentIds = $students->pluck('id');
        $batchIds = $students->pluck('batch_id')->filter()->unique();

        $homeworks = Homework::whereIn('batch_id', $batchIds)
            ->orderByDesc('due_date')
            ->get();

        $submissions = HomeworkSubmission::whereIn('student_id', $studentIds)
            ->whereIn('homework_id', $homeworks->pluck('id'))
            ->get()
            ->groupBy('homework_id');

        $homeworksWithSubmissions = $homeworks->map(function ($homework) use ($students, $submissions) {
            $homeworkSubmissions = $submissions->get($homework->id, collect());

            $children = $students->where('batch_id', $homework->batch_id)->map(function ($student) use ($homeworkSubmissions) {
                $submission = $homeworkSubmissions->firstWhere('student_id', $student->id);

                return [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'submission_status' => $submission ? 'submitted' : 'not_submitted',
                    'submission_date' => $submission->created_at ?? null,
                ];
            });

            return [
                'id' => $homework->id,
                'title' => $homework->title,
                'description' => $homework->description,
                'due_date' => $homework->due_date,
                'submissions' => $children,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $homeworksWithSubmissions,
        ]);
    }
}
