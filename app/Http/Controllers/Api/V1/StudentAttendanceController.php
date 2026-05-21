<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();
        
        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);
        $date = \Carbon\Carbon::createFromDate($year, $month, 1);
        
        // 1. Today's Status
        $todayRecord = Attendance::where('student_id', $student->id)
            ->whereDate('date', now()->toDateString())
            ->first();
            
        $todayData = null;
        if ($todayRecord) {
            $timeStr = $todayRecord->created_at ? $todayRecord->created_at->format('g:i A') : '8:00 AM';
            $todayData = [
                'status' => ucfirst($todayRecord->status),
                'text'   => now()->format('l, d M') . ' · checked in at ' . $timeStr,
            ];
        } else {
            $todayData = [
                'status' => 'Not Marked',
                'text'   => now()->format('l, d M') . ' · attendance pending',
            ];
        }

        // 2. Calendar Data
        $attendances = Attendance::where('student_id', $student->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy('date');
            
        $daysInMonth = $date->daysInMonth;
        $calendarDays = [];
        
        $presentCount = 0;
        $absentCount = 0;
        $leaveCount = 0;
        
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $currentDate = $date->copy()->day($i);
            $dateString = $currentDate->toDateString();
            
            $status = 'no_class';
            
            if ($attendances->has($dateString)) {
                $record = $attendances->get($dateString);
                $status = strtolower($record->status);
                
                if ($status === 'present' || $status === 'late') {
                    $presentCount++;
                    $status = 'present';
                } elseif ($status === 'absent') {
                    $absentCount++;
                } elseif ($status === 'leave') {
                    $leaveCount++;
                }
            } elseif ($currentDate->isSunday()) {
                $status = 'holiday';
            } else {
                // Determine if it was a batch day
                $dayName = $currentDate->format('l'); // e.g. "Monday"
                $batchDays = $student->batch->days ?? [];
                
                if (!empty($batchDays) && (in_array($dayName, $batchDays) || in_array(substr($dayName, 0, 3), $batchDays))) {
                    if ($currentDate->isPast() && !$currentDate->isToday()) {
                        $status = 'absent'; // Marked absent if it was a class day in the past and no record exists
                        $absentCount++;
                    }
                }
            }

            $calendarDays[$i] = $status;
        }

        // 3. Monthly Summary
        $totalClassDays = $presentCount + $absentCount + $leaveCount;
        $attendanceRate = $totalClassDays > 0 ? round(($presentCount / $totalClassDays) * 100) : 100;

        return response()->json([
            'status' => 'success',
            'data' => [
                'today' => $todayData,
                'calendar' => [
                    'month'       => (int)$month,
                    'year'        => (int)$year,
                    'month_label' => $date->format('F Y'),
                    'today_label' => 'TODAY - ' . strtoupper(now()->format('d M Y')),
                    'days'        => $calendarDays, // { "1": "no_class", "4": "absent", ... }
                ],
                'summary' => [
                    'label'   => strtoupper($date->format('F')) . ' OVERALL',
                    'pct'     => $attendanceRate,
                    'total'   => $totalClassDays,
                    'present' => $presentCount,
                    'absent'  => $absentCount,
                ],
            ],
        ]);
    }
}
