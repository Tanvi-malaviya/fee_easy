<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Notification;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Staff;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    /**
     * Send a push notification to students, parents, staff, or specific users.
     *
     * Body params:
     *   title       (required)
     *   message     (required)
     *   target_type (required): all_students | all_parents | all_staff | specific_students | specific_parents | specific_staff
     *   target_ids  (optional array): user IDs when target_type is specific_*
     *   type        (optional): notification type label
     *   reference_id (optional)
     */
    public function sendPush(Request $request, FCMService $fcm)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'message'     => 'required|string',
            'target_type' => 'required|in:all_students,all_parents,all_staff,specific_students,specific_parents,specific_staff',
            'target_ids'  => 'nullable|array',
            'target_ids.*'=> 'integer',
            'type'        => 'nullable|string|max:100',
            'reference_id'=> 'nullable|integer',
        ]);

        $institute   = $request->user();
        $targetType  = $request->target_type;
        $targetIds   = $request->input('target_ids', []);
        $title       = $request->title;
        $body        = $request->message;
        $notifType   = $request->input('type', 'general');

        // ── Resolve recipients ──────────────────────────────────────────────
        $recipients = collect(); // each item: ['model' => ..., 'user_type' => '...']

        if ($targetType === 'all_students') {
            Student::where('institute_id', $institute->id)
                ->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                ->get()->each(fn($s) => $recipients->push(['model' => $s, 'user_type' => 'student']));

        } elseif ($targetType === 'all_parents') {
            // parents linked through students of this institute
            $parentIds = Student::where('institute_id', $institute->id)
                ->whereNotNull('parent_id')->pluck('parent_id')->unique();
            StudentParent::whereIn('id', $parentIds)
                ->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                ->get()->each(fn($p) => $recipients->push(['model' => $p, 'user_type' => 'parent']));

        } elseif ($targetType === 'all_staff') {
            Staff::where('institute_id', $institute->id)
                ->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                ->get()->each(fn($s) => $recipients->push(['model' => $s, 'user_type' => 'staff']));

        } elseif ($targetType === 'specific_students') {
            Student::where('institute_id', $institute->id)
                ->whereIn('id', $targetIds)
                ->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                ->get()->each(fn($s) => $recipients->push(['model' => $s, 'user_type' => 'student']));

        } elseif ($targetType === 'specific_parents') {
            $parentIds = Student::where('institute_id', $institute->id)
                ->whereIn('id', $targetIds)->whereNotNull('parent_id')->pluck('parent_id')->unique();
            StudentParent::whereIn('id', $parentIds)
                ->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                ->get()->each(fn($p) => $recipients->push(['model' => $p, 'user_type' => 'parent']));

        } elseif ($targetType === 'specific_staff') {
            Staff::where('institute_id', $institute->id)
                ->whereIn('id', $targetIds)
                ->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                ->get()->each(fn($s) => $recipients->push(['model' => $s, 'user_type' => 'staff']));
        }

        if ($recipients->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No recipients with registered devices found for the selected target.',
            ], 422);
        }

        // ── Send notifications ───────────────────────────────────────────────
        $sentCount   = 0;
        $failedCount = 0;

        foreach ($recipients as $recipient) {
            $user     = $recipient['model'];
            $userType = $recipient['user_type'];

            // 1. Save to DB
            Notification::create([
                'user_type'    => $userType,
                'user_id'      => $user->id,
                'title'        => $title,
                'message'      => $body,
                'type'         => $notifType,
                'target'       => $targetType,
                'reference_id' => $request->reference_id,
                'is_read'      => false,
            ]);

            // 2. Send Firebase push
            $pushed = $fcm->send($user->fcm_token, $title, $body, [
                'type'         => $notifType,
                'reference_id' => (string) ($request->reference_id ?? ''),
            ]);

            $pushed ? $sentCount++ : $failedCount++;
        }

        return response()->json([
            'status'  => 'success',
            'message' => "Push notification sent to {$sentCount} device(s)." . ($failedCount > 0 ? " {$failedCount} failed." : ''),
            'data'    => [
                'total'  => $recipients->count(),
                'sent'   => $sentCount,
                'failed' => $failedCount,
            ],
        ], 201);
    }

    /**
     * Return recipient counts for the compose UI (no auth token needed - uses web session).
     */
    public function recipientStats(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $institute = $request->user();

        $studentsTotal   = Student::where('institute_id', $institute->id)->count();
        $studentsWithFCM = Student::where('institute_id', $institute->id)
            ->whereNotNull('fcm_token')->where('fcm_token', '!=', '')->count();

        $parentIds = Student::where('institute_id', $institute->id)
            ->whereNotNull('parent_id')->pluck('parent_id')->unique();
        $parentsTotal   = StudentParent::whereIn('id', $parentIds)->count();
        $parentsWithFCM = StudentParent::whereIn('id', $parentIds)
            ->whereNotNull('fcm_token')->where('fcm_token', '!=', '')->count();

        $staffTotal   = Staff::where('institute_id', $institute->id)->count();
        $staffWithFCM = Staff::where('institute_id', $institute->id)
            ->whereNotNull('fcm_token')->where('fcm_token', '!=', '')->count();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'students' => ['total' => $studentsTotal, 'with_fcm' => $studentsWithFCM],
                'parents'  => ['total' => $parentsTotal,  'with_fcm' => $parentsWithFCM],
                'staff'    => ['total' => $staffTotal,     'with_fcm' => $staffWithFCM],
            ],
        ]);
    }

    /**
     * Legacy send (keeps old behaviour - stores notification for institute itself).
     */
    public function send(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'title'        => 'required|string|max:255',
            'message'      => 'required|string',
            'type'         => 'nullable|string|max:100',
            'target'       => 'nullable|string|max:255',
            'reference_id' => 'nullable|integer',
        ]);

        $notification = Notification::create([
            'user_type'    => 'institute',
            'user_id'      => $request->user()->id,
            'title'        => $request->title,
            'message'      => $request->message,
            'type'         => $request->type,
            'target'       => $request->target,
            'reference_id' => $request->reference_id,
            'is_read'      => false,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Notification queued successfully.',
            'data'    => $notification,
        ], 201);
    }
}

