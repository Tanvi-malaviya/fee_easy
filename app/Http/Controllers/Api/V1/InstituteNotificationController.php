<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class InstituteNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_type', 'institute')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $notifications,
        ]);
    }

    public function send(Request $request)
    {
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
