<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function staff()
    {
        return $this->hasMany(Staff::class, 'staff_department_id');
    }

    public function staffMembers()
    {
        return $this->belongsToMany(Staff::class, 'department_staff', 'staff_department_id', 'staff_id')->withTimestamps();
    }

    public function isProtected(): bool
    {
        $protected = ['faculty / teacher', 'faculty/teacher', 'faculty', 'teacher'];
        return in_array(strtolower(trim($this->name)), $protected);
    }
}
