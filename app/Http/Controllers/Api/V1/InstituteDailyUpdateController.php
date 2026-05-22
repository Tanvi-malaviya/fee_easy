<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\DailyUpdate;
use App\Models\Institute;
use Illuminate\Http\Request;
use App\Enums\UpdateCategory;
use App\Enums\UpdateRecipient;
use App\Enums\UpdateTargetType;
use Illuminate\Validation\Rules\Enum;

class InstituteDailyUpdateController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $updates = $request->user()
            ->dailyUpdates()
            ->with(['batch.students'])
            ->latest()
            ->get();

        $updates->each->makeHidden(['standard', 'student_id', 'student']);

        return response()->json([
            'status' => 'success',
            'data' => $updates,
        ]);
    }

    public function store(Request $request)
    {
        // Fix for trailing spaces/tabs in Postman keys
        $cleanData = [];
        foreach ($request->all() as $key => $value) {
            $cleanData[trim($key)] = $value;
        }
        $request->merge($cleanData);

        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        if ($request->recipient === 'parents' && !$request->has('target_type')) {
            $request->merge(['target_type' => 'all']);
        }


        $request->validate([
            'category' => ['required', new Enum(UpdateCategory::class)],
            'recipient' => ['required', new Enum(UpdateRecipient::class)],
            'target_type' => ['required', new Enum(UpdateTargetType::class)],
            'batch_id' => 'required_if:target_type,batch|nullable|exists:batches,id',
            'student_id' => 'nullable|exists:students,id',
            'standard' => 'required_if:target_type,standard|nullable|string',
            'topic' => 'nullable|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
        ]);

        $attachmentPath = null;
        $file = $request->file('attachment');
        
        // Robust check for any uploaded file if 'attachment' key is missing
        if (!$file && count($request->allFiles()) > 0) {
            $file = array_values($request->allFiles())[0];
        }

        if ($file) {
            $path = $file->store('updates', 'public');
            $attachmentPath = asset('storage/' . $path);
        }

        $update = DailyUpdate::create([
            'institute_id' => $request->user()->id,
            'recipient' => $request->recipient,
            'batch_id' => $request->target_type === UpdateTargetType::BATCH->value ? $request->batch_id : null,
            'target_type' => $request->target_type,
            'standard' => $request->target_type === UpdateTargetType::STANDARD->value ? $request->standard : null,
            'student_id' => $request->student_id,
            'category' => $request->category,
            'topic' => $request->topic,
            'description' => $request->description,
            'attachment' => $attachmentPath,
            'date' => now()->toDateString(),
        ]);

        // Send notifications based on Recipient (Students/Parents/Both) and Target Audience
        $studentsQuery = \App\Models\Student::where('institute_id', $request->user()->id);

        if ($request->target_type === UpdateTargetType::BATCH->value) {
            $studentsQuery->where('batch_id', $request->batch_id);
        } elseif ($request->target_type === UpdateTargetType::STANDARD->value) {
            $studentsQuery->where('standard', $request->standard);
        } elseif ($request->student_id) {
            $studentsQuery->where('id', $request->student_id);
        }

        $students = $studentsQuery->with('parent')->get();
        $fcm = app(\App\Services\FCMService::class);

        $categoryLabel = ucfirst($request->category);
        $topicLabel = $request->topic ?: 'Announcement';
        $notifTitle = "New {$categoryLabel} Update: {$topicLabel} 📢";
        $notifBody = \Illuminate\Support\Str::limit($request->description, 150);
        $notifData = [
            'type' => 'daily_update',
            'update_id' => (string) $update->id,
            'category' => $request->category,
        ];

        $notifyStudents = in_array($request->recipient, ['students', 'both']);
        $notifyParents = in_array($request->recipient, ['parents', 'both']);

        foreach ($students as $student) {
            // Notify Student
            if ($notifyStudents) {
                \App\Models\Notification::create([
                    'user_type' => 'student',
                    'user_id' => $student->id,
                    'title' => $notifTitle,
                    'message' => $notifBody,
                    'type' => 'daily_update',
                    'reference_id' => $update->id,
                    'is_read' => false,
                ]);

                if (!empty($student->fcm_token)) {
                    $fcm->send($student->fcm_token, $notifTitle, $notifBody, $notifData);
                }
            }

            // Notify Parent
            if ($notifyParents && $student->parent) {
                \App\Models\Notification::create([
                    'user_type' => 'parent',
                    'user_id' => $student->parent->id,
                    'title' => $notifTitle,
                    'message' => $notifBody,
                    'type' => 'daily_update',
                    'reference_id' => $update->id,
                    'is_read' => false,
                ]);

                if (!empty($student->parent->fcm_token)) {
                    $fcm->send($student->parent->fcm_token, $notifTitle, $notifBody, $notifData);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Update published successfully.',
            'data' => $update->load(['batch.students'])->makeHidden(['standard', 'student_id', 'student']),
        ], 201);
    }
}
