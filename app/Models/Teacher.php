<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'institute_id', 'name', 'phone', 'email', 'subject', 'designation', 'salary', 'join_date', 'status'
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function attendances()
    {
        return $this->hasMany(TeacherAttendance::class);
    }
}
