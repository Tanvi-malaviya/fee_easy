<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Institute;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InstituteExamController extends Controller
{
    /**
     * Resolve the authenticated institute (from Sanctum API token or Web session).
     */
    protected function getInstitute(Request $request)
    {
        $user = $request->user();
        if ($user instanceof Institute) {
            return $user;
        }

        if (auth('institute')->check()) {
            return auth('institute')->user();
        }

        return null;
    }

    /**
     * List all exams for the institute with optional filtering.
     */
    public function index(Request $request)
    {
        $institute = $this->getInstitute($request);
        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = Exam::where('institute_id', $institute->id)
            ->with(['batch:id,name,subject']);

        // Filter by batch_id
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('exam_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('exam_date', '<=', $request->to_date);
        }

        // Search by title or subject
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $exams = $query->orderByDesc('exam_date')->orderByDesc('id')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => [
                'current_page' => $exams->currentPage(),
                'data'         => $exams->items(),
                'from'         => $exams->firstItem(),
                'last_page'    => $exams->lastPage(),
                'per_page'     => $exams->perPage(),
                'to'           => $exams->lastItem(),
                'total'        => $exams->total(),
            ],
        ]);
    }

    /**
     * Create a new exam.
     */
    public function store(Request $request)
    {
        $institute = $this->getInstitute($request);
        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'batch_id'      => 'required|integer|exists:batches,id',
            'title'         => 'required|string|max:255',
            'subject'       => 'nullable|string|max:255',
            'exam_date'     => 'required|date',
            'start_time'    => 'nullable|string',
            'end_time'      => 'nullable|string',
            'total_marks'   => 'required|numeric|min:1|max:10000',
            'passing_marks' => 'required|numeric|min:0|lte:total_marks',
            'description'   => 'nullable|string',
            'status'        => 'nullable|string|in:scheduled,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify batch belongs to this institute
        $batch = Batch::where('id', $request->batch_id)
            ->where('institute_id', $institute->id)
            ->first();

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Selected batch does not belong to your institute.'
            ], 404);
        }

        $exam = Exam::create([
            'institute_id'  => $institute->id,
            'batch_id'      => $batch->id,
            'title'         => $request->title,
            'subject'       => $request->subject ?: $batch->subject,
            'exam_date'     => $request->exam_date,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'total_marks'   => $request->total_marks,
            'passing_marks' => $request->passing_marks,
            'description'   => $request->description,
            'status'        => $request->status ?: 'scheduled',
        ]);

        $exam->load('batch:id,name,subject');

        return response()->json([
            'status' => 'success',
            'message' => 'Exam created successfully.',
            'data' => $exam,
        ], 201);
    }

    /**
     * Get single exam details along with stats and marks.
     */
    public function show(Request $request, $id)
    {
        $institute = $this->getInstitute($request);
        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = Exam::where('id', $id)
            ->where('institute_id', $institute->id)
            ->with([
                'batch:id,name,subject',
                'marks.student:id,name,phone,enrollment_id,profile_image'
            ])
            ->first();

        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $exam,
        ]);
    }

    /**
     * Update an exam.
     */
    public function update(Request $request, $id)
    {
        $institute = $this->getInstitute($request);
        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = Exam::where('id', $id)
            ->where('institute_id', $institute->id)
            ->first();

        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'         => 'sometimes|required|string|max:255',
            'subject'       => 'nullable|string|max:255',
            'exam_date'     => 'sometimes|required|date',
            'start_time'    => 'nullable|string',
            'end_time'      => 'nullable|string',
            'total_marks'   => 'sometimes|required|numeric|min:1|max:10000',
            'passing_marks' => 'sometimes|required|numeric|min:0',
            'description'   => 'nullable|string',
            'status'        => 'nullable|string|in:scheduled,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $totalMarks = $request->input('total_marks', $exam->total_marks);
        $passingMarks = $request->input('passing_marks', $exam->passing_marks);

        if ((float) $passingMarks > (float) $totalMarks) {
            return response()->json([
                'status' => 'error',
                'message' => 'Passing marks cannot exceed total marks.'
            ], 422);
        }

        $exam->update($request->only([
            'title',
            'subject',
            'exam_date',
            'start_time',
            'end_time',
            'total_marks',
            'passing_marks',
            'description',
            'status',
        ]));

        $exam->load('batch:id,name,subject');

        return response()->json([
            'status' => 'success',
            'message' => 'Exam updated successfully.',
            'data' => $exam,
        ]);
    }

    /**
     * Delete an exam.
     */
    public function destroy(Request $request, $id)
    {
        $institute = $this->getInstitute($request);
        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = Exam::where('id', $id)
            ->where('institute_id', $institute->id)
            ->first();

        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        $exam->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Exam deleted successfully.',
        ]);
    }

    /**
     * Get student list for an exam along with their existing marks.
     */
    public function getMarks(Request $request, $id)
    {
        $institute = $this->getInstitute($request);
        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = Exam::where('id', $id)
            ->where('institute_id', $institute->id)
            ->with(['batch:id,name,subject'])
            ->first();

        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        // Get all students enrolled in the batch
        $students = Student::where('batch_id', $exam->batch_id)
            ->where('institute_id', $institute->id)
            ->whereNull('deleted_at')
            ->select('id', 'name', 'phone', 'enrollment_id', 'profile_image', 'monthly_fee')
            ->orderBy('name')
            ->get();

        // Get existing marks for this exam
        $existingMarks = ExamMark::where('exam_id', $exam->id)
            ->get()
            ->keyBy('student_id');

        $marksData = $students->map(function ($student) use ($existingMarks, $exam) {
            $mark = $existingMarks->get($student->id);

            return [
                'student_id'      => $student->id,
                'student_name'    => $student->name,
                'phone'           => $student->phone,
                'enrollment_id'   => $student->enrollment_id,
                'profile_image'   => $student->profile_image_url,
                'marks_obtained'  => $mark && !$mark->is_absent ? (float) $mark->marks_obtained : null,
                'is_absent'       => $mark ? (bool) $mark->is_absent : false,
                'remarks'         => $mark ? $mark->remarks : '',
                'percentage'      => $mark ? $mark->percentage : null,
                'is_pass'         => $mark ? $mark->is_pass : null,
                'grade'           => $mark ? $mark->grade : null,
                'mark_id'         => $mark ? $mark->id : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'exam'    => $exam,
                'students' => $marksData,
                'stats'   => $exam->stats,
            ],
        ]);
    }

    /**
     * Save/Update marks for students in an exam.
     */
    public function saveMarks(Request $request, $id)
    {
        $institute = $this->getInstitute($request);
        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $exam = Exam::where('id', $id)
            ->where('institute_id', $institute->id)
            ->first();

        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'marks'                  => 'required|array',
            'marks.*.student_id'     => 'required|integer|exists:students,id',
            'marks.*.marks_obtained' => 'nullable|numeric|min:0|max:' . $exam->total_marks,
            'marks.*.is_absent'      => 'nullable|boolean',
            'marks.*.remarks'        => 'nullable|string|max:255',
            'mark_status_as_completed' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $marksInput = $request->input('marks', []);

        DB::beginTransaction();
        try {
            foreach ($marksInput as $item) {
                $studentId = $item['student_id'];
                $isAbsent = !empty($item['is_absent']);
                $marksObtained = $isAbsent ? null : (isset($item['marks_obtained']) && $item['marks_obtained'] !== '' ? (float) $item['marks_obtained'] : null);
                $remarks = $item['remarks'] ?? null;

                ExamMark::updateOrCreate(
                    [
                        'exam_id'    => $exam->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'marks_obtained' => $marksObtained,
                        'is_absent'      => $isAbsent,
                        'remarks'        => $remarks,
                    ]
                );
            }

            // Automatically transition status to completed if requested or if marks are submitted
            if ($request->boolean('mark_status_as_completed', true) && $exam->status === 'scheduled') {
                $exam->update(['status' => 'completed']);
            }

            DB::commit();

            $exam->refresh();

            return response()->json([
                'status' => 'success',
                'message' => 'Marks saved successfully.',
                'data' => [
                    'exam'  => $exam,
                    'stats' => $exam->stats,
                ]
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save marks: ' . $e->getMessage()
            ], 500);
        }
    }
}
