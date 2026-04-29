<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Notification;
use Illuminate\Http\Request;

class InstituteNotificationController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $notifications = Notification::where('user_type', 'institute')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $notifications,
        ]);
    }

    public function markAllRead(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        Notification::where('user_type', 'institute')
            ->where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'All notifications marked as read.',
        ]);
    }

    public function send(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string|max:100',
            'target' => 'nullable|string|max:255',
            'reference_id' => 'nullable|integer',
        ]);

        $notification = Notification::create([
            'user_type' => 'institute',
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'target' => $request->target,
            'reference_id' => $request->reference_id,
            'is_read' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification queued successfully.',
            'data' => $notification,
        ], 201);
    }
}
