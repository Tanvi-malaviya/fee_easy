<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Support\Carbon;

class InstituteTeacherController extends Controller
{
    /**
     * Get all teachers for the institute.
     */
    public function index(Request $request)
    {
        $institute = $request->user();
        $paginator = $institute->teachers()->orderBy('name', 'asc')->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $paginator->items(),
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
            ]
        ]);
    }

    /**
     * Add a new teacher.
     */
    public function store(Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'subject' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'join_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive'
        ]);

        $teacher = $institute->teachers()->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher added successfully.',
            'data' => $teacher
        ], 201);
    }

    /**
     * Update an existing teacher.
     */
    public function update(Request $request, $id)
    {
        $institute = $request->user();

        $teacher = $institute->teachers()->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'subject' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'join_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive'
        ]);

        $teacher->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher updated successfully.',
            'data' => $teacher
        ]);
    }

    /**
     * Delete a teacher.
     */
    public function destroy(Request $request, $id)
    {
        $institute = $request->user();
        $teacher = $institute->teachers()->findOrFail($id);
        
        $teacher->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher deleted successfully.'
        ]);
    }

    /**
     * Get attendance for a specific date.
     */
    public function getAttendance(Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'date' => 'required|date'
        ]);

        $attendances = $institute->teacherAttendances()
            ->with('teacher:id,name,subject')
            ->where('date', $request->date)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $attendances
        ]);
    }

    /**
     * Mark attendance for one or more teachers.
     * Expects an array of attendances.
     */
    public function markAttendance(Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.teacher_id' => 'required|exists:teachers,id,institute_id,' . $institute->id,
            'attendances.*.status' => 'required|in:Present,Absent,Half-Day,Leave',
            'attendances.*.remarks' => 'nullable|string'
        ]);

        $date = $request->date;
        $savedAttendances = [];

        foreach ($request->attendances as $att) {
            $attendance = TeacherAttendance::updateOrCreate(
                [
                    'institute_id' => $institute->id,
                    'teacher_id' => $att['teacher_id'],
                    'date' => $date
                ],
                [
                    'status' => $att['status'],
                    'remarks' => $att['remarks'] ?? null
                ]
            );

            $savedAttendances[] = $attendance;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance marked successfully.',
            'data' => $savedAttendances
        ]);
    }

    /**
     * Get Monthly Attendance Report.
     */
    public function attendanceReport(Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000'
        ]);

        // Get all teachers
        $teachers = $institute->teachers()->orderBy('name', 'asc')->get();

        $report = [];

        foreach ($teachers as $teacher) {
            $monthAttendances = $institute->teacherAttendances()
                ->where('teacher_id', $teacher->id)
                ->whereMonth('date', $request->month)
                ->whereYear('date', $request->year)
                ->get();

            $report[] = [
                'teacher' => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'subject' => $teacher->subject
                ],
                'summary' => [
                    'Present' => $monthAttendances->where('status', 'Present')->count(),
                    'Absent' => $monthAttendances->where('status', 'Absent')->count(),
                    'Half-Day' => $monthAttendances->where('status', 'Half-Day')->count(),
                    'Leave' => $monthAttendances->where('status', 'Leave')->count(),
                    'Total_Working_Days_Marked' => $monthAttendances->count()
                ]
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $report
        ]);
    }
}
