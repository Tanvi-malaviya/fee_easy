<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\StaffRole;
use App\Models\StaffDepartment;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        $roles = StaffRole::where('institute_id', $institute->id)->get();
        $departments = StaffDepartment::where('institute_id', $institute->id)->get();
        $totalStaff = Staff::where('institute_id', $institute->id)->count();

        return view('institute.staff.index', compact('roles', 'departments', 'totalStaff'));
    }

    public function store(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'nullable|string|max:20',
            'staff_role_id' => 'required|exists:staff_roles,id',
            'staff_department_id' => 'required|exists:staff_departments,id',
            'employment_type' => 'required|in:Salary,Hourly',
            'base_salary' => 'required|numeric|min:0',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $validated['institute_id'] = $institute->id;
        $validated['status'] = 1; // Active by default
        $validated['employee_id'] = 'STF-' . strtoupper(substr(uniqid(), -5));

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('staff_profiles', 'public');
            $validated['profile_image'] = $path;
        }

        $staff = Staff::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Staff member added successfully',
                'data' => $staff
            ]);
        }

        return redirect()->back()->with('success', 'Staff member added successfully');
    }

    public function destroy($id)
    {
        $institute = Auth::guard('institute')->user();
        $staff = Staff::where('institute_id', $institute->id)->findOrFail($id);

        if ($staff->profile_image) {
            Storage::disk('public')->delete($staff->profile_image);
        }

        $staff->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Staff member deleted successfully'
        ]);
    }
}
