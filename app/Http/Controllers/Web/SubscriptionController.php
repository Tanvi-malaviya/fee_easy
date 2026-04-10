<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Institute;
use App\Models\Plan;
use App\Models\SubscriptionPayment;
use App\Models\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = Subscription::with('institute')->latest()->paginate(10);
        $institutes = Institute::where('status', 'active')->get();
        $plans = Plan::where('status', true)->get();
        
        $stats = [
            'active_count' => Subscription::where('status', 'active')->count(),
            'expiring_count' => Subscription::where('status', 'active')
                ->where('end_date', '<=', now()->addDays(7))
                ->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('amount'),
        ];
        
        return view('subscriptions.index', compact('subscriptions', 'institutes', 'plans', 'stats'));
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
        
        // Dynamic Duration Logic
        $days = $request->has('is_trial') ? $plan->trial_days : $plan->duration_days;
        $endDate = $startDate->copy()->addDays($days);

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
                'paid_at' => now(),
            ]);
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
            'amount' => 'required|numeric|min:0',
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
                'paid_at' => now(),
            ]);
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
     * Convert trial to paid subscription.
     */
    public function convertToPaid(Subscription $subscription, Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::find($request->plan_id);
        $endDate = now()->addDays($plan->duration_days);

        $subscription->update([
            'plan_name' => $plan->name,
            'amount' => $plan->price,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => $endDate,
        ]);

        // Record Conversion Payment
        if ($plan->price > 0) {
            SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'paid_at' => now(),
            ]);
        }

        Activity::log("Trial converted to paid plan: {$plan->name} for {$subscription->institute->institute_name}");

        return redirect()->back()->with('success', 'Trial converted to paid subscription.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('subscriptions.index')->with('success', 'Subscription record deleted.');
    }
}
