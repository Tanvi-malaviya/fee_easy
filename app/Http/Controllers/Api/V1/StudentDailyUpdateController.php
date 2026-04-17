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

        $dailyUpdates = DailyUpdate::where('batch_id', $student->batch_id)
            ->orderByDesc('date')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $dailyUpdates,
        ]);
    }
}
