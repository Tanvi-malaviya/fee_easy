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
        $query = StaffAttendance::where('institute_id', $instituteId)->with('staff:id,full_name,employee_id');

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

        // Validation for matching dates if both are provided
        if ($request->has('date') && $request->query('date') && $request->query('date') !== $request->date) {
            return response()->json([
                'status' => 'error',
                'message' => 'The date in URL and the date in body do not match.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'attendances' => 'required|array',
            'attendances.*.staff_id' => 'required|exists:staff,id,institute_id,' . $instituteId,
            'attendances.*.status' => 'required|in:Present,Absent,Half Day,Late,Holiday',
            'attendances.*.note' => 'nullable|string',
            'date' => 'required|date|before_or_equal:today'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $date = $request->date;
        $savedStaff = [];

        foreach ($request->attendances as $att) {
            $attendance = StaffAttendance::updateOrCreate(
                [
                    'staff_id' => $att['staff_id'],
                    'date' => $date,
                    'institute_id' => $instituteId
                ],
                [
                    'status' => $att['status'],
                    'note' => $att['note'] ?? null
                ]
            );
            
            // Get staff details for response
            $staff = $attendance->staff;
            $savedStaff[] = [
                'id' => $staff->id,
                'name' => $staff->full_name,
                'status' => $attendance->status
            ];
        }

        return response()->json([
            'message' => 'Attendance logged successfully',
            'count' => count($savedStaff),
            'date' => $date,
            'staff_details' => $savedStaff
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
}
