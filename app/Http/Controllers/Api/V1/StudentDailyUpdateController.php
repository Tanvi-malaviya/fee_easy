<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DailyUpdate;
use Illuminate\Http\Request;

class StudentDailyUpdateController extends Controller
{
    public function index(Request $request)
    {
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
