<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;

class TeacherSelfAttendanceController extends Controller
{
    /**
     * Mark the teacher's own attendance for today. The date is always
     * server-set — a teacher can never backdate or future-date their own
     * attendance. Re-marking the same day overwrites the earlier mark
     * (allowed until midnight, after which a new day's row is created).
     */
    public function store(Request $request)
    {
        $teacher = $request->user();
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'status' => 'required|in:Present,Absent,Half Day,Late',
            'note' => 'nullable|string',
        ]);

        $attendance = StaffAttendance::updateOrCreate(
            [
                'staff_id' => $teacher->id,
                'institute_id' => $teacher->institute_id,
                'date' => now()->toDateString(),
            ],
            [
                'status' => $request->status,
                'note' => $request->note,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance marked successfully.',
            'data' => $attendance,
        ]);
    }

    /**
     * Today's attendance status, if already marked.
     */
    public function today(Request $request)
    {
        $teacher = $request->user();
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $attendance = StaffAttendance::where('staff_id', $teacher->id)
            ->where('date', now()->toDateString())
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $attendance,
        ]);
    }

    /**
     * The teacher's own attendance history + summary.
     */
    public function index(Request $request)
    {
        $teacher = $request->user();
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = StaffAttendance::where('staff_id', $teacher->id);

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $totalPresent = (clone $query)->where('status', 'Present')->count();
        $totalAbsent = (clone $query)->where('status', 'Absent')->count();

        $attendance = $query->orderBy('date', 'desc')->paginate($request->get('per_page', 30));

        return response()->json([
            'status' => 'success',
            'summary' => [
                'total_present' => $totalPresent,
                'total_absent' => $totalAbsent,
            ],
            'data' => $attendance->items(),
            'pagination' => [
                'total' => $attendance->total(),
                'per_page' => $attendance->perPage(),
                'current_page' => $attendance->currentPage(),
                'last_page' => $attendance->lastPage(),
            ],
        ]);
    }
}
