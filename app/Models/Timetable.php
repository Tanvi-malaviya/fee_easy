<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'batch_id',
        'staff_id',
        'subject',
        'day_of_week',
        'start_time',
        'end_time',
        'room_no',
        'description',
        'status',
    ];

    protected $appends = [
        'formatted_start_time',
        'formatted_end_time',
        'time_slot',
        'day_name',
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
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function scopeForInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    public function scopeDay($query, $day)
    {
        return $query->where('day_of_week', strtolower($day));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? Carbon::parse($this->start_time)->format('h:i A') : '';
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? Carbon::parse($this->end_time)->format('h:i A') : '';
    }

    public function getTimeSlotAttribute()
    {
        return $this->formatted_start_time . ' - ' . $this->formatted_end_time;
    }

    public function getDayNameAttribute()
    {
        return ucfirst($this->day_of_week);
    }
}
