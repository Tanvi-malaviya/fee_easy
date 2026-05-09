<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    use HasFactory;

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
