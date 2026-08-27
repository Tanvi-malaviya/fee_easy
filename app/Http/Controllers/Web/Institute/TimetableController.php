<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Staff;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TimetableController extends Controller
{
    /**
     * Display the Daily & Weekly Schedule with Faculty & Batch.
     */
    public function index(Request $request)
    {
        $institute = Auth::guard('institute')->user();

        $batches = Batch::where('institute_id', $institute->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $facultyList = Staff::where('institute_id', $institute->id)
            ->faculty()
            ->with(['role', 'department', 'departments'])
            ->orderBy('full_name')
            ->get();

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        $today = strtolower(Carbon::now()->format('l'));
        $selectedDay = strtolower($request->get('day', $today));
        if (!array_key_exists($selectedDay, $daysOfWeek)) {
            $selectedDay = 'monday';
        }

        $filterBatchId = $request->get('batch_id');
        $filterStaffId = $request->get('staff_id');
        $viewMode = $request->get('view', 'daily'); // 'daily' or 'weekly'

        // Base Query
        $baseQuery = Timetable::where('institute_id', $institute->id)
            ->with(['batch', 'staff.role', 'staff.department']);

        if (!empty($filterBatchId)) {
            $baseQuery->where('batch_id', $filterBatchId);
        }

        if (!empty($filterStaffId)) {
            $baseQuery->where('staff_id', $filterStaffId);
        }

        // Daily Slots Query
        $dailySlots = (clone $baseQuery)
            ->where('day_of_week', $selectedDay)
            ->orderBy('start_time', 'asc')
            ->get();

        // Weekly Matrix Query (all 7 days)
        $allWeeklySlots = (clone $baseQuery)
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_of_week');

        // Stats
        $totalLecturesWeek = Timetable::where('institute_id', $institute->id)->count();
        $totalLecturesToday = Timetable::where('institute_id', $institute->id)->where('day_of_week', $today)->count();
        $assignedBatchesCount = Timetable::where('institute_id', $institute->id)->distinct('batch_id')->count('batch_id');
        $assignedFacultyCount = Timetable::where('institute_id', $institute->id)->whereNotNull('staff_id')->distinct('staff_id')->count('staff_id');

        return view('institute.timetable.index', compact(
            'batches',
            'facultyList',
            'daysOfWeek',
            'today',
            'selectedDay',
            'filterBatchId',
            'filterStaffId',
            'viewMode',
            'dailySlots',
            'allWeeklySlots',
            'totalLecturesWeek',
            'totalLecturesToday',
            'assignedBatchesCount',
            'assignedFacultyCount'
        ));
    }

    /**
     * Store a newly created schedule slot.
     */
    public function store(Request $request)
    {
        $institute = Auth::guard('institute')->user();

        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'staff_id' => 'nullable|exists:staff,id',
            'subject' => 'required|string|max:150',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,cancelled',
        ], [
            'batch_id.required' => 'Please select a batch.',
            'subject.required' => 'Subject name is required.',
            'end_time.after' => 'End time must be after the start time.',
        ]);

        $validated['institute_id'] = $institute->id;
        $validated['day_of_week'] = strtolower($validated['day_of_week']);
        $validated['status'] = $validated['status'] ?? 'active';

        $timetable = Timetable::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Lecture schedule added successfully.',
                'data' => $timetable->load(['batch', 'staff'])
            ]);
        }

        return redirect()->route('institute.timetable.index', [
            'day' => $validated['day_of_week'],
            'batch_id' => $request->get('filter_batch_id'),
            'staff_id' => $request->get('filter_staff_id'),
        ])->with('success', 'Lecture schedule added successfully.');
    }

    /**
     * Update the specified schedule slot.
     */
    public function update(Request $request, Timetable $timetable)
    {
        $institute = Auth::guard('institute')->user();

        if ($timetable->institute_id !== $institute->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'staff_id' => 'nullable|exists:staff,id',
            'subject' => 'required|string|max:150',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,cancelled',
        ]);

        // Normalize time formats (H:i)
        $validated['start_time'] = Carbon::parse($validated['start_time'])->format('H:i:s');
        $validated['end_time'] = Carbon::parse($validated['end_time'])->format('H:i:s');
        $validated['day_of_week'] = strtolower($validated['day_of_week']);

        $timetable->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Schedule updated successfully.',
                'data' => $timetable->fresh(['batch', 'staff'])
            ]);
        }

        return redirect()->route('institute.timetable.index', [
            'day' => $validated['day_of_week'],
        ])->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified schedule slot.
     */
    public function destroy(Request $request, Timetable $timetable)
    {
        $institute = Auth::guard('institute')->user();

        if ($timetable->institute_id !== $institute->id) {
            abort(403, 'Unauthorized action.');
        }

        $day = $timetable->day_of_week;
        $timetable->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Lecture slot deleted successfully.'
            ]);
        }

        return redirect()->route('institute.timetable.index', ['day' => $day])
            ->with('success', 'Lecture slot deleted successfully.');
    }
}
