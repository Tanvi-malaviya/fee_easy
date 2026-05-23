<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;

use App\Models\Fee;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Notification;
use App\Models\Resource;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();
        $batch = $student->batch;

        // 1. Attendance Rate Calculation
        $attendanceRate = 0;
        $attendanceCount = Attendance::where('student_id', $student->id)->count();
        if ($attendanceCount > 0) {
            $presentCount = Attendance::where('student_id', $student->id)
                ->where('status', 'present')
                ->count();
            $attendanceRate = ($presentCount / $attendanceCount) * 100;
        }

        // 2. Today's Class details
        $todayClass = null;
        if ($batch) {
            $todayDayName = Carbon::now()->format('l'); // e.g. "Tuesday"
            $isClassToday = false;
            if (is_array($batch->days)) {
                $isClassToday = in_array($todayDayName, $batch->days) || in_array(substr($todayDayName, 0, 3), $batch->days);
            }
            
            $teacher = Teacher::where('institute_id', $student->institute_id)
                ->where('subject', $batch->subject)
                ->first();
            if (!$teacher) {
                $teacher = Teacher::where('institute_id', $student->institute_id)->first();
            }

            $todayClass = [
                'subject' => $batch->subject,
                'start_time' => $batch->start_time ? Carbon::parse($batch->start_time)->format('g:i A') : null,
                'end_time' => $batch->end_time ? Carbon::parse($batch->end_time)->format('g:i A') : null,
                'teacher_name' => $teacher ? $teacher->name : null,
                'description' => $batch->description,
                'classroom' => $batch->classroom,
                'is_today' => $isClassToday,
                'day_short' => strtoupper(Carbon::now()->format('D')), // e.g. "TUE"
                'day_label' => $isClassToday ? "TODAY'S CLASS" : "NEXT CLASS",
            ];
        }

        // 3. Weekly Attendance Dots (M T W T F S S)
        $startOfWeek = Carbon::now()->startOfWeek();
        $weekAttendances = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$startOfWeek->toDateString(), Carbon::now()->endOfWeek()->toDateString()])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('N'); // 1 (Mon) to 7 (Sun)
            });

        $weekDays = [];
        $dayNamesShort = [1 => 'M', 2 => 'T', 3 => 'W', 4 => 'T', 5 => 'F', 6 => 'S', 7 => 'S'];
        for ($i = 1; $i <= 7; $i++) {
            $status = "";
            if (isset($weekAttendances[$i])) {
                $status = strtolower($weekAttendances[$i]->status) ?: ""; // present, absent, late, leave
            }

            $weekDays[] = [
                'day' => $dayNamesShort[$i],
                'status' => $status,
                'date' => $startOfWeek->copy()->addDays($i - 1)->toDateString(),
            ];
        }

        // 4. Today's Assignments (Homeworks)
        $todayAssignments = [];
        if ($student->batch_id) {
            $todayAssignments = Homework::where('batch_id', $student->batch_id)
                ->orderBy('due_date', 'asc')
                ->take(5)
                ->get()
                ->map(function ($homework) use ($student) {
                    $submission = HomeworkSubmission::where('homework_id', $homework->id)
                        ->where('student_id', $student->id)
                        ->first();
                    
                    $dueDate = Carbon::parse($homework->due_date);
                    $diffInDays = Carbon::today()->diffInDays($dueDate, false);
                    
                    $dueLabel = match($diffInDays) {
                        0 => 'Today',
                        1 => 'Tomorrow',
                        -1 => 'Yesterday',
                        default => $dueDate->format('j M'),
                    };

                    return [
                        'id' => $homework->id,
                        'title' => $homework->title,
                        'description' => $homework->description,
                        'subject' => $homework->batch->subject ?? 'General',
                        'due_date' => $homework->due_date,
                        'status' => $submission ? 'Submitted' : 'Pending',
                    ];
                });
        }

        // 5. Today's Attendance Details
        $todayAttendance = Attendance::where('student_id', $student->id)
            ->where('date', Carbon::today()->toDateString())
            ->first();

        $todayAttendanceData = null;
        if ($todayAttendance) {
            $checkInTime = $todayAttendance->created_at ? Carbon::parse($todayAttendance->created_at)->format('g:i A') : '8:00 AM';
            $todayAttendanceData = [
                'status' => ucfirst($todayAttendance->status), // Present, Absent, Late
                'text' => "Checked in at {$checkInTime} · " . ($batch->subject ?? 'Class'),
            ];
        } else {
            $todayAttendanceData = [
                'status' => 'Not Marked',
                'text' => 'Attendance not marked yet for today.',
            ];
        }

      

        // 7. Study Material This Week
        $studyMaterials = [];
        if ($student->batch_id) {
            $studyMaterials = Resource::where('batch_id', $student->batch_id)
                ->orderByDesc('created_at')
                ->take(5)
                ->get()
                ->map(function ($resource) {
                    $createdDate = Carbon::parse($resource->created_at);
                    $diffInDays = Carbon::today()->diffInDays($createdDate, false);
                    
                    $timeLabel = match($diffInDays) {
                        0 => 'Today',
                        -1 => 'Yesterday',
                        default => $createdDate->format('D'),
                    };

                    return [
                        'id' => $resource->id,
                        'title' => $resource->title,
                        'description' => $resource->description,
                        'subject' => $resource->batch->subject ?? 'General',
                        'file_size' => ($resource->file_size && is_numeric($resource->file_size))
                            ? round((float)$resource->file_size / 1024 / 1024, 2) . ' MB'
                            : ($resource->file_size ?: '1 file'),
                        'file_type' => $resource->file_type,
                        'file_url' => $resource->file_url,
                        'download_url' => $resource->download_url,
                        'time_label' => $timeLabel,
                    ];
                });
        }

        // 8. Pending Fees Details
        // 9. Consolidated Fees Summary
        $totalFees = (float) ($student->monthly_fee > 0 ? $student->monthly_fee : Fee::where('student_id', $student->id)->sum('total_amount'));
        $paidFees = (float)Fee::where('student_id', $student->id)->sum('paid_amount');
        $dueFees = max(0.0, $totalFees - $paidFees);

        // 8. Pending Fees Details
        $runningDue = $dueFees;
        $pendingFees = Fee::where('student_id', $student->id)
            ->where(function ($query) {
                $query->where('status', '!=', 'Paid')
                      ->orWhereRaw('total_amount > paid_amount');
            })
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($fee) use (&$runningDue) {
                $dueDate = Carbon::parse($fee->date);
                $rawDue = (float)$fee->total_amount - (float)$fee->paid_amount;
                $dueAmount = min($runningDue, $rawDue);
                $runningDue -= $dueAmount;
                
                return [
                    'id' => $fee->id,
                    'month_year' => $dueDate->format('F Y'),
                    'due_amount' => max(0.0, $dueAmount),
                    'status' => ucfirst($fee->status),
                ];
            })
            ->filter(function($f) {
                return $f['due_amount'] > 0;
            })
            ->values()
            ->toArray();

        // Fallback: If there is a due amount but no pending fees in the list, or if they don't cover the due amount,
        // dynamically append a pending/partial fee for the remaining amount
        $pendingSum = array_sum(array_column($pendingFees, 'due_amount'));
        if ($dueFees > $pendingSum) {
            $remainingDue = $dueFees - $pendingSum;
            $currentMonthStart = Carbon::now()->startOfMonth();
            $dueDate = $currentMonthStart->copy()->addDays(24); // 25th of the month
            
            $pendingFees[] = [
                'id' => null,
                'month_year' => Carbon::now()->format('F Y'),
                'due_amount' => $remainingDue,
                'status' => $paidFees > 0 ? 'Partial' : 'Pending',
            ];
        }

        $totalFeesFormatted = number_format($totalFees, 2, '.', '');
        $paidFeesFormatted = number_format($paidFees, 2, '.', '');
        $dueFeesFormatted = number_format($dueFees, 2, '.', '');

        return response()->json([
            'status' => 'success',
            'data' => [
                'student_name' => $student->name,
                'batch_id' => $student->batch_id,
                'batch_name' => $student->batch->name ?? null,
                'attendance_rate' => round($attendanceRate, 2),
                'total_fees' => $totalFeesFormatted,
                'paid_fees' => $paidFeesFormatted,
                'due_fees' => $dueFeesFormatted,
                
                // New widgets mapping the requested dashboard design
                'today_class' => $todayClass,
                'week_attendance_days' => $weekDays,
                'today_assignments' => $todayAssignments,
                'today_attendance' => $todayAttendanceData,
             
                'study_materials' => $studyMaterials,
                'pending_fees' => $pendingFees,
            ],
        ]);
    }
}

