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
        'max_capacity',
    ];

    protected $casts = [
        'days' => 'array',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
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
}
