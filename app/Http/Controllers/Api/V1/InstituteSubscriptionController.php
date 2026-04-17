<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InstituteSubscriptionController extends Controller
{
    public function show(Request $request)
    {
        $subscription = $request->user()->subscriptions()
            ->with('payments')
            ->latest('end_date')
            ->first();

        if (! $subscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'No subscription found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $subscription,
        ]);
    }

    public function renew(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'days' => 'required|integer|min:1',
            'payment_gateway' => 'nullable|string|max:255',
            'payment_source' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        $institute = $request->user();
        $subscription = $institute->subscriptions()
            ->whereIn('status', ['active', 'trial'])
            ->latest('end_date')
            ->first();

        $now = Carbon::now();
        $startDate = $subscription && $subscription->end_date && $subscription->end_date->greaterThan($now)
            ? $subscription->end_date
            : $now;

        if (! $subscription) {
            $subscription = Subscription::create([
                'institute_id' => $institute->id,
                'plan_name' => 'Subscription Renewal',
                'amount' => $request->amount,
                'start_date' => $startDate,
                'end_date' => $startDate->copy()->addDays($request->days),
                'status' => 'active',
            ]);
        } else {
            $subscription->update([
                'amount' => $request->amount,
                'end_date' => $startDate->copy()->addDays($request->days),
                'status' => 'active',
            ]);
        }

        SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'amount' => $request->amount,
            'payment_gateway' => $request->payment_gateway,
            'payment_source' => $request->payment_source,
            'transaction_id' => $request->transaction_id,
            'paid_at' => $now,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Subscription renewed successfully.',
            'data' => $subscription,
        ]);
    }
}
