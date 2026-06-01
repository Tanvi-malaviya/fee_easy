<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffAttendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'staff_id',
        'institute_id',
        'date',
        'status',
        'note'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
