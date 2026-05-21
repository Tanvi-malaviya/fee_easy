<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentResourceController extends Controller
{
    /**
     * GET /api/v1/student/resources
     *
     * Returns study materials for the student's batch.
     * Supports optional ?subject= filter (matches batch.subject).
     *
     * Response includes:
     *  - subjects  : list of distinct subjects for filter tabs (+ "All subjects")
     *  - resources : list of resource items with title, description, file count, teacher, date
     */
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        /** @var Student $student */
        $student = $request->user();
        $student->loadMissing('batch:id,name,subject,institute_id');

        $batch = $student->batch;

        if (!$batch) {
            return response()->json([
                'status' => 'success',
                'data'   => ['subjects' => [], 'resources' => []],
            ]);
        }

        // All resources for this student's institute, ordered newest first
        $query = Resource::where('institute_id', $batch->institute_id)
            ->where('batch_id', $batch->id)
            ->with('batch:id,name,subject')
            ->orderBy('created_at', 'desc');

        // Optional subject filter
        $subjectFilter = $request->query('subject');
        if ($subjectFilter && $subjectFilter !== 'all') {
            $query->whereHas('batch', fn($q) => $q->where('subject', $subjectFilter));
        }

        $resources = $query->get();

        // Build filter tabs: distinct subjects from this batch's institute
        $allSubjects = Resource::where('institute_id', $batch->institute_id)
            ->where('batch_id', $batch->id)
            ->with('batch:id,subject')
            ->get()
            ->pluck('batch.subject')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Format resource list
        $formatted = $resources->map(function ($r) {
            return [
                'id'          => $r->id,
                'title'       => $r->title,
                'description' => $r->description,
                'subject'     => $r->batch->subject ?? null,
                'batch_name'  => $r->batch->name ?? null,
                'file_type'   => $r->file_type,   // document | video | image
                'file_size'   => $r->file_size,
                'file_count'  => 1,                // one file per resource record
                'file_url'     => $r->file_url,
                'download_url' => url('/api/v1/student/resources/' . $r->id . '/download'),
                'date'         => $this->formatDate($r->created_at),
                'date_raw'    => $r->created_at->toDateString(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => [
                'subjects'  => $allSubjects,   // e.g. ["Mathematics", "Physics", "Chemistry"]
                'resources' => $formatted,
            ],
        ]);
    }

    /**
     * GET /api/v1/student/resources/{id}/download
     */
    public function download(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student  = $request->user();
        $student->loadMissing('batch:id,institute_id');

        $resource = Resource::where('id', $id)
            ->where('institute_id', $student->batch->institute_id ?? 0)
            ->first();

        if (!$resource || !$resource->file_path) {
            return response()->json(['status' => 'error', 'message' => 'Resource not found'], 404);
        }

        if (!Storage::disk('public')->exists($resource->file_path)) {
            return response()->json(['status' => 'error', 'message' => 'File not found on server'], 404);
        }

        // Use title + original file extension so OS recognizes the file type
        $extension     = pathinfo($resource->file_path, PATHINFO_EXTENSION);
        $downloadName  = $resource->title . ($extension ? '.' . $extension : '');

        return Storage::disk('public')->download($resource->file_path, $downloadName);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function formatDate(\Carbon\Carbon $date): string
    {
        $today    = now()->startOfDay();
        $diffDays = $today->diffInDays($date->startOfDay(), false);

        if ($diffDays === 0)  return 'Today';
        if ($diffDays === -1) return 'Yesterday';

        $dayOfWeek = $date->format('l'); // "Monday" etc.
        $daysAgo   = abs($diffDays);

        // Within this week → show day name (Mon, Tue, Wed...)
        if ($daysAgo <= 6) return $date->format('D'); // "Mon", "Wed"

        // Older → show "12 May"
        return $date->format('d M');
    }
}
