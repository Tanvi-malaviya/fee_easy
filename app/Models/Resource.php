<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'batch_id',
        'title',
        'description',
        'file_path',
        'file_type',
        'file_size',
    ];

    protected $appends = ['file_url', 'download_url'];

    public function getDownloadUrlAttribute()
    {
        return url('/api/v1/institute/resources/' . $this->id . '/download');
    }

    public function getFileUrlAttribute()
    {
        if (!$this->file_path) return null;
        
        // Encode spaces for browser compatibility
        $encodedPath = str_replace(' ', '%20', $this->file_path);
        
        // Using url() ensures the subfolder (like /fee_easy/public) is included
        return url('storage/' . $encodedPath);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
