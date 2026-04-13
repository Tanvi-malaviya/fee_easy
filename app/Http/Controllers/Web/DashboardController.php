<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Institute;
use App\Models\Subscription;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalInstitutes = Institute::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $expiredSubscriptions = Subscription::where('status', 'expired')->count();
        $totalRevenue = \App\Models\SubscriptionPayment::sum('amount');

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
            ]
        ];
        
        $currency = \App\Models\SystemSetting::get('currency_symbol', '₹');
        
        return view('dashboard', compact(
            'totalInstitutes', 
            'activeSubscriptions', 
            'expiredSubscriptions', 
            'totalRevenue',
            'analytics',
            'currency'
        ));
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
}
