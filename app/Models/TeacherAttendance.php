<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherAttendance extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'institute_id', 'teacher_id', 'date', 'status', 'remarks'
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
