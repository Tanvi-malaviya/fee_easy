<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeacherExamController extends Controller
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

    protected function findOwnedExam(Staff $teacher, $id): ?Exam
    {
        $batchIds = Batch::where('staff_id', $teacher->id)->pluck('id');
        return Exam::where('id', $id)->whereIn('batch_id', $batchIds)->first();
    }

    public function index(Request $request)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batchIds = Batch::where('staff_id', $teacher->id)->pluck('id');

        $query = Exam::whereIn('batch_id', $batchIds)->with(['batch:id,name,subject']);

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $exams = $query->orderByDesc('exam_date')->orderByDesc('id')->paginate($request->input('per_page', 15));

        return response()->json(['status' => 'success', 'data' => $exams]);
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'batch_id' => 'required|integer|exists:batches,id',
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'exam_type' => 'nullable|string|in:unit_test,mid_term,final,quiz,assignment,other',
            'exam_date' => 'required|date',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'total_marks' => 'required|numeric|min:1|max:10000',
            'passing_marks' => 'required|numeric|min:0|lte:total_marks',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $batch = Batch::where('id', $request->batch_id)->where('staff_id', $teacher->id)->first();
        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found or not assigned to you.'], 404);
        }

        $exam = Exam::create([
            'institute_id' => $batch->institute_id,
            'batch_id' => $batch->id,
            'staff_id' => $teacher->id,
            'title' => $request->title,
            'subject' => $request->subject ?: $batch->subject,
            'exam_type' => $request->exam_type ?: 'other',
            'exam_date' => $request->exam_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_marks' => $request->total_marks,
            'passing_marks' => $request->passing_marks,
            'description' => $request->description,
            'status' => 'scheduled',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Exam created successfully.',
            'data' => $exam->load('batch:id,name,subject'),
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = $this->findOwnedExam($teacher, $id);
        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        $exam->load(['batch:id,name,subject', 'marks.student:id,name,phone,enrollment_id,profile_image']);

        return response()->json(['status' => 'success', 'data' => $exam]);
    }

    public function update(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = $this->findOwnedExam($teacher, $id);
        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'exam_type' => 'nullable|string|in:unit_test,mid_term,final,quiz,assignment,other',
            'exam_date' => 'sometimes|required|date',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'total_marks' => 'sometimes|required|numeric|min:1|max:10000',
            'passing_marks' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:scheduled,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $totalMarks = $request->input('total_marks', $exam->total_marks);
        $passingMarks = $request->input('passing_marks', $exam->passing_marks);
        if ((float) $passingMarks > (float) $totalMarks) {
            return response()->json(['status' => 'error', 'message' => 'Passing marks cannot exceed total marks.'], 422);
        }

        $exam->update($request->only([
            'title', 'subject', 'exam_type', 'exam_date', 'start_time', 'end_time',
            'total_marks', 'passing_marks', 'description', 'status',
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Exam updated successfully.',
            'data' => $exam->fresh()->load('batch:id,name,subject'),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = $this->findOwnedExam($teacher, $id);
        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        $exam->delete();

        return response()->json(['status' => 'success', 'message' => 'Exam deleted successfully.']);
    }

    public function getMarks(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = $this->findOwnedExam($teacher, $id);
        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        $students = Student::where('batch_id', $exam->batch_id)
            ->whereNull('deleted_at')
            ->select('id', 'name', 'phone', 'enrollment_id', 'profile_image')
            ->orderBy('name')
            ->get();

        $existingMarks = ExamMark::where('exam_id', $exam->id)->get()->keyBy('student_id');

        $marksData = $students->map(function ($student) use ($existingMarks) {
            $mark = $existingMarks->get($student->id);
            return [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'enrollment_id' => $student->enrollment_id,
                'marks_obtained' => $mark && !$mark->is_absent ? (float) $mark->marks_obtained : null,
                'is_absent' => $mark ? (bool) $mark->is_absent : false,
                'remarks' => $mark ? $mark->remarks : '',
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => ['exam' => $exam, 'students' => $marksData, 'stats' => $exam->stats],
        ]);
    }

    public function saveMarks(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = $this->findOwnedExam($teacher, $id);
        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'marks' => 'required|array',
            'marks.*.student_id' => 'required|integer|exists:students,id',
            'marks.*.marks_obtained' => 'nullable|numeric|min:0|max:' . $exam->total_marks,
            'marks.*.is_absent' => 'nullable|boolean',
            'marks.*.remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($request->input('marks', []) as $item) {
                $isAbsent = !empty($item['is_absent']);
                ExamMark::updateOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => $item['student_id']],
                    [
                        'marks_obtained' => $isAbsent ? null : ($item['marks_obtained'] ?? null),
                        'is_absent' => $isAbsent,
                        'remarks' => $item['remarks'] ?? null,
                    ]
                );
            }

            $justPublished = false;
            if ($request->boolean('mark_status_as_completed', true) && $exam->status === 'scheduled') {
                $exam->update(['status' => 'completed']);
                $justPublished = true;
            }

            DB::commit();

            // Auto-email the report card PDF to every student with marks the moment results are published.
            if ($justPublished) {
                \App\Services\ExamReportNotifier::sendForExam($exam->fresh());
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Marks saved successfully.',
                'data' => ['exam' => $exam->fresh(), 'stats' => $exam->fresh()->stats],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to save marks: ' . $e->getMessage()], 500);
        }
    }
}
