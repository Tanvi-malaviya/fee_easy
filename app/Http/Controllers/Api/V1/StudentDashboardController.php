<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();

        $attendanceRate = 0;
        $attendanceCount = Attendance::where('student_id', $student->id)->count();
        if ($attendanceCount > 0) {
            $presentCount = Attendance::where('student_id', $student->id)
                ->where('status', 'present')
                ->count();
            $attendanceRate = ($presentCount / $attendanceCount) * 100;
        }

        $totalFees = Fee::where('student_id', $student->id)->sum('total_amount');
        $paidFees = Fee::where('student_id', $student->id)->sum('paid_amount');
        $dueFees = $totalFees - $paidFees;

        return response()->json([
            'status' => 'success',
            'data' => [
                'student_name' => $student->name,
                'batch_id' => $student->batch_id,
                'batch_name' => $student->batch->name ?? null,
                'attendance_rate' => round($attendanceRate, 2),
                'total_fees' => $totalFees,
                'paid_fees' => $paidFees,
                'due_fees' => $dueFees,
            ],
        ]);
    }
}
