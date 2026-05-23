<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class StudentParent extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'parents';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'relation',
        'status',
        'fcm_token',
        'notification_settings',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'notification_settings' => 'array',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
