<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstituteWebsiteContent extends Model
{
    use HasFactory;

    protected $table = 'institute_website_contents';

    protected $fillable = [
        'institute_id',
        'hero_slides',
        'about_vision',
        'about_mission',
        'about_values',
        'achievements',
        'gallery',
        'events',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'youtube',
    ];

    protected $casts = [
        'hero_slides' => 'array',
        'about_vision' => 'array',
        'about_mission' => 'array',
        'about_values' => 'array',
        'achievements' => 'array',
        'gallery' => 'array',
        'events' => 'array',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
