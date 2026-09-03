<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DemoRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'phone',
        'institute_name',
        'email',
        'designation',
        'status',
        'nurture_stage',
        'nurture_last_sent_at',
    ];

    protected $casts = [
        'nurture_last_sent_at' => 'datetime',
    ];
}
