<?php

namespace App\Services;

use App\Models\Institute;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class StudentReportService
{
    /**
     * Build the full report data payload for a student (financial, attendance, exams, homework).
     */
    public static function getData(Student $student): array
    {
        $student->loadMissing([
            'batch',
            'parent',
            'fees.payments',
            'attendance.batch',
            'homeworkSubmissions.homework',
            'examMarks.exam'
        ]);

        // Calculate balance (Monthly Fee - Total Payments)
        $totalPaid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
        $balance = max(0, ($student->monthly_fee ?? 0) - $totalPaid);

        // Fee status calculation
        $feeStatus = 'Full Paid';
        if (!$student->monthly_fee || $student->monthly_fee == 0) {
            $feeStatus = 'No Fee';
        } elseif ($balance >= $student->monthly_fee) {
            $feeStatus = 'Pending';
        } elseif ($balance > 0) {
            $feeStatus = 'Partial Dues';
        }

        // Attendance stats & records
        $totalDays = $student->attendance()->count();
        $presentDays = $student->attendance()->where('status', 'Present')->count();
        $absentDays = $student->attendance()->where('status', 'Absent')->count();
        $lateDays = $student->attendance()->where('status', 'Late')->count();
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
        $attendanceRecords = $student->attendance()->with('batch')->orderBy('date', 'desc')->get()->map(function ($att) {
            return [
                'id' => $att->id,
                'date' => $att->date,
                'formatted_date' => \Carbon\Carbon::parse($att->date)->format('M d, Y'),
                'day' => \Carbon\Carbon::parse($att->date)->format('l'),
                'batch_name' => $att->batch ? $att->batch->name : 'N/A',
                'status' => $att->status,
            ];
        });

        // Homework stats & submissions
        $homeworkSubmissions = $student->homeworkSubmissions()->with('homework')->latest()->get();
        $averageGrade = $homeworkSubmissions->whereNotNull('score')->avg('score');
        $averageGrade = $averageGrade ? round($averageGrade, 1) : 0;
        $submittedHomeworkCount = $homeworkSubmissions->whereIn('status', ['Submitted', 'Reviewed'])->count();

        $homeworkList = $homeworkSubmissions->map(function ($sub) use ($student) {
            $hw = $sub->homework;
            return [
                'id' => $sub->id,
                'homework_id' => $sub->homework_id,
                'title' => $hw ? $hw->title : 'Homework #' . $sub->homework_id,
                'subject' => ($hw && $hw->subject) ? $hw->subject : ($student->batch ? $student->batch->subject : 'General'),
                'due_date' => ($hw && $hw->due_date) ? \Carbon\Carbon::parse($hw->due_date)->format('M d, Y') : '—',
                'status' => $sub->status ?: 'Submitted',
                'score' => !is_null($sub->score) ? (float) $sub->score : null,
                'feedback' => $sub->feedback ?: '—',
            ];
        });

        // Exams & Marks stats
        $examMarks = $student->examMarks()->with('exam')->get()->sortByDesc(function ($mark) {
            return $mark->exam->date ?? $mark->created_at;
        });
        $totalExams = $examMarks->count();
        $passedExams = $examMarks->filter(function ($mark) {
            return !$mark->is_absent && $mark->exam && $mark->marks_obtained >= ($mark->exam->passing_marks ?? 0);
        })->count();
        $failedExams = $examMarks->filter(function ($mark) {
            return !$mark->is_absent && $mark->exam && $mark->marks_obtained < ($mark->exam->passing_marks ?? 0);
        })->count();
        $absentExams = $examMarks->where('is_absent', true)->count();
        $averageExamMarks = $examMarks->where('is_absent', false)->count() > 0
            ? round($examMarks->where('is_absent', false)->avg('marks_obtained'), 1)
            : 0;

        $examList = $examMarks->values()->map(function ($mark) use ($student) {
            $exam = $mark->exam;
            $passingMarks = $exam ? (float) $exam->passing_marks : 0;
            $totalMarks = $exam ? (float) $exam->total_marks : 100;
            $scored = $mark->is_absent ? 0 : (float) $mark->marks_obtained;
            $percentage = $totalMarks > 0 ? round(($scored / $totalMarks) * 100) : 0;
            $isPassed = !$mark->is_absent && $scored >= $passingMarks;

            return [
                'id' => $mark->id,
                'exam_id' => $mark->exam_id,
                'title' => $exam ? $exam->title : 'Exam #' . $mark->exam_id,
                'subject' => ($exam && $exam->subject) ? $exam->subject : ($student->batch ? $student->batch->subject : 'General'),
                'date' => ($exam && $exam->exam_date) ? \Carbon\Carbon::parse($exam->exam_date)->format('M d, Y') : $mark->created_at->format('M d, Y'),
                'marks_scored' => $scored,
                'total_marks' => $totalMarks,
                'passing_marks' => $passingMarks,
                'percentage' => $percentage,
                'is_absent' => (bool) $mark->is_absent,
                'is_passed' => $isPassed,
                'remarks' => $mark->remarks ?: '—',
            ];
        });

        // Fee History
        $feeList = $student->fees->sortByDesc('date')->values()->map(function ($fee) {
            $remaining = max(0, $fee->total_amount - $fee->paid_amount);
            return [
                'id' => $fee->id,
                'month_year' => \Carbon\Carbon::parse($fee->date)->format('M Y'),
                'total_amount' => (float) $fee->total_amount,
                'paid_amount' => (float) $fee->paid_amount,
                'remaining' => (float) $remaining,
                'status' => $fee->status ?: ($remaining == 0 ? 'Paid' : 'Unpaid'),
                'receipt_url' => route('institute.fees.receipts.show', $fee->id),
            ];
        });

        return [
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'enrollment_id' => $student->enrollment_id,
                'email' => $student->email,
                'phone' => $student->phone,
                'standard' => $student->standard,
                'monthly_fee' => (float) ($student->monthly_fee ?? 0),
                'guardian_name' => $student->guardian_name ?: 'N/A',
                'dob' => $student->dob ? \Carbon\Carbon::parse($student->dob)->format('M d, Y') : 'N/A',
                'admission_date' => $student->created_at->format('M d, Y'),
                'profile_image_url' => $student->profile_image_url,
                'address' => trim(implode(', ', array_filter([
                    $student->address_line_1,
                    $student->address_line_2,
                    $student->city,
                    $student->state,
                    $student->pincode ? "- {$student->pincode}" : null
                ]))) ?: 'N/A',
                'batch_id' => $student->batch_id,
                'batch_name' => $student->batch ? $student->batch->name : 'Unassigned',
                'profile_url' => route('institute.students.show', $student->id),
            ],
            'financial' => [
                'balance' => $balance,
                'total_paid' => $totalPaid,
                'monthly_fee' => (float) ($student->monthly_fee ?? 0),
                'fee_status' => $feeStatus,
                'fees_history' => $feeList,
            ],
            'attendance' => [
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'late_days' => $lateDays,
                'percentage' => $attendancePercentage,
                'records' => $attendanceRecords,
            ],
            'exams' => [
                'total_exams' => $totalExams,
                'passed_exams' => $passedExams,
                'failed_exams' => $failedExams,
                'absent_exams' => $absentExams,
                'average_score' => $averageExamMarks,
                'list' => $examList,
            ],
            'homework' => [
                'total_submissions' => $homeworkSubmissions->count(),
                'submitted_count' => $submittedHomeworkCount,
                'average_grade' => $averageGrade,
                'list' => $homeworkList,
            ]
        ];
    }

    /**
     * Render the student report PDF (same view used for manual export/email).
     */
    public static function buildPdf(Student $student, Institute $institute): string
    {
        $reportData = static::getData($student);

        $pdf = Pdf::loadView('institute.reports.student_pdf', [
            'institute' => $institute,
            'student' => $reportData['student'],
            'financial' => $reportData['financial'],
            'attendance' => $reportData['attendance'],
            'exams' => $reportData['exams'],
            'homework' => $reportData['homework'],
        ]);

        return $pdf->output();
    }

    /**
     * Generate and email the PDF report to the student, if they have an email on file.
     */
    public static function emailReport(Student $student, Institute $institute): bool
    {
        if (empty($student->email)) {
            return false;
        }

        try {
            $pdfOutput = static::buildPdf($student, $institute);
            $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $student->name);
            $fileName = "Student_Report_{$safeName}_{$student->id}.pdf";

            InstituteMailService::send(
                $institute,
                $student->email,
                new \App\Mail\StudentReportMail($student, $institute, $pdfOutput, $fileName)
            );

            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to email student report to {$student->email}: " . $e->getMessage());
            return false;
        }
    }
}
