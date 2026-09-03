<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Institute;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionRenewal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalInstitutes = Institute::count();

        // "Active Access" = institutes whose latest plan is active and not past its expiry date.
        $activeAccessCount = Subscription::where('status', 'active')->where('end_date', '>=', $today)->count();
        $expiringSoonCount = Subscription::where('status', 'active')
            ->whereBetween('end_date', [$today, $today->copy()->addDays(Subscription::EXPIRE_SOON_THRESHOLD_DAYS)])
            ->count();
        $expiredCount = Subscription::where('end_date', '<', $today)->whereIn('status', ['active', 'expired'])->count();
        $pendingRenewalsCount = SubscriptionRenewal::where('status', 'pending')->count();

        $totalRevenue = SubscriptionPayment::sum('amount');
        $revenueThisMonth = SubscriptionPayment::whereMonth('paid_at', $today->month)->whereYear('paid_at', $today->year)->sum('amount');
        $revenueLastMonth = SubscriptionPayment::whereMonth('paid_at', $today->copy()->subMonthNoOverflow()->month)
            ->whereYear('paid_at', $today->copy()->subMonthNoOverflow()->year)
            ->sum('amount');

        $newInstitutesThisMonth = Institute::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->count();
        $newInstitutesLastMonth = Institute::whereMonth('created_at', $today->copy()->subMonthNoOverflow()->month)
            ->whereYear('created_at', $today->copy()->subMonthNoOverflow()->year)
            ->count();

        $avgRevenuePerInstitute = $totalInstitutes > 0 ? $totalRevenue / $totalInstitutes : 0;

        [$renewalsThisMonth, $newSalesThisMonth] = $this->getRenewalsVsNewSales();

        // Analytics Data
        $analytics = [
            'institutes' => [
                'weekly' => $this->getInstituteGrowth('weekly'),
                'monthly' => $this->getInstituteGrowth('monthly'),
                'yearly' => $this->getInstituteGrowth('yearly'),
            ],
            'revenue' => [
                'weekly' => $this->getRevenueAnalysis('weekly'),
                'monthly' => $this->getRevenueAnalysis('monthly'),
                'yearly' => $this->getRevenueAnalysis('yearly'),
            ],
            'expiry' => [
                'weekly' => $this->getExpiryTrend('weekly'),
                'monthly' => $this->getExpiryTrend('monthly'),
                'yearly' => $this->getExpiryTrend('yearly'),
            ],
        ];

        $planMix = Subscription::where('status', 'active')
            ->where('end_date', '>=', $today)
            ->selectRaw('plan_name, count(*) as total')
            ->groupBy('plan_name')
            ->orderByDesc('total')
            ->get();

        $currency = \App\Models\SystemSetting::get('currency_symbol', '₹');

        return view('dashboard', compact(
            'totalInstitutes',
            'activeAccessCount',
            'expiringSoonCount',
            'expiredCount',
            'pendingRenewalsCount',
            'totalRevenue',
            'revenueThisMonth',
            'revenueLastMonth',
            'newInstitutesThisMonth',
            'newInstitutesLastMonth',
            'avgRevenuePerInstitute',
            'renewalsThisMonth',
            'newSalesThisMonth',
            'analytics',
            'planMix',
            'currency'
        ));
    }

    /**
     * Split this month's subscription payments into renewals (subscription
     * already had a payment before this month) vs new sales (first-ever
     * payment for that subscription), counted once per subscription.
     */
    private function getRenewalsVsNewSales(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();

        $subsWithPriorPayment = SubscriptionPayment::where('paid_at', '<', $startOfMonth)
            ->pluck('subscription_id')
            ->unique();

        $thisMonthSubIds = SubscriptionPayment::where('paid_at', '>=', $startOfMonth)
            ->pluck('subscription_id')
            ->unique();

        $renewals = 0;
        $newSales = 0;

        foreach ($thisMonthSubIds as $subId) {
            if ($subsWithPriorPayment->contains($subId)) {
                $renewals++;
            } else {
                $newSales++;
            }
        }

        return [$renewals, $newSales];
    }

    private function getInstituteGrowth($type)
    {
        $labels = [];
        $values = [];

        if ($type === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subDays($i);
                $labels[] = $date->format('d M');
                $values[] = Institute::whereDate('created_at', $date->toDateString())->count();
            }
        } elseif ($type === 'monthly') {
            for ($i = 29; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subDays($i);
                $labels[] = $date->format('d M');
                $values[] = Institute::whereDate('created_at', $date->toDateString())->count();
            }
        } elseif ($type === 'yearly') {
            for ($i = 11; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subMonths($i);
                $labels[] = $date->format('M y'); // Shortened to 'May 25'
                $values[] = Institute::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count();
            }
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getRevenueAnalysis($type)
    {
        $labels = [];
        $values = [];

        if ($type === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subDays($i);
                $labels[] = $date->format('d M');
                $values[] = \App\Models\SubscriptionPayment::whereDate('paid_at', $date->toDateString())->sum('amount');
            }
        } elseif ($type === 'monthly') {
            for ($i = 29; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subDays($i);
                $labels[] = $date->format('d M');
                $values[] = \App\Models\SubscriptionPayment::whereDate('paid_at', $date->toDateString())->sum('amount');
            }
        } elseif ($type === 'yearly') {
            for ($i = 11; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subMonths($i);
                $labels[] = $date->format('M y'); // Shortened
                $values[] = \App\Models\SubscriptionPayment::whereMonth('paid_at', $date->month)->whereYear('paid_at', $date->year)->sum('amount');
            }
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Count of plans that lapsed (end_date fell) within each bucket —
     * the "who stopped renewing" companion to Institute Growth.
     */
    private function getExpiryTrend($type)
    {
        $labels = [];
        $values = [];

        if ($type === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subDays($i);
                $labels[] = $date->format('d M');
                $values[] = Subscription::whereDate('end_date', $date->toDateString())->count();
            }
        } elseif ($type === 'monthly') {
            for ($i = 29; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subDays($i);
                $labels[] = $date->format('d M');
                $values[] = Subscription::whereDate('end_date', $date->toDateString())->count();
            }
        } elseif ($type === 'yearly') {
            for ($i = 11; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subMonths($i);
                $labels[] = $date->format('M y');
                $values[] = Subscription::whereMonth('end_date', $date->month)->whereYear('end_date', $date->year)->count();
            }
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
