<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherProfileController extends Controller
{
    public function show(Request $request)
    {
        $staff = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => $staff->load(['role', 'department', 'departments', 'institute:id,institute_name,logo']),
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $staff = $request->user();

        $request->validate([
            'profile_image' => 'required|image|max:2048',
        ]);

        if ($staff->profile_image) {
            Storage::disk('public')->delete($staff->profile_image);
        }

        $path = $request->file('profile_image')->store('staff_profiles', 'public');
        $staff->update(['profile_image' => $path]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile photo updated.',
            'data' => $staff->fresh(),
        ]);
    }
}
