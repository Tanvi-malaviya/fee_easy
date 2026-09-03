<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstituteAddOnPurchase extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'institute_id',
        'add_on_id',
        'status',
        'amount',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'purchased_at',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'amount' => 'float',
    ];

    protected $appends = ['is_active'];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function addOn()
    {
        return $this->belongsTo(AddOn::class, 'add_on_id');
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
