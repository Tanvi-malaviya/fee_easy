<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Fee;
use App\Models\StudentParent;
use Illuminate\Http\Request;

class ParentReportController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof StudentParent)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $parent = $request->user();
        $students = $parent->students()->with('batch')->get();
        $studentIds = $students->pluck('id');

        $totalFees = Fee::whereIn('student_id', $studentIds)->sum('total_amount');
        $paidFees = Fee::whereIn('student_id', $studentIds)->sum('paid_amount');
        $dueFees = Fee::whereIn('student_id', $studentIds)->sum('due_amount');

        $attendanceCount = Attendance::whereIn('student_id', $studentIds)->count();
        $presentCount = Attendance::whereIn('student_id', $studentIds)
            ->where('status', 'present')
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'parent_name' => $parent->name,
                'children_count' => $students->count(),
                'children' => $students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'standard' => $student->standard,
                        'school_name' => $student->school_name,
                        'batch_name' => $student->batch->name ?? null,
                    ];
                }),
                'fees' => [
                    'total' => $totalFees,
                    'paid' => $paidFees,
                    'due' => $dueFees,
                ],
                'attendance' => [
                    'total_days' => $attendanceCount,
                    'present_days' => $presentCount,
                    'attendance_rate' => $attendanceCount > 0 ? round(($presentCount / $attendanceCount) * 100, 2) : 0,
                ],
            ],
        ]);
    }
}
