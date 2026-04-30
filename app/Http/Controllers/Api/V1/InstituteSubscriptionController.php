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

    public function purchase(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $institute = $request->user();
        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        try {
            $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

            $orderData = [
                'receipt'         => 'rcpt_' . $institute->id . '_' . time(),
                'amount'          => $plan->price * 100, // in paise
                'currency'        => 'INR',
                'notes'           => [
                    'plan_id' => $plan->id,
                    'institute_id' => $institute->id
                ]
            ];

            $razorpayOrder = $api->order->create($orderData);

            return response()->json([
                'status' => 'success',
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => $plan->price,
                'plan_name' => $plan->name,
                'institute_name' => $institute->institute_name,
                'email' => $institute->email,
                'phone' => $institute->phone,
                'razorpay_key' => env('RAZORPAY_KEY_ID')
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Razorpay Order Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to initiate payment. Please try again.'
            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
            'plan_id' => 'required|exists:plans,id'
        ]);

        $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // Payment verified, now create/update subscription
            $institute = $request->user();
            $plan = \App\Models\Plan::findOrFail($request->plan_id);

            $activeSub = $institute->subscriptions()
                ->where('status', 'active')
                ->where('end_date', '>', Carbon::now())
                ->latest('end_date')
                ->first();

            $startDate = $activeSub ? $activeSub->end_date : Carbon::now();
            $endDate = $startDate->copy()->addDays($plan->duration_days);

            $subscription = Subscription::create([
                'institute_id' => $institute->id,
                'plan_name' => $plan->name,
                'amount' => $plan->price,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]);

            SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'payment_gateway' => 'razorpay',
                'payment_source' => 'web',
                'transaction_id' => $request->razorpay_payment_id,
                'paid_at' => Carbon::now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment verified and subscription activated successfully.',
                'data' => $subscription
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ], 400);
        }
    }

    public function history(Request $request)
    {
        $subscriptions = $request->user()->subscriptions()
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $subscriptions->items(),
            'meta' => [
                'current_page' => $subscriptions->currentPage(),
                'last_page' => $subscriptions->lastPage(),
                'total' => $subscriptions->total(),
            ]
        ]);
    }

    public function allData(Request $request)
    {
        $institute = $request->user();
        
        // 1. Current Subscription
        $subscription = $institute->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', Carbon::now())
            ->latest('end_date')
            ->first();
            
        $enrolledCount = \App\Models\Student::where('institute_id', $institute->id)->count();

        // 2. Plans
        $plans = \App\Models\Plan::where('status', 1)->get();

        // 3. History
        $history = $institute->subscriptions()
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'subscription' => $subscription ? [
                    'plan_name' => $subscription->plan_name,
                    'status' => $subscription->status,
                    'expires_at' => $subscription->end_date,
                    'students_enrolled' => $enrolledCount,
                    'student_limit' => 1000, // Fixed fallback for now
                ] : [
                    'plan_name' => 'No Active Plan',
                    'status' => 'Inactive',
                    'expires_at' => null,
                    'students_enrolled' => $enrolledCount,
                    'student_limit' => 0,
                ],
                'plans' => $plans,
                'history' => $history
            ]
        ]);
    }
}
