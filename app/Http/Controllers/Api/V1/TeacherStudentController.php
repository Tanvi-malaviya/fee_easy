<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Notification;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherStudentController extends Controller
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

    protected function ownedBatch(Request $request, $batchId)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return [null, null];
        }

        $batch = Batch::where('id', $batchId)->where('staff_id', $teacher->id)->first();
        return [$teacher, $batch];
    }

    /**
     * List students in an assigned batch.
     */
    public function index(Request $request, $batchId)
    {
        [$teacher, $batch] = $this->ownedBatch($request, $batchId);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found or not assigned to you.'], 404);
        }

        $students = $batch->students()->orderBy('name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $students,
        ]);
    }

    /**
     * Add a new student directly into the assigned batch.
     */
    public function store(Request $request, $batchId)
    {
        [$teacher, $batch] = $this->ownedBatch($request, $batchId);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found or not assigned to you.'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:students,email',
            'phone' => 'required|numeric|digits:10',
            'standard' => 'required|string',
            'dob' => 'required|date|before_or_equal:today',
            'guardian_name' => 'required|string|max:255',
            'monthly_fee' => 'nullable|numeric|min:0|max:999999',
        ]);

        $password = Str::random(10);

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($password),
            'institute_id' => $batch->institute_id,
            'batch_id' => $batch->id,
            'standard' => $request->standard,
            'dob' => $request->dob,
            'guardian_name' => $request->guardian_name,
            'monthly_fee' => $request->monthly_fee,
            'status' => 'active',
            'id_hash' => Str::random(32),
        ]);

        try {
            $institute = $batch->institute;
            \App\Services\InstituteMailService::send(
                $institute,
                $student->email,
                new \App\Mail\StudentAddedMail(
                    $student->name,
                    $student->email,
                    $password,
                    $institute->institute_name,
                    $institute->logo
                )
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send welcome email to student: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Student added to batch successfully.',
            'data' => $student,
        ], 201);
    }

    /**
     * Unassign a student from the batch (does not delete their institute account).
     */
    public function removeStudent(Request $request, $batchId, $studentId)
    {
        [$teacher, $batch] = $this->ownedBatch($request, $batchId);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found or not assigned to you.'], 404);
        }

        $student = Student::where('id', $studentId)->where('batch_id', $batch->id)->first();
        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Student not found in this batch.'], 404);
        }

        $student->update(['batch_id' => null]);

        Notification::create([
            'user_type' => 'student',
            'user_id' => $student->id,
            'title' => 'Batch Updated',
            'message' => "You have been removed from the batch: {$batch->name}",
            'type' => 'batch_removal',
            'reference_id' => $batch->id,
            'is_read' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Student removed from batch successfully.',
        ]);
    }
}
