<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'transaction_id',
        'screenshot',
        'message',
        'status',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
