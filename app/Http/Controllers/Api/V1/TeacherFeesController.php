<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Fee;
use App\Models\Staff;
use Illuminate\Http\Request;

class TeacherFeesController extends Controller
{
    protected function getTeacher(Request $request)
    {
        $user = $request->user();
        if ($user instanceof Staff) {
            return $user;
        }

        if (auth('teacher')->check()) {
            return auth('teacher')->user();
        }

        return null;
    }

    /**
     * Read-only fee visibility, gated per-batch by the institute's
     * teacher_can_view_fees toggle.
     */
    public function index(Request $request)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate(['batch_id' => 'required|exists:batches,id']);

        $batch = Batch::where('id', $request->batch_id)->where('staff_id', $teacher->id)->first();
        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found or not assigned to you.'], 404);
        }

        if (!$batch->teacher_can_view_fees) {
            return response()->json(['status' => 'error', 'message' => 'Fee visibility is not enabled for this batch.'], 403);
        }

        $studentIds = $batch->students()->pluck('id');
        $fees = Fee::whereIn('student_id', $studentIds)
            ->with('student:id,name,enrollment_id')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['status' => 'success', 'data' => $fees]);
    }
}
