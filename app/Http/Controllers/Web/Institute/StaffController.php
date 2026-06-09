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
        $departments = StaffDepartment::orderBy('name')->get();
        $totalStaff = Staff::where('institute_id', $institute->id)->count();

        return view('institute.staff.index', compact('roles', 'departments', 'totalStaff'));
    }

    public function store(Request $request)
    {
        $institute = Auth::guard('institute')->user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:staff,email',
            'phone' => 'nullable|digits:10',
            'staff_role_id' => 'nullable|exists:staff_roles,id',
            'staff_department_id' => 'required|exists:staff_departments,id',
            'employment_type' => 'required|in:Salary,Hourly',
            'base_salary' => 'required|numeric|min:1|max:999999',
            'profile_image' => 'nullable|image|max:2048',
        ], [
            'staff_department_id.required' => 'staff department is required',
        ], [
            'staff_department_id' => 'staff department',
        ]);

        $validated['institute_id'] = $institute->id;
        $validated['status'] = 1; // Active by default
        $validated['employee_id'] = 'STF-' . strtoupper(substr(uniqid(), -5));

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('staff_profiles', 'public');
            $validated['profile_image'] = $path;
        }

        $staff = Staff::create($validated);

        // Send welcome email to staff member
        try {
            $roleName = $staff->role ? $staff->role->name : 'Staff';
            $departmentName = $staff->department ? $staff->department->name : 'N/A';

            \Illuminate\Support\Facades\Mail::to($staff->email)->send(
                new \App\Mail\StaffAddedMail(
                    $staff->full_name,
                    $staff->email,
                    $staff->employee_id,
                    $roleName,
                    $departmentName,
                    $institute->institute_name,
                    $institute->logo
                )
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send staff welcome email: ' . $e->getMessage());
        }

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

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Staff member deleted successfully'
            ]);
        }

        return redirect()->route('institute.staff.index')->with('success', 'Staff member deleted successfully');
    }

    public function show($id)
    {
        $institute = Auth::guard('institute')->user();
        $staff = Staff::with(['role', 'department', 'attendances', 'salaries'])
            ->where('institute_id', $institute->id)
            ->findOrFail($id);
        $departments = StaffDepartment::orderBy('name')->get();

        return view('institute.staff.show', compact('staff', 'departments'));
    }

    public function edit($id)
    {
        $institute = Auth::guard('institute')->user();
        $staff = Staff::where('institute_id', $institute->id)->findOrFail($id);
        $roles = StaffRole::where('institute_id', $institute->id)->get();
        $departments = StaffDepartment::orderBy('name')->get();

        return view('institute.staff.edit', compact('staff', 'roles', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $institute = Auth::guard('institute')->user();
        $staff = Staff::where('institute_id', $institute->id)->findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:staff,email,' . $id,
            'phone' => 'nullable|digits:10',
            'staff_role_id' => 'nullable|exists:staff_roles,id',
            'staff_department_id' => 'required|exists:staff_departments,id',
            'employment_type' => 'required|in:Salary,Hourly',
            'base_salary' => 'required|numeric|min:1|max:999999',
            'profile_image' => 'nullable|image|max:2048',
        ], [
            'staff_department_id.required' => 'staff department is required',
        ], [
            'staff_department_id' => 'staff department',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($staff->profile_image) {
                Storage::disk('public')->delete($staff->profile_image);
            }
            $path = $request->file('profile_image')->store('staff_profiles', 'public');
            $validated['profile_image'] = $path;
        }

        $staff->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Staff updated successfully',
                'data' => $staff
            ]);
        }

        return redirect()->route('institute.staff.show', $staff->id)->with('success', 'Staff updated successfully');
    }
}
