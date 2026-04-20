<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Institute extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'institute_name',
        'logo',
        'address',
        'city',
        'state',
        'pincode',
        'website',
        'youtube',
        'instagram',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function whatsappSettings()
    {
        return $this->hasOne(InstituteWhatsappSetting::class);
    }

    public function whatsappLogs()
    {
        return $this->hasMany(WhatsappLog::class);
    }

    public function dailyUpdates()
    {
        return $this->hasMany(DailyUpdate::class);
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function teacherAttendances()
    {
        return $this->hasMany(TeacherAttendance::class);
    }

    public function expenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
