<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Institute;
use App\Models\Plan;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionRenewal;
use App\Models\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subscription::with('institute');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('institute', function ($q) use ($search) {
                $q->where('institute_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;
            if ($status === 'expired') {
                $query->where('end_date', '<', now());
            } else if ($status === 'active') {
                $query->where('status', 'active')->where('end_date', '>=', now());
            } else {
                $query->where('status', '=', $status);
            }
        }

        $subscriptions = $query->orderBy('end_date', 'asc')->paginate(10);

        $institutes = Institute::where('status', 'active')->get();
        $plans = Plan::where('status', true)->get();
        
        $paginatedItems = collect($subscriptions->items());
        
        $stats = [
            'active_count' => $paginatedItems->where('status', 'active')->count(),
            'expiring_count' => $paginatedItems->where('status', 'active')
                ->filter(function($sub) {
                    return \Carbon\Carbon::parse($sub->end_date)->lte(now()->addDays(7));
                })
                ->count(),
            'total_revenue' => $paginatedItems->where('status', 'active')->sum('amount'),
        ];
        
        $pendingRenewals = SubscriptionRenewal::with('institute')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $currency = \App\Models\SystemSetting::get('currency_symbol', '₹');
        
        return view('subscriptions.index', compact('subscriptions', 'institutes', 'plans', 'stats', 'pendingRenewals', 'currency'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'institute_id' => 'required|exists:institutes,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
        ]);

        $plan = Plan::find($request->plan_id);
        $startDate = Carbon::parse($request->start_date);
        
        // Duration Logic
        $endDate = $startDate->copy()->addDays($plan->duration_days);

        $subscription = Subscription::create([
            'institute_id' => $request->institute_id,
            'plan_name' => $plan->name,
            'amount' => $plan->price,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);

        // Record Payment (Only for paid plans)
        if ($plan->price > 0) {
            SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'payment_source' => 'admin',
                'paid_at' => now(),
            ]);
        }

        $institute = $subscription->institute;

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::to($institute->email)->send(new \App\Mail\SubscriptionStatusMail(
                $institute->institute_name,
                $plan->name,
                $endDate,
                $plan->price,
                'assigned'
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send subscription assigned email: " . $e->getMessage());
        }

        // Send DB Notification
        $notifTitle = "Subscription Assigned";
        $notifBody = "The {$plan->name} subscription plan has been assigned to your institute.";
        
        \App\Models\Notification::create([
            'user_type' => 'institute',
            'user_id' => $institute->id,
            'title' => $notifTitle,
            'message' => $notifBody,
            'type' => 'subscription_alert',
            'is_read' => false,
        ]);

        // Send FCM Notification
        if (!empty($institute->fcm_token)) {
            try {
                $fcmService = app(\App\Services\FCMService::class);
                $fcmService->send($institute->fcm_token, $notifTitle, $notifBody, [
                    'type' => 'subscription_alert',
                    'plan_name' => $plan->name,
                    'status' => 'assigned',
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send subscription assigned FCM: " . $e->getMessage());
            }
        }

        Activity::log("New subscription assigned: {$subscription->plan_name} to {$subscription->institute->institute_name}");

        return redirect()->route('subscriptions.index')->with('success', 'Plan assigned successfully.');
    }

    /**
     * Extend an existing subscription.
     */
    public function extend(Request $request, Subscription $subscription)
    {
        $request->validate([
            'days' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0|max:999999',
        ]);

        $currentEndDate = Carbon::parse($subscription->end_date);
        $newEndDate = $currentEndDate->isPast() ? now()->addDays($request->days) : $currentEndDate->addDays($request->days);

        $subscription->update([
            'end_date' => $newEndDate,
            'status' => 'active'
        ]);

        // Record Extension Payment
        if ($request->amount > 0) {
            SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'amount' => $request->amount,
                'payment_source' => 'admin',
                'paid_at' => now(),
            ]);
        }

        $institute = $subscription->institute;

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::to($institute->email)->send(new \App\Mail\SubscriptionStatusMail(
                $institute->institute_name,
                $subscription->plan_name,
                $newEndDate,
                $request->days,
                'extended'
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send subscription extended email: " . $e->getMessage());
        }

        // Send DB Notification
        $notifTitle = "Plan Extended";
        $notifBody = "Your {$subscription->plan_name} plan has been extended by {$request->days} days. New expiry: " . $newEndDate->format('d M, Y') . ".";
        
        \App\Models\Notification::create([
            'user_type' => 'institute',
            'user_id' => $institute->id,
            'title' => $notifTitle,
            'message' => $notifBody,
            'type' => 'subscription_alert',
            'is_read' => false,
        ]);

        // Send FCM Notification
        if (!empty($institute->fcm_token)) {
            try {
                $fcmService = app(\App\Services\FCMService::class);
                $fcmService->send($institute->fcm_token, $notifTitle, $notifBody, [
                    'type' => 'subscription_alert',
                    'plan_name' => $subscription->plan_name,
                    'status' => 'extended',
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send subscription extended FCM: " . $e->getMessage());
            }
        }

        Activity::log("Subscription extended for: {$subscription->institute->institute_name} (+{$request->days} days)");

        return redirect()->back()->with('success', 'Subscription extended successfully.');
    }

    /**
     * Cancel a subscription.
     */
    public function cancel(Subscription $subscription)
    {
        $subscription->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Activate a subscription.
     */
    public function activate(Subscription $subscription)
    {
        $subscription->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Subscription activated successfully.');
    }

    /**
     * Change the plan of an existing subscription.
     */
    public function changePlan(Subscription $subscription, Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::find($request->plan_id);
        $endDate = now()->addDays($plan->duration_days);

        $subscription->update([
            'plan_id'    => $plan->id,
            'plan_name'  => $plan->name,
            'amount'     => $plan->price,
            'start_date' => now(),
            'end_date'   => $endDate,
            'status'     => 'active',
        ]);

        // Record Payment for the new plan
        if ($plan->price > 0) {
            SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'payment_source' => 'admin',
                'paid_at' => now(),
            ]);
        }

        $institute = $subscription->institute;

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::to($institute->email)->send(new \App\Mail\SubscriptionStatusMail(
                $institute->institute_name,
                $plan->name,
                $endDate,
                $plan->price,
                'changed'
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send subscription changed email: " . $e->getMessage());
        }

        // Send DB Notification
        $notifTitle = "Plan Activated";
        $notifBody = "Your {$plan->name} plan is now active. New expiry: " . $endDate->format('d M, Y') . ".";
        
        \App\Models\Notification::create([
            'user_type' => 'institute',
            'user_id' => $institute->id,
            'title' => $notifTitle,
            'message' => $notifBody,
            'type' => 'subscription_alert',
            'is_read' => false,
        ]);

        // Send FCM Notification
        if (!empty($institute->fcm_token)) {
            try {
                $fcmService = app(\App\Services\FCMService::class);
                $fcmService->send($institute->fcm_token, $notifTitle, $notifBody, [
                    'type' => 'subscription_alert',
                    'plan_name' => $plan->name,
                    'status' => 'changed',
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send subscription changed FCM: " . $e->getMessage());
            }
        }

        Activity::log("Subscription plan changed to: {$plan->name} for {$subscription->institute->institute_name}");

        return redirect()->back()->with('success', 'Subscription plan changed successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('subscriptions.index')->with('success', 'Subscription record deleted.');
    }

    /**
     * Approve an offline subscription renewal request.
     */
    public function approveRenewal(Request $request, SubscriptionRenewal $renewal)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::find($request->plan_id);
        $institute = $renewal->institute;

        // 1. Update the renewal request status
        $renewal->update([
            'status' => 'approved',
        ]);

        // 2. Activate or create their subscription
        $subscription = Subscription::where('institute_id', $institute->id)->first();
        $endDate = now()->addDays($plan->duration_days);

        if ($subscription) {
            $subscription->update([
                'plan_name' => $plan->name,
                'amount' => $plan->price,
                'start_date' => now(),
                'end_date' => $endDate,
                'status' => 'active',
            ]);
        } else {
            $subscription = Subscription::create([
                'institute_id' => $institute->id,
                'plan_name' => $plan->name,
                'amount' => $plan->price,
                'start_date' => now(),
                'end_date' => $endDate,
                'status' => 'active',
            ]);
        }

        // 3. Record subscription payment
        SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'amount' => $plan->price,
            'payment_source' => 'offline_renewal',
            'paid_at' => now(),
        ]);

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::to($institute->email)->send(new \App\Mail\SubscriptionStatusMail(
                $institute->institute_name,
                $plan->name,
                $endDate,
                $plan->price,
                'approved'
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send subscription approved email: " . $e->getMessage());
        }

        // 4. Send notifications (DB + FCM Push)
        $notifTitle = "Renewal Approved";
        $notifBody = "Your {$plan->name} plan has been renewed and is now active. Thank you!";
        
        \App\Models\Notification::create([
            'user_type' => 'institute',
            'user_id' => $institute->id,
            'title' => $notifTitle,
            'message' => $notifBody,
            'type' => 'subscription_alert',
            'is_read' => false,
        ]);

        if (!empty($institute->fcm_token)) {
            try {
                $fcmService = app(\App\Services\FCMService::class);
                $fcmService->send($institute->fcm_token, $notifTitle, $notifBody, [
                    'type' => 'subscription_alert',
                    'plan_name' => $plan->name,
                    'status' => 'approved',
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send subscription approved FCM: " . $e->getMessage());
            }
        }

        Activity::log("Manual renewal approved for {$institute->institute_name} (Plan: {$plan->name})");

        return redirect()->back()->with('success', "Renewal approved successfully. Subscription activated for {$institute->institute_name}.");
    }

    /**
     * Reject an offline subscription renewal request.
     */
    public function rejectRenewal(Request $request, SubscriptionRenewal $renewal)
    {
        $renewal->update([
            'status' => 'rejected',
        ]);

        $institute = $renewal->institute;

        // Send notifications (DB + FCM Push)
        $notifTitle = "Renewal Needs Attention";
        $notifBody = "We couldn't verify your payment. Please recheck the details and resubmit, or contact support.";
        
        \App\Models\Notification::create([
            'user_type' => 'institute',
            'user_id' => $institute->id,
            'title' => $notifTitle,
            'message' => $notifBody,
            'type' => 'subscription_alert',
            'is_read' => false,
        ]);

        if (!empty($institute->fcm_token)) {
            $fcmService = app(\App\Services\FCMService::class);
            $fcmService->send($institute->fcm_token, $notifTitle, $notifBody, [
                'type' => 'subscription_alert',
                'status' => 'rejected',
            ]);
        }

        Activity::log("Manual renewal rejected for {$renewal->institute->institute_name}");

        return redirect()->back()->with('success', "Renewal request has been rejected.");
    }
}
