<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'institute_id', 'notable_id', 'notable_type', 'content'
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function notable()
    {
        return $this->morphTo();
    }
}
