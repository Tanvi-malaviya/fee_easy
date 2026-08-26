<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $institute = \Illuminate\Support\Facades\Auth::guard('institute')->user();

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            $query = \App\Models\Batch::where('institute_id', $institute->id)
                ->withCount('students')
                ->with(['students:id,name,batch_id,profile_image,enrollment_id', 'staff']);

            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                      ->orWhere('subject', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm);
                });
            }

            $perPage = 10;
            $batches = $query->paginate($perPage);

            foreach ($batches as $batch) {
                $studentIds = \App\Models\Student::where('batch_id', $batch->id)->pluck('id');
                $batch->total_paid = (float) \App\Models\Fee::whereIn('student_id', $studentIds)->sum('paid_amount');
                $batch->total_expected = (float) $batch->students()->sum('monthly_fee');
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'items' => $batches->items(),
                    'total' => $batches->total(),
                    'current_page' => $batches->currentPage(),
                    'last_page' => $batches->lastPage(),
                    'per_page' => $batches->perPage(),
                    'from' => $batches->firstItem(),
                    'to' => $batches->lastItem(),
                ]
            ]);
        }

        $staffList = \App\Models\Staff::where('institute_id', $institute->id)->orderBy('full_name')->get();
        return view('institute.batches.index', compact('staffList'));
    }

    public function store(Request $request)
    {
        return app(\App\Http\Controllers\Api\V1\InstituteBatchController::class)->store($request);
    }

    public function update(Request $request, $id)
    {
        return app(\App\Http\Controllers\Api\V1\InstituteBatchController::class)->update($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return app(\App\Http\Controllers\Api\V1\InstituteBatchController::class)->destroy($request, $id);
    }

    public function create()
    {
        return view('institute.batches.create');
    }

    public function show(Request $request, $id)
    {
        $institute = \Illuminate\Support\Facades\Auth::guard('institute')->user();
        
        $batch = \App\Models\Batch::where('institute_id', $institute->id)
            ->withCount('students')
            ->with('staff')
            ->findOrFail($id);

        // Calculate total fees paid and expected for this batch
        $studentIds = $batch->students()->pluck('id');
        $batch->total_paid = (float) \App\Models\Fee::whereIn('student_id', $studentIds)->sum('paid_amount');
        $batch->total_expected = (float) $batch->students()->sum('monthly_fee');

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $batch
            ]);
        }

        $staffList = \App\Models\Staff::where('institute_id', $institute->id)->with('department')->orderBy('full_name')->get();
        return view('institute.batches.show', compact('id', 'batch', 'staffList'));
    }

    public function edit($id)
    {
        return view('institute.batches.edit', compact('id'));
    }

    public function students($id)
    {
        $batch = \App\Models\Batch::where('id', $id)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();
            
        $students = $batch->students;
        return view('institute.batches.students', compact('batch', 'students', 'id'));
    }

    public function homework($id)
    {
        $batch = \App\Models\Batch::where('id', $id)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();
            
        $homeworks = $batch->homeworks;
        return view('institute.batches.homework', compact('batch', 'homeworks', 'id'));
    }

    public function attendance($id)
    {
        $batch = \App\Models\Batch::where('id', $id)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();
            
        return view('institute.batches.attendance', compact('batch', 'id'));
    }

    public function resources($id)
    {
        $batch = \App\Models\Batch::where('id', $id)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();
            
        return view('institute.batches.resources', compact('batch', 'id'));
    }

    public function homeworkShow($batchId, $homeworkId)
    {
        $batch = \App\Models\Batch::where('id', $batchId)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();
            
        $homework = \App\Models\Homework::where('id', $homeworkId)
            ->where('batch_id', $batchId)
            ->firstOrFail();
            
        $id = $batchId;
        $homework_id = $homeworkId;
        return view('institute.batches.homework_show', compact('batch', 'homework', 'batchId', 'homeworkId', 'id', 'homework_id'));
    }

    public function exams($id)
    {
        $batch = \App\Models\Batch::where('id', $id)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();

        $exams = $batch->exams()->orderByDesc('exam_date')->get();
        return view('institute.batches.exams', compact('batch', 'exams', 'id'));
    }

    public function examShow($batchId, $examId)
    {
        $batch = \App\Models\Batch::where('id', $batchId)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();

        $exam = \App\Models\Exam::where('id', $examId)
            ->where('batch_id', $batchId)
            ->where('institute_id', auth('institute')->id())
            ->firstOrFail();

        $students = \App\Models\Student::where('batch_id', $batchId)
            ->where('institute_id', auth('institute')->id())
            ->whereNull('deleted_at')
            ->select('id', 'name', 'phone', 'enrollment_id', 'profile_image', 'monthly_fee')
            ->orderBy('name')
            ->get();

        $existingMarks = \App\Models\ExamMark::where('exam_id', $examId)
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
        })->values();

        $id = $batchId;
        $exam_id = $examId;
        return view('institute.batches.exam_show', compact('batch', 'exam', 'batchId', 'examId', 'id', 'exam_id', 'marksData'));
    }
}
