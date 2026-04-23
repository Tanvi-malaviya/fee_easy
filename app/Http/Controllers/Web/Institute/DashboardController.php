<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the institute dashboard.
     */
    public function index()
    {
        $institute = Auth::guard('institute')->user();
        $today = \Carbon\Carbon::today();
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
        
        // Use the exact same logic as the Mobile API (InstituteReportController@dashboard)
        $totalRevenue = \App\Models\SubscriptionPayment::whereHas('subscription', function ($query) use ($institute) {
            $query->where('institute_id', $institute->id);
        })->sum('amount');

        $totalFees = \App\Models\Fee::where('institute_id', $institute->id)->sum('total_amount');
        $dueFees = \App\Models\Fee::where('institute_id', $institute->id)->sum('due_amount');
        
        $stats = [
            'total_students' => $institute->students()->count(),
            'monthly_revenue' => $totalRevenue,
            'pending_fees' => $dueFees,
            'total_fees' => $totalFees,
            'today_attendance' => \App\Models\Student::where('institute_id', $institute->id)
                ->whereHas('attendance', function($q) use ($today) {
                    $q->where('date', $today)->where('status', 'Present');
                })->count(),
            'total_batches' => $institute->batches()->count(),
            'active_subscriptions' => $institute->subscriptions()->where('status', 'active')->count(),
        ];

        // Format for display
        $stats['monthly_revenue_formatted'] = number_format($stats['monthly_revenue']);
        $stats['pending_fees_formatted'] = number_format($stats['pending_fees']);
        $stats['total_fees_formatted'] = number_format($stats['total_fees']);

        $recent_batches = $institute->batches()->latest()->limit(5)->get();
        $recent_students = $institute->students()->latest()->limit(5)->get();

        return view('institute.dashboard', compact('stats', 'institute', 'recent_batches', 'recent_students'));
    }
}
