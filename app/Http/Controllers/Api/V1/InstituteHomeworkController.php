<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Homework;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstituteHomeworkController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = $request->user()
            ->homeworks()
            ->whereDate('due_date', '>=', \Carbon\Carbon::yesterday()->toDateString());

        if ($request->has('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $homeworks = $query->select('id', 'batch_id', 'title', 'description', 'due_date', 'attachment', 'created_at')
            ->with([
                'batch' => function($q) {
                    $q->select('id', 'name')->withCount('students');
                }
            ])
            ->withCount('submissions')
            ->orderByDesc('created_at')
            ->paginate(12);

        // Transform list items minimally
        $homeworks->each(function($homework) {
            if ($homework->attachment) {
                $homework->attachment = asset('storage/' . $homework->attachment);
            }

            // Status flags for CLOSED / ACTIVE badge
            $dueDate         = \Carbon\Carbon::parse($homework->due_date)->endOfDay();
            $homework->is_closed = \Carbon\Carbon::now()->isAfter($dueDate);
            $homework->days_left = max(0, (int) \Carbon\Carbon::today()->diffInDays($dueDate, false));
            $homework->status    = $homework->is_closed ? 'closed' : 'active';
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'current_page' => $homeworks->currentPage(),
                'data'         => $homeworks->items(),
                'from'         => $homeworks->firstItem(),
                'last_page'    => $homeworks->lastPage(),
                'per_page'     => $homeworks->perPage(),
                'to'           => $homeworks->lastItem(),
                'total'        => $homeworks->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'batch_id'    => 'required|integer|exists:batches,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'due_date'    => 'required|date|after_or_equal:today',
            'attachment'  => 'nullable|file|max:10240',
        ]);

        $institute = $request->user();
        $batch = Batch::where('id', $request->batch_id)
            ->where('institute_id', $institute->id)
            ->first();

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found for this institute.',
            ], 404);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('homework_attachments', $filename, 'public');
        }

        $homework = Homework::create([
            'batch_id' => $batch->id,
            'institute_id' => $institute->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'attachment' => $attachmentPath,
        ]);

        // ── Send Push Notification for New Homework ──
        $this->notifyBatchStudents($homework, $batch);
        // ──────────────────────────────────────────────

        return response()->json([
            'status' => 'success',
            'message' => 'Homework created successfully.',
            'data' => $homework,
        ], 201);
    }

    /**
     * Send FCM push notification to all students in the batch (and their parents).
     */
    private function notifyBatchStudents(Homework $homework, Batch $batch): void
    {
        try {
            $fcm = app(\App\Services\FCMService::class);
            $batch->load('students.parent');

            $notifTitle = "New Homework: {$batch->name} 📝";
            $notifBody  = $homework->description ?? $homework->title;

            $notifData = [
                'type' => 'homework',
                'homework_id' => (string) $homework->id,
                'batch_id' => (string) $batch->id,
            ];

            // Check if homework has an attachment (image or document/PDF)
            $homeworkImageUrl = null;
            if (!empty($homework->attachment)) {
                $homeworkImageUrl = $homework->attachment; // Accessor gives the full URL
            }

            foreach ($batch->students as $student) {
                // Notify Student
                \App\Models\Notification::create([
                    'user_type'    => 'student',
                    'user_id'      => $student->id,
                    'title'        => $notifTitle,
                    'message'      => $notifBody,
                    'image'        => $homeworkImageUrl,
                    'type'         => 'homework',
                    'reference_id' => $homework->id,
                    'is_read'      => false,
                ]);
                
                if (!empty($student->fcm_token)) {
                    $fcm->send($student->fcm_token, $notifTitle, $notifBody, $notifData);
                }

                // Notify Parent
                if ($student->parent) {
                    \App\Models\Notification::create([
                        'user_type'    => 'parent',
                        'user_id'      => $student->parent->id,
                        'title'        => "New Homework: {$student->name} 📝",
                        'message'      => "{$student->name} has new homework: {$homework->title}",
                        'image'        => $homeworkImageUrl,
                        'type'         => 'homework',
                        'reference_id' => $homework->id,
                        'is_read'      => false,
                    ]);
                    
                    if (!empty($student->parent->fcm_token)) {
                        $fcm->send($student->parent->fcm_token, "New Homework: {$student->name} 📝", "{$student->name} has new homework: {$homework->title}", $notifData);
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Homework FCM notification failed: ' . $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = Homework::select('id', 'batch_id', 'institute_id', 'title', 'description', 'due_date', 'attachment', 'created_at')
            ->with([
                'batch' => function ($q) {
                    $q->select('id', 'name', 'subject');
                    $q->with([
                        'students' => function ($sq) {
                            $sq->select('id', 'name', 'profile_image', 'batch_id');
                        }
                    ]);
                }
            ])
            ->with([
                'submissions' => function ($q) {
                    $q->select('id', 'homework_id', 'student_id', 'status', 'score');
                }
            ])
            ->where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        // Transform to include pending students
        $submissions = $homework->submissions->keyBy('student_id');
        $allSubmissions = [];

        if ($homework->batch && $homework->batch->students) {
            foreach ($homework->batch->students as $student) {
                if ($submissions->has($student->id)) {
                    $submission = $submissions->get($student->id);
                    $submission->student = [
                        'id' => $student->id,
                        'name' => $student->name,
                        'profile_image_url' => $student->profile_image_url
                    ];
                    $allSubmissions[] = $submission;
                } else {
                    $allSubmissions[] = [
                        'id' => null,
                        'homework_id' => $homework->id,
                        'student_id' => $student->id,
                        'score' => 0,
                        'status' => 'pending',
                        'student' => [
                            'id' => $student->id,
                            'name' => $student->name,
                            'profile_image_url' => $student->profile_image_url
                        ]
                    ];
                }
            }
        }

        $homework->setRelation('submissions', collect($allSubmissions));

        // Convert attachment to full URL if exists
        if ($homework->attachment) {
            $homework->attachment = asset('storage/' . $homework->attachment);
        }

        // Status flags for CLOSED / ACTIVE badge
        $dueDate              = \Carbon\Carbon::parse($homework->due_date)->endOfDay();
        $homework->is_closed  = \Carbon\Carbon::now()->isAfter($dueDate);
        $homework->days_left  = max(0, (int) \Carbon\Carbon::today()->diffInDays($dueDate, false));
        $homework->status     = $homework->is_closed ? 'closed' : 'active';

        return response()->json([
            'status' => 'success',
            'data'   => $homework,
        ]);
    }

    public function updateScore(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'scores' => 'required|array',
            'scores.*.student_id' => 'required|integer|exists:students,id',
            'scores.*.score' => 'required|numeric|min:0',
        ]);

        $homework = Homework::where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        $savedSubmissions = [];
        foreach ($request->scores as $scoreData) {
            $submission = \App\Models\HomeworkSubmission::updateOrCreate(
                [
                    'homework_id' => $homework->id,
                    'student_id' => $scoreData['student_id'],
                ],
                [
                    'score' => $scoreData['score'],
                    'status' => 'submitted',
                ]
            );
            $savedSubmissions[] = $submission;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Scores updated successfully.',
            'data' => $savedSubmissions,
        ]);
    }

    public function updateGrades(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = Homework::where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        // Block grade updates for closed homework
        if ($homework->is_closed) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Grades cannot be updated after homework is closed.'
            ], 422);
        }

        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|integer|exists:students,id',
            'grades.*.score' => 'nullable|numeric',
            'grades.*.status' => 'required|string',
        ]);

        $studentIds = collect($request->grades)->pluck('student_id')->toArray();
        $students = \App\Models\Student::whereIn('id', $studentIds)->with('parent')->get()->keyBy('id');
        $fcm = app(\App\Services\FCMService::class);

        foreach ($request->grades as $gradeData) {
            // Standardize status to Title Case for consistency
            $status = ucfirst(strtolower($gradeData['status']));
            
            // If a score is provided, status is Reviewed
            if ($gradeData['score'] !== null && $gradeData['score'] !== '') {
                $status = 'Reviewed';
            }

            // Validate standardized status
            if (!in_array($status, ['Pending', 'Missing', 'Late', 'Submitted', 'Reviewed'])) {
                continue;
            }

            $homework->submissions()->updateOrCreate(
                ['student_id' => $gradeData['student_id']],
                [
                    'score' => $gradeData['score'],
                    'status' => $status,
                ]
            );

            $student = $students[$gradeData['student_id']] ?? null;
            if (!$student) {
                continue;
            }

            // Only notify if status is 'Submitted' or 'Reviewed' (meaning graded/published successfully)
            if ($status === 'Submitted' || $status === 'Reviewed') {
                $scoreText = $gradeData['score'] !== null ? "Score: {$gradeData['score']}" : "Graded successfully";
                $notifTitle = "Homework Graded! 🌟";
                $notifBody = "Your homework \"{$homework->title}\" has been graded. {$scoreText}!";
                $notifData = [
                    'type' => 'homework_graded',
                    'homework_id' => (string) $homework->id,
                    'batch_id' => (string) $homework->batch_id,
                ];

                // Student DB Notification
                \App\Models\Notification::create([
                    'user_type' => 'student',
                    'user_id' => $student->id,
                    'title' => $notifTitle,
                    'message' => $notifBody,
                    'type' => 'homework_graded',
                    'reference_id' => $homework->id,
                    'is_read' => false,
                ]);

                // Student FCM push
                if (!empty($student->fcm_token)) {
                    $fcm->send($student->fcm_token, $notifTitle, $notifBody, $notifData);
                }

                // Parent Notification
                if ($student->parent) {
                    $parentTitle = "Homework Graded: {$student->name}";
                    $parentBody = "{$student->name}'s homework \"{$homework->title}\" has been graded. {$scoreText}!";

                    \App\Models\Notification::create([
                        'user_type' => 'parent',
                        'user_id' => $student->parent->id,
                        'title' => $parentTitle,
                        'message' => $parentBody,
                        'type' => 'homework_graded',
                        'reference_id' => $homework->id,
                        'is_read' => false,
                    ]);

                    if (!empty($student->parent->fcm_token)) {
                        $fcm->send($student->parent->fcm_token, $parentTitle, $parentBody, $notifData);
                    }
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Grades updated successfully',
        ]);
    }

    public function sendReminder(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = Homework::where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->with(['batch.students.parent'])
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        $dueDate   = \Carbon\Carbon::parse($homework->due_date)->endOfDay();
        $isClosed  = \Carbon\Carbon::now()->isAfter($dueDate);
        $daysLeft  = max(0, (int) \Carbon\Carbon::today()->diffInDays($dueDate, false));

        if ($isClosed) {
            return response()->json(['status' => 'error', 'message' => 'Cannot send reminder for closed homework.'], 422);
        }

        if ($daysLeft > 3) {
            return response()->json(['status' => 'error', 'message' => 'Reminders can only be sent when 3 or fewer days are left.'], 422);
        }

        // Fetch already submitted student IDs to skip
        $submittedStudentIds = $homework->submissions()
            ->whereIn('status', ['Submitted', 'Late'])
            ->pluck('student_id')
            ->toArray();

        $students = $homework->batch->students ?? collect();
        $remindedCount = 0;
        $fcm = app(\App\Services\FCMService::class);

        $notifTitle = "Homework Pending! 📝";
        $notifBody  = "Reminder: \"{$homework->title}\" is still pending. Please submit it soon! (If already submitted, please ignore.)";
        $notifData  = [
            'type'        => 'homework_reminder',
            'homework_id' => (string) $homework->id,
            'batch_id'    => (string) $homework->batch_id,
        ];

        foreach ($students as $student) {
            if (in_array($student->id, $submittedStudentIds)) {
                continue;
            }

            \App\Models\Notification::create([
                'user_type'    => 'student',
                'user_id'      => $student->id,
                'title'        => $notifTitle,
                'message'      => $notifBody,
                'type'         => 'homework_reminder',
                'reference_id' => $homework->id,
                'is_read'      => false,
            ]);

            if (!empty($student->fcm_token)) {
                $fcm->send($student->fcm_token, $notifTitle, $notifBody, $notifData);
            }

            if ($student->parent) {
                $parentTitle = "Homework Reminder: {$student->name}";
                $parentBody  = "{$student->name}'s homework \"{$homework->title}\" is still pending. Please make sure they submit it. (If already submitted, please ignore.)";

                \App\Models\Notification::create([
                    'user_type'    => 'parent',
                    'user_id'      => $student->parent->id,
                    'title'        => $parentTitle,
                    'message'      => $parentBody,
                    'type'         => 'homework_reminder',
                    'reference_id' => $homework->id,
                    'is_read'      => false,
                ]);

                if (!empty($student->parent->fcm_token)) {
                    $fcm->send($student->parent->fcm_token, $parentTitle, $parentBody, $notifData);
                }
            }

            $remindedCount++;
        }

        return response()->json([
            'status' => 'success',
            'message' => "Reminders sent successfully to {$remindedCount} student(s)!",
        ]);
    }
}
