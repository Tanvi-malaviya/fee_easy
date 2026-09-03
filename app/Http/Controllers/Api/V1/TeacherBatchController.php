<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Staff;
use Illuminate\Http\Request;

class TeacherBatchController extends Controller
{
    /**
     * Resolve the authenticated teacher (Sanctum token or web session).
     */
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
     * List batches assigned to the authenticated teacher.
     * A teacher never sees batches they were not assigned to — no create/delete access here.
     */
    public function index(Request $request)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batches = Batch::where('staff_id', $teacher->id)
            ->withCount('students')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $batches,
        ]);
    }

    public function show(Request $request, $id)
    {
        $teacher = $this->getTeacher($request);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batch = Batch::where('id', $id)
            ->where('staff_id', $teacher->id)
            ->withCount('students')
            ->with(['students:id,name,batch_id,profile_image,enrollment_id'])
            ->first();

        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found or not assigned to you.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $batch,
        ]);
    }
}
