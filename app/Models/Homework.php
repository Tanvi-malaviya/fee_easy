<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homework extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'homeworks';

    protected $fillable = [
        'batch_id',
        'institute_id',
        'title',
        'description',
        'due_date',
        'attachment',
    ];

    public function getAttachmentAttribute($value)
    {
        if (!$value) return null;
        // Encode spaces for browser compatibility
        $encodedPath = str_replace(' ', '%20', $value);
        return url('storage/' . $encodedPath);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function submissions()
    {
        return $this->hasMany(HomeworkSubmission::class);
    }
}
