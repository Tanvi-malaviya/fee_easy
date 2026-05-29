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
        // Automatically sanitize request keys to trim whitespaces/tabs (defensive fix for Postman/client inputs)
        $normalizedInputs = [];
        foreach ($request->all() as $key => $value) {
            $normalizedInputs[trim($key)] = $value;
        }
        $request->replace($normalizedInputs);

        foreach ($request->allFiles() as $key => $file) {
            $trimmedKey = trim($key);
            if ($trimmedKey !== $key) {
                $request->files->set($trimmedKey, $file);
                $request->files->remove($key);
            }
        }

        $user = auth('institute')->user() ?? auth('sanctum')->user() ?? $request->user();

        // Determine receiver_type based on sender
        $receiverType = $request->input('receiver_type');
        $receiverTable = 'parents';

        if ($receiverType) {
            if ($receiverType === \App\Models\Institute::class) {
                $receiverTable = 'institutes';
            } elseif ($receiverType === \App\Models\Staff::class) {
                $receiverTable = 'staff';
            } elseif ($receiverType === \App\Models\Student::class) {
                $receiverTable = 'students';
            } else {
                $receiverTable = 'parents';
            }
        } else {
            if ($user instanceof Institute) {
                $receiverType = StudentParent::class;
                $receiverTable = 'parents';
            } else {
                $receiverType = Institute::class;
                $receiverTable = 'institutes';
            }
        }

        $maxSize = 10240; // Default 10MB
        if ($request->input('type') === 'image') {
            $maxSize = 2048; // 2MB
        } elseif ($request->input('type') === 'video') {
            $maxSize = 20480; // 20MB
        } elseif ($request->input('type') === 'audio') {
            $maxSize = 10240; // 10MB
        }

        $request->validate([
            'receiver_id' => 'required|integer|exists:' . $receiverTable . ',id',
            'message' => 'nullable|string',
            'type' => 'required|in:text,image,video,document,audio,location,contact',
            'attachment' => 'nullable|file|max:' . $maxSize,
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('chat_attachments', 'public');
        }

        $message = ChatMessage::create([
            'sender_id' => $user->id,
            'sender_type' => get_class($user),
            'receiver_id' => $request->receiver_id,
            'receiver_type' => $receiverType,
            'message' => $request->message,
            'type' => $request->type,
            'attachment' => $attachmentPath,
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        $message->load('sender', 'receiver');

        // Trigger real-time FCM push notification to receiver mobile phone
        if ($message->receiver && !empty($message->receiver->fcm_token)) {
            $senderName = $user->name ?? $user->full_name ?? $user->institute_name ?? 'Someone';

            // Set descriptive body for non-text messages
            $body = $message->message;
            if ($message->type !== 'text') {
                $body = match ($message->type) {
                    'image' => '📷 Sent an image',
                    'video' => '🎥 Sent a video',
                    'document' => '📄 Sent a document',
                    'audio' => '🎵 Sent an audio message',
                    'location' => '📍 Shared a location',
                    'contact' => '👤 Shared a contact',
                    default => 'New message',
                };
            }

            (new \App\Services\FCMService())->sendToUser(
                $message->receiver,
                $senderName,
                $body,
                [
                    'type' => 'chat',
                    'chat_id' => (string) $message->id,
                    'sender_id' => (string) $user->id,
                    'sender_type' => class_basename($user),
                ]
            );
        }

        $formattedMessage = [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'sender_type' => class_basename($message->sender_type),
            'receiver_id' => $message->receiver_id,
            'receiver_type' => class_basename($message->receiver_type),
            'message' => $message->message,
            'type' => $message->type,
            'attachment' => $message->attachment ? url('storage/' . $message->attachment) : null,
            'read_at' => $message->read_at,
            'received_at' => $message->received_at,
            'created_at' => $message->created_at,
            'updated_at' => $message->updated_at,
            'sender' => $message->sender ? [
                'id' => $message->sender->id,
                'name' => $message->sender->name ?? $message->sender->full_name ?? $message->sender->institute_name ?? 'Unknown',
                'logo' => $message->sender->logo ?? $message->sender->profile_image ?? null,
                'type' => class_basename($message->sender_type)
            ] : null,
            'receiver' => $message->receiver ? [
                'id' => $message->receiver->id,
                'name' => $message->receiver->name ?? $message->receiver->full_name ?? $message->receiver->institute_name ?? 'Unknown',
                'logo' => $message->receiver->logo ?? $message->receiver->profile_image ?? null,
                'type' => class_basename($message->receiver_type)
            ] : null
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => $formattedMessage
        ], 201);
    }

    /**
     * Get the conversation list for the authenticated user
     */
    public function list(Request $request)
    {
        $user = auth('institute')->user() ?? auth('sanctum')->user() ?? $request->user();
        $userClass = get_class($user);

        // Get the latest message for every distinct conversation
        // A conversation is defined by the pair of sender and receiver

        // This query fetches all messages where the current user is either the sender or receiver
        $messages = ChatMessage::with(['sender', 'receiver'])
            ->where(function ($query) use ($user, $userClass) {
                $query->where('sender_id', $user->id)
                    ->where('sender_type', $userClass)
                    ->where('deleted_by_sender', false);
            })
            ->orWhere(function ($query) use ($user, $userClass) {
                $query->where('receiver_id', $user->id)
                    ->where('receiver_type', $userClass)
                    ->where('deleted_by_receiver', false);
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
                $other_name = $msg->receiver ? ($msg->receiver->name ?? $msg->receiver->full_name ?? $msg->receiver->institute_name ?? 'Unknown') : 'Unknown';
                $other_logo = $msg->receiver ? ($msg->receiver->logo ?? $msg->receiver->profile_image ?? null) : null;
            } else {
                $key = $msg->sender_type . '_' . $msg->sender_id;
                $other_id = $msg->sender_id;
                $other_type = class_basename($msg->sender_type);
                $other_name = $msg->sender ? ($msg->sender->name ?? $msg->sender->full_name ?? $msg->sender->institute_name ?? 'Unknown') : 'Unknown';
                $other_logo = $msg->sender ? ($msg->sender->logo ?? $msg->sender->profile_image ?? null) : null;
            }

            if (!isset($conversations[$key])) {
                $conversations[$key] = [
                    'my_id' => $user->id,
                    'my_type' => class_basename($userClass),
                    'user_id' => $other_id,
                    'user_type' => $other_type,
                    'user_name' => $other_name,
                    'user_logo' => $other_logo,
                    'latest_message' => $msg->message,
                    'type' => $msg->type,
                    'created_at' => $msg->created_at,
                    'unread_count' => ChatMessage::where('sender_id', $other_id)
                        ->where('sender_type', $msg->sender_type == $userClass ? $msg->receiver_type : $msg->sender_type)
                        ->where('receiver_id', $user->id)
                        ->where('receiver_type', $userClass)
                        ->where('deleted_by_receiver', false)
                        ->whereNull('read_at')
                        ->count(),
                ];
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
        $user = auth('institute')->user() ?? auth('sanctum')->user() ?? $request->user();
        $userClass = get_class($user);

        $otherType = $request->query('type'); // 'Staff', 'Student', 'Institute', 'StudentParent'

        if ($otherType) {
            $otherClass = $otherType == 'Staff' ? \App\Models\Staff::class :
                ($otherType == 'Student' ? \App\Models\Student::class :
                    ($otherType == 'Institute' ? \App\Models\Institute::class : \App\Models\StudentParent::class));
        } else {
            // Fallback for older API calls
            $otherClass = $user instanceof Institute ? \App\Models\StudentParent::class : \App\Models\Institute::class;
        }

        // Mark as read where I am the receiver BEFORE loading messages
        $unreadMessages = ChatMessage::where('receiver_id', $user->id)
            ->where('receiver_type', $userClass)
            ->where('sender_id', $user_id)
            ->where('sender_type', $otherClass)
            ->whereNull('read_at')
            ->get();

        if ($unreadMessages->isNotEmpty()) {
            ChatMessage::whereIn('id', $unreadMessages->pluck('id'))
                ->update(['read_at' => now()]);

            foreach ($unreadMessages as $msg) {
                $msg->read_at = now();
                broadcast(new \App\Events\MessageRead($msg))->toOthers();
            }
        }

        $perPage = $request->query('per_page', 20);

        $paginator = ChatMessage::with(['sender', 'receiver'])
            ->where(function ($query) use ($user, $userClass, $user_id, $otherClass) {
                // Sent by me, received by other
                $query->where('sender_id', $user->id)
                    ->where('sender_type', $userClass)
                    ->where('receiver_id', $user_id)
                    ->where('receiver_type', $otherClass)
                    ->where('deleted_by_sender', false);
            })
            ->orWhere(function ($query) use ($user, $userClass, $user_id, $otherClass) {
                // Sent by other, received by me
                $query->where('sender_id', $user_id)
                    ->where('sender_type', $otherClass)
                    ->where('receiver_id', $user->id)
                    ->where('receiver_type', $userClass)
                    ->where('deleted_by_receiver', false);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Reverse the items so the oldest comes first chronologically in the page dataset
        $reversedMessages = collect($paginator->items())->reverse()->values();

        $formattedMessages = $reversedMessages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_type' => class_basename($msg->sender_type),
                'receiver_id' => $msg->receiver_id,
                'receiver_type' => class_basename($msg->receiver_type),
                'message' => $msg->message,
                'type' => $msg->type,
                'attachment' => $msg->attachment ? url('storage/' . $msg->attachment) : null,
                'read_at' => $msg->read_at,
                'received_at' => $msg->received_at,
                'created_at' => $msg->created_at,
                'updated_at' => $msg->updated_at,
                'sender' => $msg->sender ? [
                    'id' => $msg->sender->id,
                    'name' => $msg->sender->name ?? $msg->sender->full_name ?? $msg->sender->institute_name ?? 'Unknown',
                    'logo' => $msg->sender->logo ?? $msg->sender->profile_image ?? null,
                    'type' => class_basename($msg->sender_type)
                ] : null,
                'receiver' => $msg->receiver ? [
                    'id' => $msg->receiver->id,
                    'name' => $msg->receiver->name ?? $msg->receiver->full_name ?? $msg->receiver->institute_name ?? 'Unknown',
                    'logo' => $msg->receiver->logo ?? $msg->receiver->profile_image ?? null,
                    'type' => class_basename($msg->receiver_type)
                ] : null
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
                'data' => $formattedMessages->toArray()
            ]
        ]);
    }

    /**
     * Clear / Delete an entire conversation between current user and another user
     */
    public function clearConversation(Request $request)
    {
        $user = auth('institute')->user() ?? auth('sanctum')->user() ?? $request->user();
        $userClass = get_class($user);

        $request->validate([
            'user_id' => 'required|integer',
            'user_type' => 'required|string'
        ]);

        $otherId = $request->input('user_id');
        $otherType = $request->input('user_type');
        $otherClass = $otherType == 'Staff' ? \App\Models\Staff::class :
            ($otherType == 'Student' ? \App\Models\Student::class :
                ($otherType == 'Institute' ? \App\Models\Institute::class : \App\Models\StudentParent::class));

        // 1. Where I am the sender, mark deleted_by_sender = true
        ChatMessage::where('sender_id', $user->id)
            ->where('sender_type', $userClass)
            ->where('receiver_id', $otherId)
            ->where('receiver_type', $otherClass)
            ->update(['deleted_by_sender' => true]);

        // 2. Where I am the receiver, mark deleted_by_receiver = true
        ChatMessage::where('sender_id', $otherId)
            ->where('sender_type', $otherClass)
            ->where('receiver_id', $user->id)
            ->where('receiver_type', $userClass)
            ->update(['deleted_by_receiver' => true]);

        // 3. Clean up permanent deletion if both users have deleted the conversation
        ChatMessage::where('deleted_by_sender', true)
            ->where('deleted_by_receiver', true)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Conversation deleted successfully.'
        ]);
    }

    /**
     * Mark a message as read and broadcast acknowledgment back to sender
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer|exists:chat_messages,id'
        ]);

        $message = ChatMessage::findOrFail($request->message_id);

        if (empty($message->read_at)) {
            $message->read_at = now();
            $message->save();

            // Broadcast back to the SENDER of the message that it has been read/received
            broadcast(new \App\Events\MessageRead($message))->toOthers();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Message marked as read.',
            'data' => [
                'id' => $message->id,
                'read_at' => $message->read_at,
            ]
        ]);
    }

    /**
     * Mark a message as received/delivered (device background receipt)
     */
    public function markAsReceived(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer|exists:chat_messages,id'
        ]);

        $message = ChatMessage::findOrFail($request->message_id);

        if (empty($message->received_at)) {
            $message->received_at = now();
            $message->save();

            // Broadcast back to the SENDER of the message that it has been delivered/received
            broadcast(new \App\Events\MessageReceived($message))->toOthers();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Message marked as received.',
            'data' => [
                'id' => $message->id,
                'received_at' => $message->received_at,
            ]
        ]);
    }

    /**
     * Fetch chat contact list dynamically based on authenticated user type
     */
    public function contacts(Request $request)
    {
        $user = $request->user();

        if ($user instanceof \App\Models\Institute) {
            $students = $user->students()->select('id', 'name', 'profile_image')->get()->map(function ($s) {
                $s->type = 'Student';
                $s->profile_image = $s->profile_image ? url('storage/' . $s->profile_image) : null;
                return $s;
            });

            return response()->json($students);
        }

        if ($user instanceof \App\Models\Student || $user instanceof \App\Models\StudentParent) {
            $instituteId = $user->institute_id ?? null;
            if (!$instituteId && $user instanceof \App\Models\StudentParent) {
                $firstChild = $user->students()->first();
                $instituteId = $firstChild ? $firstChild->institute_id : null;
            }

            if (!$instituteId) {
                return response()->json([]);
            }

            $institute = \App\Models\Institute::where('id', $instituteId)
                ->select('id', 'institute_name as name', 'logo as profile_image')
                ->get()->map(function ($i) {
                    $i->type = 'Institute';
                    $i->profile_image = $i->profile_image ? url('storage/' . $i->profile_image) : null;
                    return $i;
                });

            $staff = \App\Models\Staff::where('institute_id', $instituteId)->select('id', 'full_name as name', 'profile_image')->get()->map(function ($s) {
                $s->type = 'Staff';
                $s->profile_image = $s->profile_image ? url('storage/' . $s->profile_image) : null;
                return $s;
            });

            return response()->json($institute->concat($staff));
        }

        if ($user instanceof \App\Models\Staff) {
            $instituteId = $user->institute_id;

            $institute = \App\Models\Institute::where('id', $instituteId)
                ->select('id', 'institute_name as name', 'logo as profile_image')
                ->get()->map(function ($i) {
                    $i->type = 'Institute';
                    $i->profile_image = $i->profile_image ? url('storage/' . $i->profile_image) : null;
                    return $i;
                });

            $students = \App\Models\Student::where('institute_id', $instituteId)->select('id', 'name', 'profile_image')->get()->map(function ($s) {
                $s->type = 'Student';
                $s->profile_image = $s->profile_image ? url('storage/' . $s->profile_image) : null;
                return $s;
            });

            $parents = \App\Models\StudentParent::whereHas('students', function ($q) use ($instituteId) {
                $q->where('institute_id', $instituteId);
            })->select('id', 'father_name as name', 'profile_image')->get()->map(function ($p) {
                $p->type = 'StudentParent';
                $p->profile_image = $p->profile_image ? url('storage/' . $p->profile_image) : null;
                return $p;
            });

            $otherStaff = \App\Models\Staff::where('institute_id', $instituteId)->where('id', '!=', $user->id)->select('id', 'full_name as name', 'profile_image')->get()->map(function ($s) {
                $s->type = 'Staff';
                $s->profile_image = $s->profile_image ? url('storage/' . $s->profile_image) : null;
                return $s;
            });

            return response()->json($institute->concat($students)->concat($parents)->concat($otherStaff));
        }

        return response()->json([]);
    }
}
