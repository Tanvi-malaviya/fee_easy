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
            'transaction_id' => 'required|string|unique:subscription_renewals,transaction_id',
            'screenshot'     => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'message'        => 'nullable|string|max:500',
        ]);

        $institute = $request->user();

        // Prevent duplicate pending submissions
        $existing = \App\Models\SubscriptionRenewal::where('institute_id', $institute->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'You already have a pending renewal request under review.',
            ], 400);
        }

        $path = null;
        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('payment_proofs', 'public');
        }

        $renewal = \App\Models\SubscriptionRenewal::create([
            'institute_id' => $institute->id,
            'transaction_id' => $request->transaction_id,
            'screenshot' => $path,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your subscription renewal request has been submitted successfully. We will review and activate it shortly!',
            'data' => $renewal
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
        $plans = \App\Models\Plan::where('status', 1)->get()->map(function($plan) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'price' => $plan->price,
                'duration_days' => $plan->duration_days,
                'status' => $plan->status,
                'created_at' => $plan->created_at,
                'updated_at' => $plan->updated_at,
            ];
        });

        // 3. History
        $history = $institute->subscriptions()
            ->latest()
            ->take(10)
            ->get();
            
        // 4. Payment Settings for Offline Renewal
        $paymentSettings = [
            'bank_holder_name' => \App\Models\SystemSetting::get('bank_holder_name', 'Tuoora Education'),
            'bank_name'        => \App\Models\SystemSetting::get('bank_name', 'HDFC Bank'),
            'bank_account'     => \App\Models\SystemSetting::get('bank_account_number', '—'),
            'bank_ifsc'        => \App\Models\SystemSetting::get('bank_ifsc', '—'),
            'qr_path'          => \App\Models\SystemSetting::get('payment_qr_path', 'payment_qr_code.png'),
            'qr_url'           => url('images/' . \App\Models\SystemSetting::get('payment_qr_path', 'payment_qr_code.png')),
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'subscription' => $subscription ? [
                    'plan_name' => $subscription->plan_name,
                    'status' => $subscription->status,
                    'expires_at' => $subscription->end_date,
                    'students_enrolled' => $enrolledCount,
                ] : [
                    'plan_name' => 'No Active Plan',
                    'status' => 'Inactive',
                    'expires_at' => null,
                    'students_enrolled' => $enrolledCount,
                ],
                'plans' => $plans,
                'history' => $history,
                'payment_settings' => $paymentSettings
            ]
        ]);
    }
}
