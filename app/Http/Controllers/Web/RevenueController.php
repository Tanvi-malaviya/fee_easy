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
        // Lifetime Revenue
        $totalRevenue = SubscriptionPayment::sum('amount');

        // Monthly Revenue (Last 30 Days)
        $monthlyRevenue = SubscriptionPayment::where('paid_at', '>=', now()->subDays(30))->sum('amount');

        // Revenue this calendar month
        $thisMonthRevenue = SubscriptionPayment::whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        // Recent Transactions
        $transactions = SubscriptionPayment::with('subscription.institute')
            ->orderBy('paid_at', 'desc')
            ->paginate(15);

        return view('revenue.index', compact(
            'totalRevenue', 
            'monthlyRevenue', 
            'thisMonthRevenue', 
            'transactions'
        ));
    }
}
