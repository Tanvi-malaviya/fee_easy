<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstituteWhatsappSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'access_token',
        'phone_number_id',
        'business_account_id',
        'is_active',
        'last_verified_at',
    ];

    protected $casts = [
        'last_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
