<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommunityMessage;
use App\Models\Institute;
use App\Models\StudentParent;

class CommunityController extends Controller
{
    /**
     * Get the community list for the user based on their city
     */
    public function list(Request $request)
    {
        $user = $request->user();
        
        if (empty($user->city)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please update your profile with your city to join a community.',
                'data' => []
            ], 400);
        }

        $city = trim($user->city);
        
        // Count institutes in city
        $instituteCount = Institute::where('city', $city)->count();
        // Count parents in city
        $parentCount = StudentParent::where('city', $city)->count();

        // Get latest message in this city
        $latestMessage = CommunityMessage::with('sender:id,name')
            ->where('city_name', $city)
            ->latest()
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                [
                    'city' => $city,
                    'total_members' => $instituteCount + $parentCount,
                    'institutes_count' => $instituteCount,
                    'parents_count' => $parentCount,
                    'latest_message' => $latestMessage ? $latestMessage->message : null,
                    'latest_message_sender' => $latestMessage && $latestMessage->sender ? $latestMessage->sender->name : null,
                    'latest_message_time' => $latestMessage ? $latestMessage->created_at : null
                ]
            ]
        ]);
    }

    /**
     * Get members of the user's community
     */
    public function members(Request $request)
    {
        $user = $request->user();
        
        if (empty($user->city)) {
            return response()->json([
                'status' => 'error',
                'message' => 'City not set in profile.'
            ], 400);
        }

        $city = trim($user->city);

        $institutes = Institute::where('city', $city)->select('id', 'name', 'institute_name')->get()->map(function($item) {
            $item->type = 'Institute';
            return $item;
        });

        $parents = StudentParent::where('city', $city)->select('id', 'name')->get()->map(function($item) {
            $item->type = 'StudentParent';
            return $item;
        });

        // Combine both
        $members = $institutes->concat($parents);

        return response()->json([
            'status' => 'success',
            'data' => $members
        ]);
    }

    /**
     * Get community messages
     */
    public function messages(Request $request)
    {
        $user = $request->user();
        
        if (empty($user->city)) {
            return response()->json([
                'status' => 'error',
                'message' => 'City not set in profile.'
            ], 400);
        }

        $city = trim($user->city);

        $messages = CommunityMessage::with('sender')
            ->where('city_name', $city)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ]);
    }

    /**
     * Send a community message
     */
    public function send(Request $request)
    {
        $user = $request->user();
        
        if (empty($user->city)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot send messages without joining a city community. Please update your profile.'
            ], 400);
        }

        $request->validate([
            'message' => 'required|string',
            'type' => 'required|in:text,image',
        ]);

        $city = trim($user->city);

        $message = CommunityMessage::create([
            'city_name' => $city,
            'sender_id' => $user->id,
            'sender_type' => get_class($user),
            'message' => $request->message,
            'type' => $request->type
        ]);

        $message->load('sender');

        return response()->json([
            'status' => 'success',
            'message' => 'Message broadcasted successfully to ' . $city . ' community.',
            'data' => $message
        ], 201);
    }
}
