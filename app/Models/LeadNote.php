<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_id',
        'institute_id',
        'title',
        'note'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
