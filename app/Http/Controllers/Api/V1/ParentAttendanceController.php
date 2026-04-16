<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\StudentParent;
use Illuminate\Http\Request;

class ParentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof StudentParent)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $studentIds = $request->user()->students()->pluck('id');

        $attendance = Attendance::whereIn('student_id', $studentIds)
            ->with(['batch', 'student:id,name'])
            ->orderByDesc('date')
            ->get();

        $presentCount = $attendance->where('status', 'present')->count();
        $absentCount = $attendance->where('status', 'absent')->count();
        $leaveCount = $attendance->where('status', 'leave')->count();
        $totalDays = $attendance->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'total_days' => $totalDays,
                    'present' => $presentCount,
                    'absent' => $absentCount,
                    'leave' => $leaveCount,
                    'attendance_rate' => $totalDays > 0 ? round(($presentCount / $totalDays) * 100, 2) : 0,
                ],
                'records' => $attendance,
            ],
        ]);
    }
}
