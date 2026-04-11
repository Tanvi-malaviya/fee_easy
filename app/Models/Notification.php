<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'title',
        'message',
        'image',
        'type',
        'reference_id',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
