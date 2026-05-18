<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // Broadcast back to the SENDER of the message so their checkmarks turn blue/read
        $senderType = class_basename($this->message->sender_type);
        
        return [
            new PrivateChannel('chat.' . $senderType . '.' . $this->message->sender_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageRead';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'message_id' => $this->message->id,
            'receiver_id' => $this->message->receiver_id,
            'receiver_type' => class_basename($this->message->receiver_type),
            'read_at' => $this->message->read_at ? $this->message->read_at->toISOString() : now()->toISOString(),
            'status' => 'read'
        ];
    }
}
