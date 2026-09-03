<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Staff;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeacherTimetableController extends Controller
{
    protected function getTeacher(Request $request)
    {
        $user = $request->user();
        if ($user instanceof Staff) {
            return $user;
        }

        if (auth('teacher')->check()) {
            return auth('teacher')->user();
        }

        return null;
    }

    public function index(Request $request)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = Timetable::where('staff_id', $teacher->id)->with(['batch:id,name,subject,classroom']);

        if ($request->filled('day')) {
            $query->where('day_of_week', strtolower($request->day));
        }
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $schedules = $query->orderBy('start_time')->get();

        return response()->json(['status' => 'success', 'data' => $schedules]);
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'subject' => 'required|string|max:150',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required',
            'end_time' => 'required',
            'room_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $batch = Batch::where('id', $validated['batch_id'])->where('staff_id', $teacher->id)->first();
        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found or not assigned to you.'], 404);
        }

        $timetable = Timetable::create([
            'institute_id' => $teacher->institute_id,
            'batch_id' => $batch->id,
            'staff_id' => $teacher->id,
            'subject' => $validated['subject'],
            'day_of_week' => strtolower($validated['day_of_week']),
            'start_time' => Carbon::parse($validated['start_time'])->format('H:i:s'),
            'end_time' => Carbon::parse($validated['end_time'])->format('H:i:s'),
            'room_no' => $validated['room_no'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lecture schedule created successfully.',
            'data' => $timetable->load('batch'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $timetable = Timetable::where('id', $id)->where('staff_id', $teacher->id)->first();
        if (!$timetable) {
            return response()->json(['status' => 'error', 'message' => 'Schedule not found.'], 404);
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:150',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required',
            'end_time' => 'required',
            'room_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,cancelled',
        ]);

        $validated['start_time'] = Carbon::parse($validated['start_time'])->format('H:i:s');
        $validated['end_time'] = Carbon::parse($validated['end_time'])->format('H:i:s');
        $validated['day_of_week'] = strtolower($validated['day_of_week']);

        $timetable->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Schedule updated successfully.',
            'data' => $timetable->fresh('batch'),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $timetable = Timetable::where('id', $id)->where('staff_id', $teacher->id)->first();
        if (!$timetable) {
            return response()->json(['status' => 'error', 'message' => 'Schedule not found.'], 404);
        }

        $timetable->delete();

        return response()->json(['status' => 'success', 'message' => 'Lecture slot deleted successfully.']);
    }
}
