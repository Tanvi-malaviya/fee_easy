<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'batch_id',
        'staff_id',
        'title',
        'subject',
        'exam_type',
        'exam_date',
        'start_time',
        'end_time',
        'total_marks',
        'passing_marks',
        'description',
        'status',
    ];

    protected $casts = [
        // Plain Y-m-d serialization — the default 'date' cast round-trips
        // through UTC on JSON encode, which shifts the calendar day back
        // one for any app timezone ahead of UTC (e.g. Asia/Kolkata).
        'exam_date' => 'date:Y-m-d',
        'total_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
    ];

    protected $appends = [
        'stats',
        'formatted_date',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function marks()
    {
        return $this->hasMany(ExamMark::class);
    }

    public function getFormattedDateAttribute()
    {
        return $this->exam_date ? $this->exam_date->format('d M, Y') : null;
    }

    /**
     * Compute statistics for this exam.
     */
    public function getStatsAttribute()
    {
        $totalStudentsInBatch = $this->batch ? $this->batch->students()->count() : 0;
        
        $marks = \Illuminate\Support\Facades\DB::table('exam_marks')
            ->where('exam_id', $this->id)
            ->get();

        $enteredCount = $marks->count();
        $absentCount = $marks->where('is_absent', 1)->count();
        $presentMarks = $marks->where('is_absent', 0)->whereNotNull('marks_obtained');
        $presentCount = $presentMarks->count();

        $highest = $presentMarks->max('marks_obtained');
        $lowest = $presentMarks->min('marks_obtained');
        $average = $presentCount > 0 ? round($presentMarks->avg('marks_obtained'), 2) : 0;

        $passing = (float) $this->passing_marks;
        $passedCount = $presentMarks->filter(function ($m) use ($passing) {
            return (float) $m->marks_obtained >= $passing;
        })->count();
        $failedCount = $presentCount - $passedCount;

        $passPercentage = $presentCount > 0 ? round(($passedCount / $presentCount) * 100, 1) : 0;

        return [
            'total_students' => $totalStudentsInBatch,
            'marks_entered_count' => $enteredCount,
            'present_count' => $presentCount,
            'absent_count' => $absentCount,
            'passed_count' => $passedCount,
            'failed_count' => $failedCount,
            'pass_percentage' => $passPercentage,
            'highest_marks' => $highest !== null ? (float) $highest : null,
            'lowest_marks' => $lowest !== null ? (float) $lowest : null,
            'average_marks' => (float) $average,
        ];
    }
}
