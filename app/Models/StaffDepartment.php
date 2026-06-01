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
}
