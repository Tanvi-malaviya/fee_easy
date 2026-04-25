<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $appends = ['profile_image_url'];

    protected $fillable = [
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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
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
}
