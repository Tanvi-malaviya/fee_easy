<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Update the institute's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:institute'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:15',
                'different:current_password',
                'confirmed',
                'regex:/[a-z]/',      // at least one lowercase
                'regex:/[A-Z]/',      // at least one uppercase
                'regex:/[0-9]/',      // at least one number
                'regex:/[\W_]/',      // at least one special character
            ],
        ], [
            'current_password.current_password' => 'The current password is incorrect.',
            'password.different' => 'New password cannot be the same as the current password.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 15 characters.',
            'password.confirmed' => 'New password and confirmation do not match.',
            'password.regex' => 'Password must include an uppercase letter, a lowercase letter, a number, and a special character.',
        ]);

        $request->user('institute')->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'Password updated successfully.']);
    }

    /**
     * Update the institute's profile information.
     */
    public function update(Request $request)
    {
        $institute = Auth::guard('institute')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'institute_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'digits:10'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:10'],
        ]);

        $institute->update($validated);

        return response()->json(['message' => 'Profile updated successfully.']);
    }

    /**
     * Update the active website template.
     */
    public function updateTemplate(Request $request)
    {
        $institute = Auth::guard('institute')->user();

        $validated = $request->validate([
            'template_id' => ['required', 'integer', 'between:1,5'],
        ]);

        $institute->update([
            'template_id' => $validated['template_id']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Website template updated successfully.'
        ]);
    }
}
