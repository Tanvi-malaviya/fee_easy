<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InstituteProfileController extends Controller
{
    /**
     * Get the authenticated institute's profile.
     */
    public function show(Request $request)
    {
        $institute = $request->user();
        $subscription = $institute->subscriptions()->latest()->first();

        return response()->json([
            'status' => 'success',
            'data' => $institute,
            'subscription' => $subscription
        ]);
    }

    /**
     * Update the authenticated institute's profile.
     */
    public function update(Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'institute_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:institutes,email,' . $institute->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only([
            'institute_name',
            'name',
            'email',
            'phone',
            'address',
            'address_line_2',
            'city',
            'state',
            'country',
            'pincode'
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($institute->logo && Storage::disk('public')->exists($institute->logo)) {
                Storage::disk('public')->delete($institute->logo);
            }

            $path = $request->file('logo')->store('institutes/logos', 'public');
            $data['logo'] = $path;
        }

        $institute->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $institute
        ]);
    }
}
