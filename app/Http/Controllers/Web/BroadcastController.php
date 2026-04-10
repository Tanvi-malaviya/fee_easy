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
    public function index()
    {
        // Get unique recent broadcasts (grouped by content)
        $recentNotifications = Notification::where('type', 'system_broadcast')
            ->select('title', 'message', 'created_at')
            ->groupBy('title', 'message', 'created_at')
            ->latest()
            ->take(10)
            ->get();
            
        return view('broadcast.index', compact('recentNotifications'));
    }

    /**
     * Send a mass broadcast.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'message' => 'required|string',
            'target' => 'required|in:all,active',
            'channels' => 'nullable|array',
        ]);

        $channels = $validated['channels'] ?? ['dashboard'];

        $query = Institute::query();
        if ($validated['target'] === 'active') {
            $query->where('status', 'active');
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
