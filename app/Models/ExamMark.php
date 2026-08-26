<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'marks_obtained',
        'is_absent',
        'remarks',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'is_absent' => 'boolean',
    ];

    protected $appends = [
        'percentage',
        'is_pass',
        'grade',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getPercentageAttribute()
    {
        if ($this->is_absent || $this->marks_obtained === null) {
            return null;
        }

        $exam = $this->relationLoaded('exam') ? $this->getRelation('exam') : \Illuminate\Support\Facades\DB::table('exams')->where('id', $this->exam_id)->first();
        if (!$exam || (float) $exam->total_marks <= 0) {
            return null;
        }

        return round(((float) $this->marks_obtained / (float) $exam->total_marks) * 100, 2);
    }

    public function getIsPassAttribute()
    {
        if ($this->is_absent || $this->marks_obtained === null) {
            return false;
        }

        $exam = $this->relationLoaded('exam') ? $this->getRelation('exam') : \Illuminate\Support\Facades\DB::table('exams')->where('id', $this->exam_id)->first();
        if (!$exam) {
            return false;
        }

        return (float) $this->marks_obtained >= (float) $exam->passing_marks;
    }

    public function getGradeAttribute()
    {
        $pct = $this->percentage;
        if ($pct === null) {
            return $this->is_absent ? 'ABSENT' : '-';
        }

        if ($pct >= 90) return 'A+';
        if ($pct >= 80) return 'A';
        if ($pct >= 70) return 'B+';
        if ($pct >= 60) return 'B';
        if ($pct >= 50) return 'C';
        if ($pct >= 35) return 'D';
        return 'F';
    }
}
