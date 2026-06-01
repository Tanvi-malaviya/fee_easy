<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffSalary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'staff_id',
        'institute_id',
        'month',
        'year',
        'base_salary',
        'bonus',
        'deductions',
        'net_salary',
        'payment_date',
        'payment_method',
        'notes',
        'status'
    ];

    protected $hidden = ['month', 'year'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
