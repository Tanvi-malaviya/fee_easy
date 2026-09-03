<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Staff;
use Illuminate\Http\Request;

class TeacherAttendanceController extends Controller
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

    /**
     * Attendance sheet for an assigned batch on a given date.
     */
    public function index(Request $request)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'date' => 'required|date',
        ]);

        $batch = Batch::where('id', $request->batch_id)->where('staff_id', $teacher->id)->first();
        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found or not assigned to you.'], 404);
        }

        $students = $batch->students()->select('id', 'name', 'phone', 'batch_id', 'enrollment_id')->get();

        $attendanceRecords = Attendance::where('batch_id', $batch->id)
            ->where('date', $request->date)
            ->get()
            ->keyBy('student_id');

        $records = $students->map(function ($student) use ($attendanceRecords) {
            $record = $attendanceRecords->get($student->id);
            return [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'phone' => $student->phone,
                'enrollment_id' => $student->enrollment_id,
                'status' => $record ? $record->status : null,
                'attendance_id' => $record ? $record->id : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $records,
        ]);
    }

    /**
     * Bulk mark student attendance for an assigned batch.
     */
    public function store(Request $request)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late',
        ]);

        $batch = Batch::where('id', $request->batch_id)->where('staff_id', $teacher->id)->first();
        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found or not assigned to you.'], 404);
        }

        $savedRecords = [];
        foreach ($request->attendance as $record) {
            $savedRecords[] = Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'batch_id' => $batch->id,
                    'date' => $request->date,
                ],
                [
                    'status' => $record['status'],
                    'marked_by' => $teacher->full_name,
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance marked successfully.',
            'data' => $savedRecords,
        ]);
    }
}
