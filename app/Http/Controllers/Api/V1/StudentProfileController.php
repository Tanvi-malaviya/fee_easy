<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\HomeworkSubmission;
use App\Models\Homework;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    /**
     * GET /api/v1/student/profile
     *
     * Returns the full profile screen data:
     *  - header      : name, initials, avatar, batch, standard, roll, member_since
     *  - stats       : streak (consecutive days), attendance_pct (this month), assignments_pct
     *  - student_qr  : id_hash, formatted ID
     *  - info        : phone, email, parent name + phone
     */
    public function show(Request $request)
    {
        /** @var Student $student */
        $student = $request->user();
        $student->load(['batch:id,name,subject,days', 'parent:id,name,phone,relation']);

        // ── Header ─────────────────────────────────────────────────────────────

        $words    = explode(' ', trim($student->name ?? ''));
        $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', array_slice($words, 0, 2))));

        $memberSince = $student->created_at
            ? Carbon::parse($student->created_at)->format('F Y')   // "June 2024"
            : null;

        // Roll number: numeric student ID padded to 3 digits
        $rollNo = str_pad($student->id, 3, '0', STR_PAD_LEFT);

        // Avatar: real photo if available, else ui-avatars fallback
        $avatarUrl = $student->profile_image_url
            ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&color=7F9CF5&background=EBF4FF';

        $header = [
            'name'         => $student->name,
            'initials'     => $initials,
            'avatar_url'   => $avatarUrl,
            'standard'     => $student->standard,
            'batch_name'   => $student->batch->name ?? null,
            'subject'      => $student->batch->subject ?? null,
            'roll_no'      => $rollNo,
            'member_since' => $memberSince,
        ];

        // ── Stats ──────────────────────────────────────────────────────────────

        // Attendance this month — SAME logic as StudentAttendanceController
        // (virtual absent for past batch days with no record = matches attendance API pct)
        $now       = Carbon::now();
        $monthDate = Carbon::createFromDate($now->year, $now->month, 1);
        $batchDays = $student->batch->days ?? [];

        $attendancesRaw = Attendance::where('student_id', $student->id)
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->get();

        // Key by "YYYY-MM-DD" string regardless of DB datetime format
        $attendances = $attendancesRaw->keyBy(fn($r) => \Carbon\Carbon::parse($r->date)->toDateString());

        $presentCount = 0;
        $absentCount  = 0;

        for ($i = 1; $i <= $monthDate->daysInMonth; $i++) {
            $currentDate = $monthDate->copy()->day($i);
            if ($currentDate->isAfter(Carbon::today())) break;

            $dateStr = $currentDate->toDateString();

            if ($attendances->has($dateStr)) {
                $s = strtolower($attendances->get($dateStr)->status);
                if ($s === 'present' || $s === 'late') {
                    $presentCount++;
                } elseif ($s === 'absent') {
                    $absentCount++;
                }
            } elseif (!$currentDate->isSunday() && !empty($batchDays)) {
                // Virtual absent: past batch day with no record
                $dayName = $currentDate->format('l');
                if (
                    ($currentDate->isPast() && !$currentDate->isToday()) &&
                    (in_array($dayName, $batchDays) || in_array(substr($dayName, 0, 3), $batchDays))
                ) {
                    $absentCount++;
                }
            }
        }

        $monthTotal    = $presentCount + $absentCount;
        $attendancePct = $monthTotal > 0 ? round(($presentCount / $monthTotal) * 100) : 0;

        // Assignments completed %
        $batchId        = $student->batch_id;
        $totalHomeworks = $batchId ? Homework::where('batch_id', $batchId)->count() : 0;
        $completedHw    = $batchId
            ? HomeworkSubmission::where('student_id', $student->id)
                ->whereIn('homework_id', Homework::where('batch_id', $batchId)->pluck('id'))
                ->count()
            : 0;
        $assignmentsPct = $totalHomeworks > 0 ? min(100, round(($completedHw / $totalHomeworks) * 100)) : 0;

        $stats = [
            'attendance_pct'    => $attendancePct,
            'attendance_label'  => 'this month',
            'assignments_pct'   => $assignmentsPct,
            'assignments_label' => 'of assignments',
        ];

        // ── Student QR ─────────────────────────────────────────────────────────

        // Format: STU-YYYY-NNN  e.g. STU-2026-041
        $year      = Carbon::now()->year;
        $idPadded  = str_pad($student->id, 3, '0', STR_PAD_LEFT);
        $studentQr = [
            'id_hash'    => $student->id_hash ?? null,
            'display_id' => 'STU-' . $year . '-' . $idPadded,
            'hint'       => 'Show this at the door so the institute can mark attendance.',
        ];

        // ── Your Info ──────────────────────────────────────────────────────────

        $parent = $student->parent;
        $info   = [
            'phone'        => $student->phone,
            'email'        => $student->email,
            'parent_name'  => $parent->name ?? null,
            'parent_phone' => $parent->phone ?? null,
            'parent_relation' => $parent->relation ?? 'Parent',
        ];

        return response()->json([
            'status' => 'success',
            'data'   => [
                'header'     => $header,
                'stats'      => $stats,
                'student_qr' => $studentQr,
                'info'       => $info,
            ],
        ]);
    }

}

