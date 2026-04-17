<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Institute;
use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;

class InstituteReportController extends Controller
{
    public function dashboard(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $institute = $request->user();

        $totalRevenue = SubscriptionPayment::whereHas('subscription', function ($query) use ($institute) {
            $query->where('institute_id', $institute->id);
        })->sum('amount');

        $totalFees = Fee::where('institute_id', $institute->id)->sum('total_amount');
        $dueFees = Fee::where('institute_id', $institute->id)->sum('due_amount');

        return response()->json([
            'status' => 'success',
            'data' => [
                'students_count' => $institute->students()->count(),
                'batches_count' => $institute->batches()->count(),
                'active_subscriptions' => $institute->subscriptions()->where('status', 'active')->count(),
                'trial_subscriptions' => $institute->subscriptions()->where('status', 'trial')->count(),
                'total_revenue' => $totalRevenue,
                'total_fees' => $totalFees,
                'total_due_fees' => $dueFees,
            ],
        ]);
    }

    public function income(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $payments = SubscriptionPayment::whereHas('subscription', function ($query) use ($request) {
            $query->where('institute_id', $request->user()->id);
        })->orderByDesc('paid_at')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'count' => $payments->count(),
                    'total_amount' => $payments->sum('amount'),
                ],
                'payments' => $payments,
            ],
        ]);
    }

    public function fees(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $fees = Fee::where('institute_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'count' => $fees->count(),
                    'total_amount' => $fees->sum('total_amount'),
                    'paid_amount' => $fees->sum('paid_amount'),
                    'due_amount' => $fees->sum('due_amount'),
                ],
                'fees' => $fees,
            ],
        ]);
    }
}
