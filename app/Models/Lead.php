<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'full_name',
        'phone',
        'email',
        'address',
        'course_selection',
        'reference',
        'referer',
        'status'
    ];

    public function notes()
    {
        return $this->hasMany(LeadNote::class)->orderBy('created_at', 'desc');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
