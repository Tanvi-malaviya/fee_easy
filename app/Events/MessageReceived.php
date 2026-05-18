<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceived implements ShouldBroadcastNow
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
        // Broadcast back to the SENDER of the message so their checkmarks turn into double gray checkmarks (Received/Delivered)
        $senderType = class_basename($this->message->sender_type);
        
        return [
            new PrivateChannel('chat.' . $senderType . '.' . $this->message->sender_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageReceived';
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
            'received_at' => $this->message->received_at ? $this->message->received_at->toISOString() : now()->toISOString(),
            'status' => 'received'
        ];
    }
}
