<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeworkSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'homework_id',
        'student_id',
        'status',
        'score',
        'submitted_at',
    ];

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
