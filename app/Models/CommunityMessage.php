<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityMessage extends Model
{
    protected $fillable = [
        'city_name', 'sender_id', 'sender_type', 'message', 'type', 'attachment'
    ];

    public function sender()
    {
        return $this->morphTo();
    }
}
