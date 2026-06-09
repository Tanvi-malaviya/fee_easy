<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\Activity;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings board.
     */
    public function index()
    {
        $settings = SystemSetting::pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    /**
     * Update global settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'payment_qr_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        foreach ($request->input('settings', []) as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }

        // Handle QR Code Image Upload
        if ($request->hasFile('payment_qr_image')) {
            $file = $request->file('payment_qr_image');
            if ($file->isValid()) {
                try {
                    $filename = 'qr_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images'), $filename);

                    SystemSetting::updateOrCreate(
                        ['key' => 'payment_qr_path'],
                        ['value' => $filename, 'group' => 'general']
                    );
                } catch (\Exception $e) {
                    \Log::error('Settings QR Upload Error: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Failed to save QR code image. Please check directory permissions.');
                }
            } else {
                return redirect()->back()->with('error', 'The uploaded QR code image is not valid.');
            }
        }

        Activity::log("Global system settings updated");

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }
}
