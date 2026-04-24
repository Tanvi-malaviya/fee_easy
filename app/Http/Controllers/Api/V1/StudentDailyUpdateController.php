<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DailyUpdate;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentDailyUpdateController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();
        $instituteId = $student->institute_id;

        $dailyUpdates = DailyUpdate::where('institute_id', $instituteId)
            ->whereIn('recipient', ['students', 'both'])
            ->where(function($q) use ($student) {
                $q->where('target_type', 'all')
                  ->orWhere('batch_id', $student->batch_id)
                  ->orWhere('standard', $student->standard);
            })
            ->orderByDesc('date')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $dailyUpdates,
        ]);
    }
}
