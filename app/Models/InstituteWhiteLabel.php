<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InstituteWhiteLabel extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'institute_id',
        'status',
        'amount',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'purchased_at',
        'app_name',
        'app_logo',
        'primary_color',
        'secondary_color',
        'android_package_id',
        'ios_bundle_id',
        'admin_confirmed_at',
        'admin_notes',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'admin_confirmed_at' => 'datetime',
    ];

    protected $appends = [
        'app_logo_url',
        'is_active',
        'branding_complete',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function getAppLogoUrlAttribute()
    {
        return $this->app_logo ? url(Storage::url($this->app_logo)) : null;
    }

    public function getIsActiveAttribute()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getBrandingCompleteAttribute()
    {
        return !empty($this->app_name) && !empty($this->app_logo);
    }
}
