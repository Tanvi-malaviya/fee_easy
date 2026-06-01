<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteAttendanceController extends Controller
{
    /**
     * List attendance records with date and batch filtering.
     */
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'date' => 'required|date',
            'batch_id' => 'nullable|exists:batches,id'
        ]);

        $institute = $request->user();

        if ($request->batch_id) {
            $batch = $institute->batches()->find($request->batch_id);
            if (!$batch) {
                return response()->json(['status' => 'error', 'message' => 'Batch not found'], 404);
            }
            // Get all students in the specific batch
            $students = $batch->students()->select('id', 'name', 'phone', 'batch_id')->get();
            $batchIds = [$batch->id];
        } else {
            // Get all students for the whole institute
            $students = $institute->students()->select('id', 'name', 'phone', 'batch_id')->get();
            $batchIds = $institute->batches()->pluck('id')->toArray();
        }

        // Get existing attendance for these batches and date
        $attendanceRecords = Attendance::whereIn('batch_id', $batchIds)
            ->where('date', $request->date)
            ->get()
            ->keyBy('student_id');

        // Map students to include attendance status if it exists
        $records = $students->map(function($student) use ($attendanceRecords) {
            $record = $attendanceRecords->get($student->id);
            return [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'phone' => $student->phone,
                'batch_id' => $student->batch_id,
                'status' => $record ? $record->status : null,
                'marked_by' => $record ? $record->marked_by : null,
                'attendance_id' => $record ? $record->id : null,
                'date' => $record ? $record->date : ($attendanceRecords->isNotEmpty() ? null : null), // consistent
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $records
        ]);
    }

    /**
     * Bulk mark attendance for a batch and date.
     */
    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late',
        ]);

        $batchId = $request->batch_id;
        $date = $request->date;
        $markedBy = $request->user()->name ?? 'Institute Admin';

        $savedRecords = [];
        $studentIds = collect($request->attendance)->pluck('student_id')->toArray();
        $students = \App\Models\Student::whereIn('id', $studentIds)->with('parent')->get()->keyBy('id');
        $fcm = app(\App\Services\FCMService::class);

        foreach ($request->attendance as $record) {
            $attendance = Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'batch_id' => $batchId,
                    'date' => $date,
                ],
                [
                    'status' => $record['status'],
                    'marked_by' => $markedBy,
                ]
            );
            $savedRecords[] = $attendance;

            $student = $students[$record['student_id']] ?? null;
            if ($student && ($attendance->wasRecentlyCreated || $attendance->wasChanged('status'))) {
                $statusUpper = ucfirst(strtolower($record['status']));
                $formattedDate = \Illuminate\Support\Carbon::parse($date)->format('d M Y');
                
                if ($record['status'] === 'present' || $record['status'] === 'late') {
                    $notifTitle   = "Marked Present";
                    $studentBody  = "You've been marked present for today.";
                    $parentBody   = "{$student->name} has been marked present for today.";
                } else {
                    $notifTitle   = "Marked Absent";
                    $studentBody  = "You've been marked absent today. If this is a mistake, contact your institute.";
                    $parentBody   = "{$student->name} has been marked absent today. Please contact the institute if unexpected.";
                }
                $notifData = [
                    'type'   => 'attendance',
                    'date'   => $date,
                    'status' => strtolower($record['status']),
                ];

                \App\Models\Notification::create([
                    'user_type'    => 'student',
                    'user_id'      => $student->id,
                    'title'        => $notifTitle,
                    'message'      => $studentBody,
                    'type'         => 'attendance',
                    'reference_id' => $attendance->id,
                    'is_read'      => false,
                ]);

                // Student FCM push
                if (!empty($student->fcm_token)) {
                    $fcm->send($student->fcm_token, $notifTitle, $studentBody, $notifData);
                }

                // Parent Notification
                if ($student->parent) {
                    $parentTitle = $notifTitle;

                    \App\Models\Notification::create([
                        'user_type' => 'parent',
                        'user_id' => $student->parent->id,
                        'title' => $parentTitle,
                        'message' => $parentBody,
                        'type' => 'attendance',
                        'reference_id' => $attendance->id,
                        'is_read' => false,
                    ]);

                    if (!empty($student->parent->fcm_token)) {
                        $fcm->send($student->parent->fcm_token, $parentTitle, $parentBody, $notifData);
                    }
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance marked successfully',
            'data' => $savedRecords
        ]);
    }
}
