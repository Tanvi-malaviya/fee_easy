<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Fee;
use App\Models\StudentParent;
use Illuminate\Http\Request;

class ParentDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof StudentParent)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $parent = $request->user();
        $children = $parent->students()->with('batch')->get();
        $studentIds = $children->pluck('id')->toArray();

        $fees = Fee::whereIn('student_id', $studentIds)->get();
        $attendance = Attendance::whereIn('student_id', $studentIds)->get();

        $totalFees = $fees->sum('total_amount');
        $paidFees = $fees->sum('paid_amount');
        $dueFees = $totalFees - $paidFees;

        $presentCount = $attendance->where('status', 'present')->count();
        $totalRecords = $attendance->count();
        $attendanceRate = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'parent_name' => $parent->name,
                'children_count' => $children->count(),
                'children' => $children->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'batch_id' => $student->batch_id,
                        'batch_name' => $student->batch->name ?? null,
                        'standard' => $student->standard,
                    ];
                }),
                'attendance_rate' => $attendanceRate,
                'total_fees' => $totalFees,
                'paid_fees' => $paidFees,
                'due_fees' => $dueFees,
            ],
        ]);
    }
}
