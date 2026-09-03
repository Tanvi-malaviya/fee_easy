<?php

namespace App\Services;

use App\Models\Exam;
use Illuminate\Support\Facades\Log;

class ExamReportNotifier
{
    /**
     * Auto-email the academic report PDF to every student who has a mark recorded
     * for this exam, the moment its results are published (status -> completed).
     */
    public static function sendForExam(Exam $exam): int
    {
        $institute = $exam->institute;
        if (!$institute) {
            return 0;
        }

        $students = $exam->marks()->with('student')->get()
            ->pluck('student')
            ->filter()
            ->unique('id');

        $sent = 0;
        foreach ($students as $student) {
            try {
                if (StudentReportService::emailReport($student, $institute)) {
                    $sent++;
                }
            } catch (\Throwable $e) {
                Log::error("Failed to auto-send exam report for student {$student->id} (exam {$exam->id}): " . $e->getMessage());
            }
        }

        return $sent;
    }
}
