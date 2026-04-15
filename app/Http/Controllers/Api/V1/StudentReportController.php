<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Fee;
use Illuminate\Http\Request;

class StudentReportController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user();

        $totalFees = Fee::where('student_id', $student->id)->sum('total_amount');
        $paidFees = Fee::where('student_id', $student->id)->sum('paid_amount');
        $dueFees = Fee::where('student_id', $student->id)->sum('due_amount');

        $attendanceCount = Attendance::where('student_id', $student->id)->count();
        $presentCount = Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->count();
        $attendanceRate = $attendanceCount > 0 ? round(($presentCount / $attendanceCount) * 100, 2) : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'student_name' => $student->name,
                'standard' => $student->standard,
                'school_name' => $student->school_name,
                'batch_name' => $student->batch->name ?? null,
                'fees' => [
                    'total' => $totalFees,
                    'paid' => $paidFees,
                    'due' => $dueFees,
                ],
                'attendance' => [
                    'total_days' => $attendanceCount,
                    'present_days' => $presentCount,
                    'attendance_rate' => $attendanceRate,
                ],
            ],
        ]);
    }
}
