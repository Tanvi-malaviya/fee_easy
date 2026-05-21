<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentNotificationController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $notifications = Notification::where('user_type', 'student')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $notifications,
        ]);
    }

    /**
     * GET /api/v1/student/notifications/{id}/attachment/download
     * Forces download of the image attached to a notification.
     */
    public function downloadAttachment(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $notification = Notification::where('user_type', 'student')
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$notification) {
            return response()->json(['status' => 'error', 'message' => 'Notification not found'], 404);
        }

        if (empty($notification->image)) {
            return response()->json(['status' => 'error', 'message' => 'No attachment on this notification'], 404);
        }

        // Extract relative storage path from full URL
        // e.g. "http://host/storage/homework_attachments/file.jpg" → "homework_attachments/file.jpg"
        $imageUrl    = $notification->image;
        $storageBase = url('storage') . '/';

        if (str_starts_with($imageUrl, $storageBase)) {
            $relativePath = substr($imageUrl, strlen($storageBase));
        } else {
            $relativePath = ltrim(parse_url($imageUrl, PHP_URL_PATH), '/storage/');
        }

        $relativePath = urldecode($relativePath);

        if (!Storage::disk('public')->exists($relativePath)) {
            return response()->json(['status' => 'error', 'message' => 'Attachment file not found'], 404);
        }

        return Storage::disk('public')->download($relativePath, basename($relativePath));
    }

    /**
     * POST /api/v1/student/notifications/{id}/read
     * Marks a single notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $notification = Notification::where('user_type', 'student')
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$notification) {
            return response()->json(['status' => 'error', 'message' => 'Notification not found'], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['status' => 'success', 'message' => 'Marked as read']);
    }

    /**
     * POST /api/v1/student/notifications/mark-all-read
     */
    public function markAllRead(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        Notification::where('user_type', 'student')
            ->where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success', 'message' => 'All notifications marked as read']);
    }
}
