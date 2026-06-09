<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyUpdate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'batch_id',
        'institute_id',
        'student_id',
        'description',
        'date',
        'category',
        'target_type',
        'standard',
        'attachment',
        'topic',
    ];

    public function getAttachmentAttribute($value)
    {
        if (!$value) return null;
        
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        $cleanPath = ltrim($value, '/');
        
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }
        
        $encodedPath = str_replace(' ', '%20', $cleanPath);
        return url('storage/' . $encodedPath);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
