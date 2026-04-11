<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Notification;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    /**
     * Display the broadcast center.
     */
    public function index(Request $request)
    {
        $totalInstitutes = Institute::count();
        $subscribedInstitutes = Institute::whereHas('subscriptions', function ($query) {
            $query->where('end_date', '>=', now())
                  ->whereIn('status', ['active', 'trial']);
        })->count();

        $query = Notification::where('type', 'system_broadcast')
            ->select('title', 'message', 'image', 'created_at')
            ->groupBy('title', 'message', 'image', 'created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        $recentNotifications = $query->latest()
            ->take(10)
            ->get();

        if ($request->ajax()) {
            return view('broadcast.table_rows', compact('recentNotifications'))->render();
        }
            
        return view('broadcast.index', compact('recentNotifications', 'totalInstitutes', 'subscribedInstitutes'));
    }

    /**
     * Send a mass broadcast.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'message' => 'required|string',
            'target' => 'required|in:all,subscribed',
            'channels' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('broadcasts', 'public');
        }

        $channels = $validated['channels'] ?? ['dashboard'];

        $query = Institute::query();
        if ($validated['target'] === 'subscribed') {
            $query->whereHas('subscriptions', function ($query) {
                $query->where('end_date', '>=', now())
                      ->whereIn('status', ['active', 'trial']);
            });
        }

        $institutes = $query->get();
        $sentCount = 0;

        foreach ($institutes as $institute) {
            // Channel 1: Dashboard Notification
            if (in_array('dashboard', $channels)) {
                Notification::create([
                    'user_type' => 'institute',
                    'user_id' => $institute->id,
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'image' => $imagePath,
                    'type' => 'system_broadcast',
                ]);
            }

            // Channel 2: WhatsApp (Mock for now, but uses Step 9 infrastructure)
            if (in_array('whatsapp', $channels)) {
                $whatsapp = $institute->whatsappSettings;
                if ($whatsapp && $whatsapp->is_active && $whatsapp->access_token) {
                    // In a real app, you'd trigger a job: SendWhatsAppBroadcast::dispatch($institute, $message);
                }
            }

            $sentCount++;
        }

        Activity::log("Mass broadcast dispatched: {$validated['title']} to {$sentCount} institutes.");

        return redirect()->back()->with('success', "Success! Broadcast sent to {$sentCount} institutes.");
    }
}
