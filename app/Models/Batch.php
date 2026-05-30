<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'name',
        'subject',
        'description',
        'fees',
        'start_time',
        'end_time',
        'days',
        'classroom',
        'staff_id',
    ];

    protected $casts = [
        'days' => 'array',
    ];

    protected $hidden = [
        'max_capacity',
    ];

    public function getStartTimeAttribute($value)
    {
        return $value ? date('h:i A', strtotime($value)) : null;
    }

    public function getEndTimeAttribute($value)
    {
        return $value ? date('h:i A', strtotime($value)) : null;
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function dailyUpdates()
    {
        return $this->hasMany(DailyUpdate::class);
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
