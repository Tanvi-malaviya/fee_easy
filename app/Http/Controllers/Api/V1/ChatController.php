<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\Institute;
use App\Models\StudentParent;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Send a new message
     */
    public function send(Request $request)
    {
        $user = $request->user();
        
        // Determine receiver_type based on sender
        $receiverType = '';
        $receiverTable = 'parents';

        if ($user instanceof Institute) {
            // Suppose Institute always sends to StudentParent in this context
            $receiverType = StudentParent::class;
            $receiverTable = 'parents';
        } else if ($user instanceof StudentParent || $user instanceof \App\Models\Student) {
            // Parent or Student sends to Institute
            $receiverType = Institute::class;
            $receiverTable = 'institutes';
        } else {
            // Default or passed via request
            $receiverType = $request->input('receiver_type', StudentParent::class);
            $receiverTable = $receiverType == Institute::class ? 'institutes' : 'parents';
        }

        $request->validate([
            'receiver_id' => 'required|integer|exists:' . $receiverTable . ',id',
            'message' => 'required|string',
            'type' => 'required|in:text,image',
        ]);

        $message = ChatMessage::create([
            'sender_id' => $user->id,
            'sender_type' => get_class($user),
            'receiver_id' => $request->receiver_id,
            'receiver_type' => $receiverType,
            'message' => $request->message,
            'type' => $request->type,
        ]);

        $message->load('sender', 'receiver');

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => $message
        ], 201);
    }

    /**
     * Get the conversation list for the authenticated user
     */
    public function list(Request $request)
    {
        $user = $request->user();
        $userClass = get_class($user);

        // Get the latest message for every distinct conversation
        // A conversation is defined by the pair of sender and receiver
        
        // This query fetches all messages where the current user is either the sender or receiver
        $messages = ChatMessage::with(['sender', 'receiver'])
            ->where(function($query) use ($user, $userClass) {
                $query->where('sender_id', $user->id)
                      ->where('sender_type', $userClass);
            })
            ->orWhere(function($query) use ($user, $userClass) {
                $query->where('receiver_id', $user->id)
                      ->where('receiver_type', $userClass);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by the "other" person in the chat
        $conversations = [];
        foreach ($messages as $msg) {
            if ($msg->sender_id == $user->id && $msg->sender_type == $userClass) {
                $key = $msg->receiver_type . '_' . $msg->receiver_id;
                $other_id = $msg->receiver_id;
                $other_type = class_basename($msg->receiver_type);
                $other_name = $msg->receiver ? $msg->receiver->name : 'Unknown';
            } else {
                $key = $msg->sender_type . '_' . $msg->sender_id;
                $other_id = $msg->sender_id;
                $other_type = class_basename($msg->sender_type);
                $other_name = $msg->sender ? $msg->sender->name : 'Unknown';
            }

            if (!isset($conversations[$key])) {
                $conversations[$key] = [
                    'my_id' => $user->id,
                    'my_name' => $user->name ?? 'Unknown',
                    'my_type' => class_basename($userClass),
                    'user_id' => $other_id,
                    'user_name' => $other_name,
                    'user_type' => $other_type,
                    'latest_message' => $msg->message,
                    'type' => $msg->type,
                    'created_at' => $msg->created_at,
                    'unread_count' => 0 // To be implemented properly via count queries later
                ];
            }
            
            // Count unread if this message was sent TO the user and is NOT read
            if ($msg->receiver_id == $user->id && $msg->receiver_type == $userClass && $msg->read_at == null) {
                $conversations[$key]['unread_count']++;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => array_values($conversations)
        ]);
    }

    /**
     * Get chat messages with a specific user
     */
    public function messages(Request $request, $user_id)
    {
        $user = $request->user();
        $userClass = get_class($user);
        
        // Figure out the other class type based on the user
        if ($user instanceof Institute) {
            $otherClass = StudentParent::class;
        } else {
            $otherClass = Institute::class;
        }

        $messages = ChatMessage::with(['sender', 'receiver'])
            ->where(function($query) use ($user, $userClass, $user_id, $otherClass) {
                // Sent by me, received by other
                $query->where('sender_id', $user->id)
                      ->where('sender_type', $userClass)
                      ->where('receiver_id', $user_id)
                      ->where('receiver_type', $otherClass);
            })
            ->orWhere(function($query) use ($user, $userClass, $user_id, $otherClass) {
                // Sent by other, received by me
                $query->where('sender_id', $user_id)
                      ->where('sender_type', $otherClass)
                      ->where('receiver_id', $user->id)
                      ->where('receiver_type', $userClass);
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Mark as read where I am the receiver
        ChatMessage::where('receiver_id', $user->id)
            ->where('receiver_type', $userClass)
            ->where('sender_id', $user_id)
            ->where('sender_type', $otherClass)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ]);
    }
    
    /**
     * Delete a message
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $userClass = get_class($user);

        $message = ChatMessage::findOrFail($id);

        // Allow deletion only if the user is the sender of the message
        if ($message->sender_id !== $user->id || $message->sender_type !== $userClass) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this message.'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Message deleted successfully.'
        ]);
    }
}
