<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'institute_id',
        'total_amount',
        'paid_amount',
        'status',
        'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
