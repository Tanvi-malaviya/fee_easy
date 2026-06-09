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
        $dueFees = \App\Models\Fee::where('institute_id', $institute->id)->sum(\DB::raw('total_amount - paid_amount'));

        $stats = [
            'total_students' => $institute->students()->count(),
            'monthly_revenue' => $totalRevenue,
            'pending_fees' => $dueFees,
            'total_fees' => $totalFees,
            'today_attendance' => \App\Models\Student::where('institute_id', $institute->id)
                ->whereHas('attendance', function ($q) use ($today) {
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

        // Active subscription & "expiring soon" countdown (shown when 7 days or less remain)
        $activeSubscription = $institute->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', $today)
            ->orderByDesc('end_date')
            ->first();

        $subscriptionDaysLeft = $activeSubscription
            ? $today->diffInDays(\Carbon\Carbon::parse($activeSubscription->end_date)->startOfDay(), false)
            : null;

        // Payment/Bank settings from admin panel (SystemSetting)
        $paymentSettings = [
            'bank_holder_name' => \App\Models\SystemSetting::get('bank_holder_name', 'Tuoora Education'),
            'bank_name'        => \App\Models\SystemSetting::get('bank_name', 'HDFC Bank'),
            'bank_account'     => \App\Models\SystemSetting::get('bank_account_number', '—'),
            'bank_ifsc'        => \App\Models\SystemSetting::get('bank_ifsc', '—'),
            'qr_path'          => \App\Models\SystemSetting::get('payment_qr_path', 'payment_qr_code.png'),
        ];

        return view('institute.dashboard', compact('stats', 'institute', 'recent_batches', 'recent_students', 'paymentSettings', 'activeSubscription', 'subscriptionDaysLeft'));
    }

    /**
     * Show the subscription renewal form page.
     */
    public function showRenewalForm()
    {
        $institute = Auth::guard('institute')->user();

        // Payment/Bank settings from admin panel (SystemSetting)
        $paymentSettings = [
            'bank_holder_name' => \App\Models\SystemSetting::get('bank_holder_name', 'Tuoora Education'),
            'bank_name'        => \App\Models\SystemSetting::get('bank_name', 'HDFC Bank'),
            'bank_account'     => \App\Models\SystemSetting::get('bank_account_number', '—'),
            'bank_ifsc'        => \App\Models\SystemSetting::get('bank_ifsc', '—'),
            'qr_path'          => \App\Models\SystemSetting::get('payment_qr_path', 'payment_qr_code.png'),
        ];

        return view('institute.subscription.renew', compact('institute', 'paymentSettings'));
    }


    /**
     * Submit an offline subscription renewal request.
     */
    public function submitRenewal(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string|max:255',
            'screenshot' => 'required|image|max:10240', // Max 10MB
            'message' => 'nullable|string|max:1000',
        ]);

        $institute = Auth::guard('institute')->user();

        $path = null;
        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('renewal_screenshots', 'public');
        }

        \App\Models\SubscriptionRenewal::create([
            'institute_id' => $institute->id,
            'transaction_id' => $request->transaction_id,
            'screenshot' => $path,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your subscription renewal request has been submitted successfully. We will review and activate it shortly!',
        ]);
    }
}
