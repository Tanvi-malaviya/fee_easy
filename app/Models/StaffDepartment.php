<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDepartment extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function staff()
    {
        return $this->hasMany(Staff::class, 'staff_department_id');
    }
}
