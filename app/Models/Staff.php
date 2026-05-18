<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'employee_id',
        'full_name',
        'email',
        'phone',
        'staff_role_id',
        'staff_department_id',
        'employment_type',
        'base_salary',
        'status',
        'profile_image',
        'institute_id',
        'fcm_token',
    ];

    protected $appends = ['profile_url'];

    public function getProfileUrlAttribute()
    {
        return $this->profile_image
            ? url('storage/' . $this->profile_image)
            : null;
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function role()
    {
        return $this->belongsTo(StaffRole::class, 'staff_role_id');
    }

    public function department()
    {
        return $this->belongsTo(StaffDepartment::class, 'staff_department_id');
    }

    public function attendances()
    {
        return $this->hasMany(StaffAttendance::class);
    }

    public function salaries()
    {
        return $this->hasMany(StaffSalary::class);
    }
}
