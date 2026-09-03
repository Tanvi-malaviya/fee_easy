<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class HomeworkSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'homework_id',
        'student_id',
        'status',
        'score',
        'note',
        'attachment',
        'submitted_at',
    ];

    protected $appends = ['attachment_url'];

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment ? url(Storage::url($this->attachment)) : null;
    }

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
