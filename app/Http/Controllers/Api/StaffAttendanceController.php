<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffAttendanceController extends Controller
{
    /**
     * Get attendance list with filters.
     */
    public function index(Request $request)
    {
        $instituteId = $request->user()->id;
        $query = StaffAttendance::where('institute_id', $instituteId)->with(['staff:id,full_name,employee_id,staff_department_id,profile_image', 'staff.department:id,name']);

        if ($request->has('date')) {
            $query->where('date', $request->date);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('staff', function($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                  ->orWhere('employee_id', 'like', "%$search%");
            });
        }

        $attendance = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $attendance->items(),
            'pagination' => [
                'total' => $attendance->total(),
                'per_page' => $attendance->perPage(),
                'current_page' => $attendance->currentPage(),
                'last_page' => $attendance->lastPage(),
            ]
        ]);
    }

    /**
     * Store new attendance (Log Attendance).
     */
    public function store(Request $request)
    {
        $instituteId = $request->user()->id;

        // Handle both single object and array for backward compatibility
        $isBulk = $request->has('attendances') && is_array($request->attendances);
        $attendances = $isBulk ? $request->attendances : [$request->all()];
        $date = $request->date ?? ($isBulk ? null : $request->date);

        $validator = Validator::make($request->all(), [
            'attendances' => $isBulk ? 'required|array' : 'nullable',
            'staff_id' => !$isBulk ? 'required|exists:staff,id,institute_id,' . $instituteId : 'nullable',
            'status' => !$isBulk ? 'required|in:Present,Absent,Half Day,Late,Holiday' : 'nullable',
            'note' => 'nullable|string',
            'date' => 'required|date|before_or_equal:today',
            'attendances.*.staff_id' => $isBulk ? 'required|exists:staff,id,institute_id,' . $instituteId : 'nullable',
            'attendances.*.status' => $isBulk ? 'required|in:Present,Absent,Half Day,Late,Holiday' : 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $savedData = [];
        foreach ($attendances as $att) {
            $staffId = $att['staff_id'] ?? $request->staff_id;
            $status = $att['status'] ?? $request->status;
            $note = $att['note'] ?? $request->note;
            $currentDate = $att['date'] ?? $request->date;

            $attendance = StaffAttendance::updateOrCreate(
                [
                    'staff_id' => $staffId,
                    'date' => $currentDate,
                    'institute_id' => $instituteId
                ],
                [
                    'status' => $status,
                    'note' => $note
                ]
            );

            $staff = $attendance->staff;
            $savedData[] = [
                'id' => $attendance->id,
                'staff_id' => $staff->id,
                'staff_name' => $staff->full_name,
                'date' => $attendance->date,
                'status' => $attendance->status,
                'note' => $attendance->note
            ];
        }

        return response()->json([
            'message' => 'Attendance saved successfully',
            'data' => $isBulk ? $savedData : $savedData[0]
        ]);
    }

    /**
     * Export attendance (Simplified JSON for now).
     */
    public function export(Request $request)
    {
        $institute = $request->user();
        $query = StaffAttendance::where('institute_id', $institute->id)
            ->with('staff:id,full_name,employee_id');

        if ($request->has('date')) {
            $query->where('date', $request->date);
        }

        $attendances = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.staff_attendance', [
            'institute' => $institute,
            'attendances' => $attendances
        ]);

        return $pdf->download('staff_attendance_' . date('Y-m-d') . '.pdf');
    }
    /**
     * Remove the specified attendance record.
     */
    public function destroy(Request $request, $id)
    {
        $instituteId = $request->user()->id;
        $attendance = StaffAttendance::where('institute_id', $instituteId)->find($id);

        if (!$attendance) {
            return response()->json(['status' => 'error', 'message' => 'Attendance record not found'], 404);
        }

        $attendance->delete();

        return response()->json(['status' => 'success', 'message' => 'Attendance record deleted successfully']);
    }

    /**
     * Get attendance records for a particular staff.
     */
    public function showByStaff(Request $request, $staffId)
    {
        $instituteId = $request->user()->id;
        $query = StaffAttendance::where('institute_id', $instituteId)
            ->where('staff_id', $staffId);

        if ($request->has('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->has('year')) {
            $query->whereYear('date', $request->year);
        }

        $totalPresent = (clone $query)->where('status', 'Present')->count();
        $totalAbsent = (clone $query)->where('status', 'Absent')->count();
       

        $attendance = $query->orderBy('date', 'desc')->paginate($request->get('per_page', 15));

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
            ]
        ]);
    }
}
