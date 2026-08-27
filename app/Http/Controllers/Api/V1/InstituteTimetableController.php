<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\Batch;
use App\Models\Staff;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InstituteTimetableController extends Controller
{
    /**
     * Get timetable schedule for the authenticated institute.
     */
    public function index(Request $request)
    {
        $institute = $request->user();

        $query = Timetable::where('institute_id', $institute->id)
            ->with(['batch:id,name,subject,fees,classroom', 'staff:id,full_name,employee_id,profile_image,staff_role_id']);

        if ($request->has('day')) {
            $query->where('day_of_week', strtolower($request->day));
        }

        if ($request->has('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->has('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        $schedules = $query->orderBy('start_time', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $schedules
        ]);
    }

    /**
     * Get timetable for authenticated Student.
     */
    public function studentSchedule(Request $request)
    {
        $student = $request->user();
        if (!$student || !$student->batch_id) {
            return response()->json([
                'status' => 'success',
                'data' => []
            ]);
        }

        $today = strtolower(Carbon::now()->format('l'));
        $day = strtolower($request->get('day', $today));

        $schedules = Timetable::where('institute_id', $student->institute_id)
            ->where('batch_id', $student->batch_id)
            ->where('status', 'active')
            ->when($day !== 'all', function ($q) use ($day) {
                $q->where('day_of_week', $day);
            })
            ->with(['batch:id,name,subject', 'staff:id,full_name,employee_id,profile_image'])
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'day' => $day,
            'data' => $schedules
        ]);
    }

    /**
     * Store new schedule slot via API.
     */
    public function store(Request $request)
    {
        $institute = $request->user();

        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'staff_id' => 'nullable|exists:staff,id',
            'subject' => 'required|string|max:150',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required',
            'end_time' => 'required',
            'room_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['institute_id'] = $institute->id;
        $validated['start_time'] = Carbon::parse($validated['start_time'])->format('H:i:s');
        $validated['end_time'] = Carbon::parse($validated['end_time'])->format('H:i:s');
        $validated['day_of_week'] = strtolower($validated['day_of_week']);
        $validated['status'] = 'active';

        $timetable = Timetable::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Lecture schedule created successfully.',
            'data' => $timetable->load(['batch', 'staff'])
        ], 201);
    }
}
