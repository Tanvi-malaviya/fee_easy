<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DailyUpdate;
use App\Models\StudentParent;
use Illuminate\Http\Request;

class ParentDailyUpdateController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof StudentParent)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batchIds = $request->user()->students()->pluck('batch_id')->filter()->unique();

        $dailyUpdates = DailyUpdate::whereIn('batch_id', $batchIds)
            ->orderByDesc('date')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $dailyUpdates,
        ]);
    }
}
