<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RevenueController extends Controller
{
    /**
     * Display the revenue dashboard.
     */
    public function index()
    {
        // Daily Revenue (Today)
        $dailyRevenue = SubscriptionPayment::whereDate('paid_at', Carbon::today())->sum('amount');

        // This Month Revenue
        $thisMonthRevenue = SubscriptionPayment::whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('amount');

        // This Year Revenue
        $thisYearRevenue = SubscriptionPayment::whereYear('paid_at', Carbon::now()->year)->sum('amount');

        // Total Lifetime Revenue
        $totalRevenue = SubscriptionPayment::sum('amount');

        // Recent Transactions
        $transactions = SubscriptionPayment::with('subscription.institute')
            ->orderBy('paid_at', 'desc')
            ->paginate(15);

        // Data for manual recording
        // Now showing all active institutes to allow recording payments for anyone
        $institutes = \App\Models\Institute::where('status', 'active')
            ->orderBy('institute_name')
            ->get();

        return view('revenue.index', compact(
            'dailyRevenue',
            'thisMonthRevenue',
            'thisYearRevenue',
            'totalRevenue', 
            'transactions',
            'institutes'
        ));
    }

    /**
     * Store a manual payment record.
     */
    public function storeManualPayment(Request $request)
    {
        $request->validate([
            'institute_id' => 'required|exists:institutes,id',
            'amount' => 'required|numeric|min:1',
            'paid_at' => 'required|date',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        // Find the latest active/expired subscription or create a manual entry one if none exists
        $subscription = \App\Models\Subscription::where('institute_id', $request->institute_id)
            ->latest()
            ->first();

        if (!$subscription) {
            // Create a placeholder subscription if they don't have one to attach revenue to
            $subscription = \App\Models\Subscription::create([
                'institute_id' => $request->institute_id,
                'plan_name' => 'Manual Record',
                'amount' => $request->amount,
                'start_date' => $request->paid_at,
                'end_date' => \Carbon\Carbon::parse($request->paid_at)->addMonth(),
                'status' => 'active',
            ]);
        }

        $payment = SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'amount' => $request->amount,
            'transaction_id' => $request->transaction_id ?? 'MANUAL-' . strtoupper(uniqid()),
            'payment_gateway' => 'manual',
            'paid_at' => $request->paid_at,
        ]);

        \App\Models\Activity::log("Manual payment recorded: ₹{$payment->amount} for {$payment->subscription->institute->institute_name}");

        return redirect()->back()->with('success', 'Manual payment recorded successfully.');
    }
}
