<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class StudentHomeworkController extends Controller
{
    /**
     * GET /api/v1/student/homeworks
     *
     * Returns a full assignments screen response matching the mobile UI:
     *   - summary  : total, pending, completed counts + "marked complete by" teacher note
     *   - week_summary : "X/Y Still to do this week"
     *   - pending   : list of pending/overdue assignments
     *   - completed : list of submitted assignments
     *
     * Optional query params:
     *   ?status=pending|completed   – filter list
     *   ?subject=MATHEMATICS        – filter by subject
     */
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();

        if (!$student->batch_id) {
            return response()->json([
                'status' => 'success',
                'data'   => $this->emptyResponse(),
            ]);
        }

        // ── Fetch all homeworks for this student's batch ──
        $homeworks = Homework::where('batch_id', $student->batch_id)
            ->with('batch:id,name,subject')
            ->orderBy('due_date', 'asc')
            ->get();

        // ── Fetch all submissions for this student in one query ──
        $homeworkIds  = $homeworks->pluck('id');
        $submissions  = HomeworkSubmission::where('student_id', $student->id)
            ->whereIn('homework_id', $homeworkIds)
            ->get()
            ->keyBy('homework_id');

        // ── Week boundaries ──
        $weekStart = Carbon::now()->startOfWeek();   // Monday
        $weekEnd   = Carbon::now()->endOfWeek();     // Sunday

        $pending   = [];
        $completed = [];
        $weekTotal = 0;
        $weekDone  = 0;

        foreach ($homeworks as $homework) {
            $submission = $submissions->get($homework->id);
            $isSubmitted = $submission && in_array(strtolower($submission->status), ['submitted', 'late', 'missing']) === false
                ? false
                : ($submission && strtolower($submission->status) === 'submitted');

            // Simpler: any submission record = submitted
            $isSubmitted = (bool)$submission;

            $dueDate     = Carbon::parse($homework->due_date);
            $diffInDays  = Carbon::today()->diffInDays($dueDate, false);
            $isOverdue   = $diffInDays < 0;

            // Due label
            $dueLabel = match (true) {
                $diffInDays === 0  => 'Today',
                $diffInDays === 1  => 'Tomorrow',
                $diffInDays === -1 => 'Yesterday',
                $isOverdue         => 'Overdue · ' . $dueDate->format('j M'),
                default            => $dueDate->format('j M'),
            };

            $dueLabelShort = match (true) {
                $diffInDays === 0  => 'DUE TODAY',
                $diffInDays === 1  => 'DUE TOMORROW',
                $isOverdue         => 'OVERDUE',
                default            => 'DUE ' . strtoupper($dueDate->format('j M')),
            };

            // Week tracking
            $dueInThisWeek = $dueDate->between($weekStart, $weekEnd);
            if ($dueInThisWeek) {
                $weekTotal++;
                if ($isSubmitted) {
                    $weekDone++;
                }
            }

            $item = [
                'id'              => $homework->id,
                'title'           => $homework->title,
                'description'     => $homework->description,
                'subject'         => $homework->batch->subject ?? 'General',
                'batch_name'      => $homework->batch->name ?? null,
                'due_date'        => $homework->due_date,
                'due_label'       => $dueLabel,
                'due_label_badge' => $dueLabelShort,
                'is_overdue'      => $isOverdue,
                'attachment_url'  => $homework->attachment ?? null,
                'status'          => $isSubmitted ? 'completed' : ($isOverdue ? 'overdue' : 'pending'),
                'submission'      => $submission ? [
                    'id'           => $submission->id,
                    'status'       => $submission->status,
                    'score'        => $submission->score,
                    'submitted_at' => $submission->created_at?->toISOString(),
                ] : null,
            ];

            if ($isSubmitted) {
                $completed[] = $item;
            } else {
                $pending[] = $item;
            }
        }

        // ── Filter by status if requested ──
        $statusFilter  = $request->query('status');
        $subjectFilter = $request->query('subject');

        $applyFilter = function (array $list) use ($subjectFilter): array {
            if ($subjectFilter) {
                return array_values(array_filter($list, fn($i) => strcasecmp($i['subject'], $subjectFilter) === 0));
            }
            return $list;
        };

        $filteredPending   = $applyFilter($pending);
        $filteredCompleted = $applyFilter($completed);

        // ── Week summary text (matches "2/5 Still to do this week") ──
        $weekStillTodo = $weekTotal - $weekDone;
        $weekSummary = [
            'still_to_do'  => $weekStillTodo,
            'total'        => $weekTotal,
            'done'         => $weekDone,
            'label'        => "{$weekStillTodo}/{$weekTotal} Still to do this week",
            'sub_label'    => "{$weekDone} marked complete",
        ];

        // ── Overall counts ──
        $summary = [
            'total'     => count($homeworks),
            'pending'   => count($pending),
            'completed' => count($completed),
            'overdue'   => count(array_filter($pending, fn($i) => $i['is_overdue'])),
        ];

        // ── Build response based on status filter ──
        $responseData = [
            'summary'      => $summary,
            'week_summary' => $weekSummary,
        ];

        if (!$statusFilter || $statusFilter === 'pending') {
            $responseData['pending'] = array_values($filteredPending);
        }
        if (!$statusFilter || $statusFilter === 'completed') {
            $responseData['completed'] = array_values($filteredCompleted);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $responseData,
        ]);
    }

    /**
     * GET /api/v1/student/homeworks/{id}
     *
     * Returns the detail of a single homework assignment for this student.
     */
    public function show(Request $request, int $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();

        $homework = Homework::with('batch:id,name,subject')
            ->where('batch_id', $student->batch_id)
            ->find($id);

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Assignment not found'], 404);
        }

        $submission = HomeworkSubmission::where('homework_id', $homework->id)
            ->where('student_id', $student->id)
            ->first();

        $dueDate    = Carbon::parse($homework->due_date);
        $diffInDays = Carbon::today()->diffInDays($dueDate, false);
        $isOverdue  = $diffInDays < 0;
        $isSubmitted = (bool)$submission;

        $dueLabel = match (true) {
            $diffInDays === 0  => 'Due Today',
            $diffInDays === 1  => 'Due Tomorrow',
            $diffInDays === -1 => 'Due Yesterday',
            $isOverdue         => 'Overdue by ' . abs($diffInDays) . ' days',
            default            => 'Due ' . $dueDate->format('j M Y'),
        };

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'             => $homework->id,
                'title'          => $homework->title,
                'description'    => $homework->description,
                'subject'        => $homework->batch->subject ?? 'General',
                'batch_name'     => $homework->batch->name ?? null,
                'due_date'       => $homework->due_date,
                'due_date_label' => $dueLabel,
                'due_date_formatted' => $dueDate->format('d M Y'),
                'is_overdue'     => $isOverdue,
                'attachment_url' => $homework->attachment ?? null,
                'status'         => $isSubmitted ? 'completed' : ($isOverdue ? 'overdue' : 'pending'),
                'submission'     => $submission ? [
                    'id'           => $submission->id,
                    'status'       => $submission->status,
                    'score'        => $submission->score,
                    'submitted_at' => $submission->created_at?->toISOString(),
                    'submitted_at_label' => $submission->created_at?->format('d M Y, g:i A'),
                ] : null,
                'created_at' => $homework->created_at?->toISOString(),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/homeworks/{id}/submit
     *
     * Mark an assignment as submitted by the student.
     * Body: { "note": "optional note" }
     */
    public function submit(Request $request, int $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();

        $homework = Homework::where('batch_id', $student->batch_id)->find($id);

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Assignment not found'], 404);
        }

        $existing = HomeworkSubmission::where('homework_id', $homework->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You have already submitted this assignment.',
            ], 409);
        }

        $dueDate  = Carbon::parse($homework->due_date)->endOfDay();
        $isOverdue = Carbon::now()->isAfter($dueDate);

        if ($isOverdue) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Submission closed. The due date for this assignment has passed.',
            ], 403);
        }

        $submission = HomeworkSubmission::create([
            'homework_id'  => $homework->id,
            'student_id'   => $student->id,
            'status'       => 'submitted',
            'submitted_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Assignment submitted successfully.',
            'data'    => [
                'submission_id' => $submission->id,
                'status'        => $submission->status,
                'submitted_at'  => $submission->created_at->toISOString(),
            ],
        ], 201);
    }

    // ── Private Helpers ──────────────────────────────────────────────────────

    private function emptyResponse(): array
    {
        return [
            'summary'      => ['total' => 0, 'pending' => 0, 'completed' => 0, 'overdue' => 0],
            'week_summary' => ['still_to_do' => 0, 'total' => 0, 'done' => 0, 'label' => '0/0 Still to do this week', 'sub_label' => '0 marked complete'],
            'pending'      => [],
            'completed'    => [],
        ];
    }

    // ── Attachment Viewer ─────────────────────────────────────────────────────

    /**
     * GET /api/v1/student/homeworks/{id}/attachment
     *
     * Returns attachment metadata for the Attachment Viewer screen:
     *   filename, file_type, file_size, preview_url (direct storage URL)
     */
    public function attachmentInfo(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student  = $request->user();
        $homework = Homework::where('id', $id)
            ->where('batch_id', $student->batch_id)
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        // getRawOriginal bypasses the URL accessor so we can read the raw path
        $rawPath = $homework->getRawOriginal('attachment');

        if (!$rawPath) {
            return response()->json(['status' => 'error', 'message' => 'No attachment on this homework'], 404);
        }

        $fullPath   = storage_path('app/public/' . $rawPath);
        $filename   = basename($rawPath);
        $extension  = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $sizeBytes  = file_exists($fullPath) ? filesize($fullPath) : null;
        $sizeMB     = $sizeBytes ? round($sizeBytes / 1024 / 1024, 1) . ' MB' : null;

        $fileType = match (true) {
            in_array($extension, ['jpg','jpeg','png','gif','webp','svg'])     => 'image',
            in_array($extension, ['mp4','mov','avi','mkv','webm'])            => 'video',
            in_array($extension, ['pdf'])                                      => 'pdf',
            in_array($extension, ['doc','docx','xls','xlsx','ppt','pptx'])    => 'document',
            default                                                            => 'file',
        };

        return response()->json([
            'status' => 'success',
            'data'   => [
                'homework_id'  => $homework->id,
                'homework_title' => $homework->title,
                'filename'     => $filename,
                'extension'    => $extension,
                'file_type'    => $fileType,     // image | video | pdf | document | file
                'file_size'    => $sizeMB,        // "1.2 MB"
                'preview_url'  => $homework->attachment, // accessor → full storage URL
                'download_url' => url("/api/v1/student/homeworks/{$id}/attachment/download"),
            ],
        ]);
    }

    /**
     * GET /api/v1/student/homeworks/{id}/attachment/download
     *
     * Forces file download (used by "Download" button in Attachment Viewer).
     */
    public function attachmentDownload(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student  = $request->user();
        $homework = Homework::where('id', $id)
            ->where('batch_id', $student->batch_id)
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        $rawPath = $homework->getRawOriginal('attachment');

        if (!$rawPath || !Storage::disk('public')->exists($rawPath)) {
            return response()->json(['status' => 'error', 'message' => 'Attachment not found'], 404);
        }

        return Storage::disk('public')->download($rawPath, basename($rawPath));
    }
}

