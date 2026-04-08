<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\InstituteWhatsappSetting;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    /**
     * Display the WhatsApp management board.
     */
    public function index()
    {
        // Get all institutes with their whatsapp settings
        $institutes = Institute::with('whatsappSettings')->paginate(10);
        return view('whatsapp.index', compact('institutes'));
    }

    /**
     * Update or create WhatsApp settings for an institute.
     */
    public function update(Request $request, Institute $institute)
    {
        $validated = $request->validate([
            'access_token' => 'nullable|string',
            'phone_number_id' => 'nullable|string',
            'business_account_id' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Ensure is_active is boolean since checkbox might not send it if unchecked
        $validated['is_active'] = $request->has('is_active');

        $institute->whatsappSettings()->updateOrCreate(
            ['institute_id' => $institute->id],
            $validated
        );

        return redirect()->back()->with('success', "WhatsApp settings for '{$institute->institute_name}' updated successfully.");
    }

    /**
     * Verify the connection (Mock for now).
     */
    public function verify(Institute $institute)
    {
        $settings = $institute->whatsappSettings;

        if (!$settings || !$settings->access_token || !$settings->phone_number_id) {
            return redirect()->back()->with('error', 'Incomplete credentials. Please provide Access Token and Phone Number ID.');
        }

        // Mocking an API call
        // In a real app, you would use Http::withToken($settings->access_token)->get(...)
        
        $settings->update([
            'last_verified_at' => now(),
            'is_active' => true
        ]);

        return redirect()->back()->with('success', "Connection verified successfully! '{$institute->institute_name}' is now ready to send messages.");
    }
}
