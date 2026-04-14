<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class InstituteAttendanceController extends Controller
{
    /**
     * List attendance records with date and batch filtering.
     */
    public function index(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'batch_id' => 'nullable|exists:batches,id'
        ]);

        $query = Attendance::with('student:id,name')
            ->where('date', $request->date)
            ->whereHas('batch', function($q) use ($request) {
                $q->where('institute_id', $request->user()->id);
            });

        if ($request->batch_id) {
            $query->where('batch_id', $request->batch_id);
        }

        $records = $query->get();

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
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance marked successfully',
            'data' => $savedRecords
        ]);
    }
}
