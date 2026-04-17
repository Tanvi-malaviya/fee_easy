<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $attendance = Attendance::where('student_id', $request->user()->id)
            ->with('batch')
            ->orderByDesc('date')
            ->get();

        $presentCount = $attendance->where('status', 'present')->count();
        $absentCount = $attendance->where('status', 'absent')->count();
        $leaveCount = $attendance->where('status', 'leave')->count();
        $totalDays = $attendance->count();

        $attendanceRate = $totalDays > 0 ? round(($presentCount / $totalDays) * 100, 2) : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'total_days' => $totalDays,
                    'present' => $presentCount,
                    'absent' => $absentCount,
                    'leave' => $leaveCount,
                    'attendance_rate' => $attendanceRate,
                ],
                'records' => $attendance,
            ],
        ]);
    }
}
