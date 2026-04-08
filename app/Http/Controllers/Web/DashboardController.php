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
        
        return view('dashboard', compact(
            'totalInstitutes', 
            'activeSubscriptions', 
            'expiredSubscriptions', 
            'totalRevenue'
        ));
    }
}
