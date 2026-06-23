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

        $currentToken = $institute->currentAccessToken();
        $isTransient = $currentToken instanceof \Laravel\Sanctum\TransientToken;
        $session = null;
        if ($currentToken && !$isTransient) {
            $session = \App\Models\DeviceSession::where('token_id', $currentToken->id)->first();
        }

        if (!$session) {
            $detection = \App\Models\DeviceSession::detect($request);
            $sessionId = $detection['session_id'];
            if (!empty($sessionId)) {
                $session = $institute->deviceSessions()
                    ->where('session_id', $sessionId)
                    ->first();
            }
        }

        if ($session) {
            $session->update(['last_open' => now()]);
        }

        // Get all active sessions for this institute
        $activeSessions = \App\Models\DeviceSession::where('institute_id', $institute->id)
            ->orderBy('last_login', 'desc')
            ->get()
            ->map(function ($s) use ($currentToken, $isTransient, $request) {
                $isCurrent = false;
                if ($currentToken) {
                    if (!$isTransient) {
                        $isCurrent = $s->token_id == $currentToken->id;
                    } else {
                        $detection = \App\Models\DeviceSession::detect($request);
                        $isCurrent = !empty($detection['session_id']) && $s->session_id === $detection['session_id'];
                    }
                }
                return [
                    'id' => $s->id,
                    'device' => $s->device,
                    'os' => $s->os,
                    'last_login' => $s->last_login ? $s->last_login->toDateTimeString() : null,
                    'last_open' => $s->last_open ? $s->last_open->toDateTimeString() : null,
                    'fcm_token' => $s->fcm_token,
                    'is_current' => $isCurrent,
                    'is_app' => !empty($s->token_id),
                    'session_type' => !empty($s->token_id) ? 'app' : 'web',
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => array_merge(
                $institute->toArray(),
                [
                    'device' => $session ? $session->device : null,
                    'os' => $session ? $session->os : null,
                    'last_login' => $session && $session->last_login ? $session->last_login->toDateTimeString() : null,
                    'last_open' => $session && $session->last_open ? $session->last_open->toDateTimeString() : null,
                    'fcm_token' => $session ? $session->fcm_token : null,
                    'active_sessions' => $activeSessions,
                ]
            ),
            'subscription' => $subscription
        ]);
    }

    /**
     * Delete the authenticated institute's account and revoke all tokens.
     */
    public function destroy(Request $request)
    {
        $institute = $request->user();

        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $institute->tokens()->delete();
        $institute->deviceSessions()->delete();
        $institute->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Institute account deleted successfully.'
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
            'email' => 'required|email|max:255|unique:institutes,email,' . $institute->id,
            'alternate_email' => 'nullable|email|max:255',
            'phone' => 'required|digits:10',
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
            'email',
            'alternate_email',
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
            'is_profile_setup' => $institute->isProfileComplete(),
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

    /**
     * Update the authenticated institute's UPI payment settings.
     * Accepts field name as either 'upi_qr_code' or 'upi_qr_code_url' (both work).
     */
    public function updatePaymentSettings(Request $request)
    {
        $institute = $request->user();

        // upi_qr_code is required only when institute has no existing QR code saved
        $qrCodeRule = $institute->upi_qr_code ? 'nullable' : 'required';

        $data = $request->validate([
            'upi_id' => 'required|string|regex:/^[\w\.\-]+@[\w\-]+$/',
            'upi_qr_code' => "{$qrCodeRule}|image|mimes:jpeg,png,jpg|max:2048",
            'upi_qr_code_url' => "nullable|image|mimes:jpeg,png,jpg|max:2048", // alias field name
        ], [
            'upi_id.required' => 'UPI ID is required.',
            'upi_id.regex' => 'Invalid UPI ID format. Use format: name@bank (e.g. merchant@okaxis).',
            'upi_qr_code.required' => 'QR code image is required.',
            'upi_qr_code.image' => 'QR code must be an image file.',
            'upi_qr_code.mimes' => 'QR code must be a JPEG or PNG image.',
            'upi_qr_code.max' => 'QR code image must not exceed 2MB.',
        ]);

        $updateData = ['upi_id' => $data['upi_id']];

        // Accept either field name: 'upi_qr_code' or 'upi_qr_code_url'
        $qrFile = $request->file('upi_qr_code') ?? $request->file('upi_qr_code_url');

        if ($qrFile) {
            // Delete old QR code image if exists
            if ($institute->upi_qr_code && Storage::disk('public')->exists($institute->upi_qr_code)) {
                Storage::disk('public')->delete($institute->upi_qr_code);
            }

            // Store new QR code
            $path = $qrFile->store('upi_qrs', 'public');
            $updateData['upi_qr_code'] = $path;
        }

        $institute->update($updateData);

        // Refresh model from DB to get latest upi_qr_code_url accessor value
        $institute->refresh();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment settings updated successfully.',
            'data' => [
                'upi_id' => $institute->upi_id,
                'upi_qr_code_url' => $institute->upi_qr_code_url,
            ]
        ]);
    }

    /**
     * Terminate / log out a specific device session.
     */
    public function logoutDeviceSession(Request $request, $id)
    {
        $institute = $request->user();
        
        $session = \App\Models\DeviceSession::where('institute_id', $institute->id)
            ->where('id', $id)
            ->first();

        if ($session) {
            $tokenId = $session->token_id;
            $session->update(['token_id' => null]);
            $session->delete();

            if ($tokenId) {
                \DB::table('personal_access_tokens')->where('id', $tokenId)->delete();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Device session terminated successfully.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Session not found.'
        ], 404);
    }

    /**
     * Update the website template for the authenticated institute.
     */
    public function updateTemplate(Request $request)
    {
        $institute = $request->user();

        $validated = $request->validate([
            'template_id' => ['required', 'integer', 'between:1,5'],
        ]);

        $institute->update([
            'template_id' => $validated['template_id']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Website template updated successfully.',
            'data' => [
                'template_id' => $institute->template_id
            ]
        ]);
    }
}
