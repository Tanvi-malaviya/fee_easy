<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $appends = ['profile_image_url', 'is_birthday_today'];

    protected $fillable = [
        'enrollment_id',
        'name',
        'email',
        'phone',
        'password',
        'institute_id',
        'parent_id',
        'batch_id',
        'standard',
        'dob',
        'guardian_name',
        'monthly_fee',
        'status',
        'id_hash',
        'profile_image',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'pincode',
        'fcm_token',
        'notification_settings',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'profile_image',
    ];

    protected $casts = [
        'password' => 'hashed',
        'notification_settings' => 'array',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function parent()
    {
        return $this->belongsTo(StudentParent::class, 'parent_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function homeworkSubmissions()
    {
        return $this->hasMany(HomeworkSubmission::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->profile_image);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getIsBirthdayTodayAttribute()
    {   


        if ($this->dob) {
            $dob = \Carbon\Carbon::parse($this->dob);
            return $dob->format('m-d') === \Carbon\Carbon::today()->format('m-d');
        }
        return false;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            if (empty($student->enrollment_id)) {
                $year = date('Y');
                $institute = $student->institute ?? \App\Models\Institute::find($student->institute_id);
                $code = $institute ? ($institute->institute_code ?? 'INST') : 'INST';
                $prefix = $year . $code;

                // Find the last serial number for this prefix
                $lastEnrollmentId = \DB::table('students')
                    ->where('institute_id', $student->institute_id)
                    ->where('enrollment_id', 'like', $prefix . '%')
                    ->orderByRaw('LENGTH(enrollment_id) DESC')
                    ->orderBy('enrollment_id', 'desc')
                    ->value('enrollment_id');

                $nextNumber = 1;
                if ($lastEnrollmentId) {
                    $sequenceStr = substr($lastEnrollmentId, strlen($prefix));
                    if (is_numeric($sequenceStr)) {
                        $nextNumber = intval($sequenceStr) + 1;
                    }
                }

                $student->enrollment_id = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
