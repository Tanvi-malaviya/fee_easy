<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $deletedByUserId;
    public $deletedByUserType;
    public $receiverId;
    public $receiverType;

    /**
     * Create a new event instance.
     */
    public function __construct(int $deletedByUserId, string $deletedByUserType, int $receiverId, string $receiverType)
    {
        $this->deletedByUserId = $deletedByUserId;
        $this->deletedByUserType = class_basename($deletedByUserType);
        $this->receiverId = $receiverId;
        $this->receiverType = class_basename($receiverType);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->receiverType . '.' . $this->receiverId),

             new PrivateChannel('chat.' . $this->receiverId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ChatDeleted';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'deleted_by_user_id' => $this->deletedByUserId,
            'deleted_by_user_type' => $this->deletedByUserType,
            'status' => 'chat_deleted'
        ];
    }
}
