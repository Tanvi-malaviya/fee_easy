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
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'logo' => 'nullable',
            'logo_url' => 'nullable',
        ]);

        $data = $request->only([
            'institute_name',
            'name',
            'phone',
            'address',
            'address_line_2',
            'city',
            'state',
            'country',
            'pincode'
        ]);

        if ($request->hasFile('logo') || $request->hasFile('logo_url')) {
            // Delete old logo if exists
            if ($institute->logo && Storage::disk('public')->exists($institute->logo)) {
                Storage::disk('public')->delete($institute->logo);
            }

            $file = $request->hasFile('logo') ? $request->file('logo') : $request->file('logo_url');
            $path = $file->store('institutes/logos', 'public');
            $data['logo'] = $path;
        } elseif (($request->has('logo') && !empty($request->logo)) || ($request->has('logo_url') && !empty($request->logo_url))) {
            $logoStr = $request->has('logo') ? $request->logo : $request->logo_url;
            // Check if it's a valid base64 data URI
            if (preg_match('/^data:image\/(\w+);base64,/', $logoStr, $type)) {
                $logoStr = substr($logoStr, strpos($logoStr, ',') + 1);
                $type = strtolower($type[1]); // png, jpeg, etc.

                if (in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'svg'])) {
                    $logoStr = base64_decode($logoStr);

                    if ($logoStr !== false) {
                        if ($institute->logo && Storage::disk('public')->exists($institute->logo)) {
                            Storage::disk('public')->delete($institute->logo);
                        }

                        $filename = 'institutes/logos/' . uniqid() . '.' . $type;
                        Storage::disk('public')->put($filename, $logoStr);
                        $data['logo'] = $filename;
                    }
                }
            }
        }

        $institute->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'is_profile_setup' => true,
            'data' => $institute
        ]);
    }

    /**
     * Change password for institute.
     */
    public function changePassword(\Illuminate\Http\Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $institute->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password does not match.'
            ], 400);
        }

        if ($request->current_password === $request->new_password) {
            return response()->json([
                'status' => 'error',
                'message' => 'New password cannot be the same as current password.',
            ], 422);
        }

        $newPassword = $request->new_password;
        $errors = [];

        if (strlen($newPassword) < 8 || strlen($newPassword) > 15) {
            $errors[] = 'Password must be between 8 and 15 characters long.';
        }
        if (!preg_match('/[a-z]/i', $newPassword)) {
            $errors[] = 'Password must contain at least 1 standard letter.';
        }
        if (!preg_match('/[A-Z]/', $newPassword)) {
            $errors[] = 'Password must contain at least 1 capital letter.';
        }
        if (!preg_match('/\d/', $newPassword)) {
            $errors[] = 'Password must contain at least 1 number.';
        }
        if (!preg_match('/[\W_]/', $newPassword)) {
            $errors[] = 'Password must contain at least 1 special character.';
        }

        // Evaluate confirmation only if all strength criteria have passed successfully
        if (empty($errors) && $request->new_password !== $request->new_password_confirmation) {
            $errors[] = 'The new password field confirmation does not match.';
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'new_password' => $errors
                ]
            ], 422);
        }

        $institute->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully.'
        ]);
    }
}
