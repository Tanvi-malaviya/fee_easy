<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'institute_id',
        'topic',
        'description',
        'date',
        'category',
        'target_type',
        'standard',
        'attachment',
        'recipient',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
