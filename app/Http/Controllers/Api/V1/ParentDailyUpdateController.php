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

        $parent = $request->user();
        $batchIds = $parent->students()->pluck('batch_id')->filter()->unique();
        $standards = $parent->students()->pluck('standard')->filter()->unique();
        $instituteIds = $parent->students()->pluck('institute_id')->unique();

        $dailyUpdates = DailyUpdate::whereIn('institute_id', $instituteIds)
            ->whereIn('recipient', ['parents', 'both'])
            ->where(function($q) use ($batchIds, $standards) {
                $q->where('target_type', 'all')
                  ->orWhereIn('batch_id', $batchIds)
                  ->orWhereIn('standard', $standards);
            })
            ->orderByDesc('date')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $dailyUpdates,
        ]);
    }
}
