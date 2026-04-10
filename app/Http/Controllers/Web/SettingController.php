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
        ]);

        foreach ($request->input('settings') as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }

        Activity::log("Global system settings updated");

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }
}
