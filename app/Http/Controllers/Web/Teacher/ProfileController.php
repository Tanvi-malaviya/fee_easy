<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user()->load(['role', 'department', 'departments', 'institute', 'attendances', 'salaries']);

        return view('teacher.profile.index', compact('teacher'));
    }

    public function updateAvatar(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $request->validate(['profile_image' => 'required|image|max:2048']);

        if ($teacher->profile_image) {
            Storage::disk('public')->delete($teacher->profile_image);
        }

        $path = $request->file('profile_image')->store('staff_profiles', 'public');
        $teacher->update(['profile_image' => $path]);

        return back()->with('success', 'Profile photo updated.');
    }
}
