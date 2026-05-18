<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'sender_id', 'sender_type', 'receiver_id', 'receiver_type',
        'message', 'type', 'attachment', 'read_at', 'received_at',
        'deleted_by_sender', 'deleted_by_receiver'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'received_at' => 'datetime',
        'deleted_by_sender' => 'boolean',
        'deleted_by_receiver' => 'boolean',
    ];

    public function sender()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->morphTo();
    }
}
