<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'full_name',
        'phone',
        'email',
        'address',
        'course_selection',
        'reference',
        'referer',
        'notes',
        'status',
        'converted_student_id',
        'converted_at',
    ];

    protected $casts = [
        'converted_at' => 'datetime',
    ];

    public function notes()
    {
        return $this->hasMany(LeadNote::class)->orderBy('created_at', 'desc');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function convertedStudent()
    {
        return $this->belongsTo(Student::class, 'converted_student_id');
    }
}
