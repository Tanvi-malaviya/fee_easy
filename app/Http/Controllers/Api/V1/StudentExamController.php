<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{
    /**
     * Get list of exams and results for the authenticated student.
     */
    public function index(Request $request)
    {
        $student = $request->user();
        if (!$student || !($student instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        if (!$student->batch_id) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'exams' => [],
                    'overall_stats' => [
                        'total_exams' => 0,
                        'passed_exams' => 0,
                        'average_percentage' => 0,
                    ]
                ]
            ]);
        }

        // Get all exams for student's batch
        $exams = Exam::where('batch_id', $student->batch_id)
            ->with(['batch:id,name,subject'])
            ->orderByDesc('exam_date')
            ->get();

        $studentMarks = ExamMark::whereIn('exam_id', $exams->pluck('id'))
            ->where('student_id', $student->id)
            ->get()
            ->keyBy('exam_id');

        $examList = $exams->map(function ($exam) use ($studentMarks) {
            $mark = $studentMarks->get($exam->id);

            return [
                'id'             => $exam->id,
                'title'          => $exam->title,
                'subject'        => $exam->subject,
                'exam_date'      => $exam->exam_date ? $exam->exam_date->format('Y-m-d') : null,
                'formatted_date' => $exam->formatted_date,
                'total_marks'    => (float) $exam->total_marks,
                'passing_marks'  => (float) $exam->passing_marks,
                'status'         => $exam->status,
                'marks_obtained' => $mark && !$mark->is_absent ? (float) $mark->marks_obtained : null,
                'is_absent'      => $mark ? (bool) $mark->is_absent : false,
                'is_pass'        => $mark ? $mark->is_pass : null,
                'percentage'     => $mark ? $mark->percentage : null,
                'grade'          => $mark ? $mark->grade : null,
                'remarks'        => $mark ? $mark->remarks : null,
            ];
        });

        // Compute overall student performance
        $scoredMarks = $studentMarks->where('is_absent', false)->whereNotNull('marks_obtained');
        $totalAttended = $scoredMarks->count();
        $totalPassed = $scoredMarks->filter(function ($m) { return $m->is_pass; })->count();
        $avgPercentage = $totalAttended > 0 ? round($scoredMarks->avg(function ($m) { return $m->percentage; }), 1) : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'exams' => $examList,
                'overall_stats' => [
                    'total_exams' => $exams->count(),
                    'attended_exams' => $totalAttended,
                    'passed_exams' => $totalPassed,
                    'average_percentage' => $avgPercentage,
                ]
            ]
        ]);
    }

    /**
     * Get specific exam report for the authenticated student.
     */
    public function show(Request $request, $id)
    {
        $student = $request->user();
        if (!$student || !($student instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = Exam::where('id', $id)
            ->where('batch_id', $student->batch_id)
            ->with(['batch:id,name,subject'])
            ->first();

        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        $mark = ExamMark::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'exam' => $exam,
                'student_result' => [
                    'marks_obtained' => $mark && !$mark->is_absent ? (float) $mark->marks_obtained : null,
                    'is_absent'      => $mark ? (bool) $mark->is_absent : false,
                    'is_pass'        => $mark ? $mark->is_pass : null,
                    'percentage'     => $mark ? $mark->percentage : null,
                    'grade'          => $mark ? $mark->grade : null,
                    'remarks'        => $mark ? $mark->remarks : null,
                ],
                'class_stats' => [
                    'highest_marks' => $exam->stats['highest_marks'],
                    'average_marks' => $exam->stats['average_marks'],
                    'pass_percentage' => $exam->stats['pass_percentage'],
                ]
            ]
        ]);
    }
}
