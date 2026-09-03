<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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
        'password',
        'institute_id',
        'fcm_token',
        'must_change_password',
        'is_login_blocked',
    ];

    protected $appends = ['profile_url'];

    protected $hidden = [
        'profile_image',
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'must_change_password' => 'boolean',
        'is_login_blocked' => 'boolean',
    ];

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

    public function departments()
    {
        return $this->belongsToMany(StaffDepartment::class, 'department_staff', 'staff_id', 'staff_department_id')->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(StaffAttendance::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function salaries()
    {
        return $this->hasMany(StaffSalary::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Scope query to only include faculty / teaching staff (who have Faculty / Teacher department).
     */
    public function scopeFaculty($query)
    {
        $facultyKeywords = ['faculty / teacher', 'faculty/teacher', 'faculty', 'teacher'];
        return $query->where(function ($q) use ($facultyKeywords) {
            $q->whereHas('departments', function ($d) use ($facultyKeywords) {
                $d->where(function ($sub) use ($facultyKeywords) {
                    foreach ($facultyKeywords as $kw) {
                        $sub->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($kw) . '%']);
                    }
                });
            })->orWhereHas('department', function ($d) use ($facultyKeywords) {
                $d->where(function ($sub) use ($facultyKeywords) {
                    foreach ($facultyKeywords as $kw) {
                        $sub->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($kw) . '%']);
                    }
                });
            });
        });
    }

    /**
     * Generate sequential Employee ID in YMD01 format (e.g. 2026082701).
     */
    public static function generateEmployeeId($instituteId, $date = null)
    {
        $dateObj = $date ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::now();
        $prefix = $dateObj->format('Ymd');

        $lastEmp = \DB::table('staff')
            ->where('institute_id', $instituteId)
            ->where('employee_id', 'like', $prefix . '%')
            ->orderByRaw('LENGTH(employee_id) DESC')
            ->orderBy('employee_id', 'desc')
            ->value('employee_id');

        $nextNumber = 1;
        if ($lastEmp) {
            $sequenceStr = substr($lastEmp, strlen($prefix));
            if (is_numeric($sequenceStr)) {
                $nextNumber = intval($sequenceStr) + 1;
            }
        }

        return $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Formatted Employee ID accessor with fallback.
     */
    public function getFormattedEmployeeIdAttribute()
    {
        if (!empty($this->employee_id)) {
            return $this->employee_id;
        }

        $dateObj = $this->created_at ? \Carbon\Carbon::parse($this->created_at) : \Carbon\Carbon::now();
        $serial = str_pad(($this->id ?: 1) % 100, 2, '0', STR_PAD_LEFT);
        return $dateObj->format('Ymd') . $serial;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($staff) {
            if (empty($staff->employee_id) && !empty($staff->institute_id)) {
                $staff->employee_id = static::generateEmployeeId($staff->institute_id, $staff->created_at ?? now());
            }
        });
    }
}
