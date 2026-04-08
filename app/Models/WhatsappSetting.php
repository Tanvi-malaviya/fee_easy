<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'api_key',
        'phone_number',
        'status',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
