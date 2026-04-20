<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'institute_id', 'name', 'phone', 'email', 'course_interest', 'notes', 'status', 'assigned_to'
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function assignedTeacher()
    {
        return $this->belongsTo(Teacher::class, 'assigned_to');
    }
}
