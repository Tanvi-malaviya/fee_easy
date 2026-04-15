<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\InstituteWhatsappSetting;
use Illuminate\Http\Request;

class InstituteWhatsappSettingController extends Controller
{
    public function show(Request $request)
    {
        $settings = $request->user()->whatsappSettings;

        return response()->json([
            'status' => 'success',
            'data' => $settings,
        ]);
    }

    public function store(Request $request)
    {
        return $this->saveSettings($request);
    }

    public function update(Request $request)
    {
        return $this->saveSettings($request);
    }

    protected function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|regex:/^[0-9]{10,15}$/',
            'access_token' => 'required|string',
            'phone_number_id' => 'nullable|string|max:255',
            'business_account_id' => 'nullable|string|max:255',
        ]);

        $validated['is_active'] = ! empty($validated['access_token']) && ! empty($validated['phone_number']);
        $validated['last_verified_at'] = now();

        $settings = InstituteWhatsappSetting::updateOrCreate([
            'institute_id' => $request->user()->id,
        ], array_merge(['institute_id' => $request->user()->id], $validated));

        return response()->json([
            'status' => 'success',
            'message' => 'WhatsApp settings saved successfully.',
            'data' => $settings,
        ]);
    }
}
