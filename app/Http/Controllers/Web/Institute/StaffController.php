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
            'staff_department_id' => 'nullable',
            'staff_department_ids' => 'nullable',
            'employment_type' => 'required|in:Salary,Hourly',
            'base_salary' => 'required|numeric|min:1|max:999999',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        // Parse department IDs
        $deptIds = [];
        if ($request->has('staff_department_ids')) {
            $deptIds = is_array($request->staff_department_ids) ? $request->staff_department_ids : explode(',', $request->staff_department_ids);
        } elseif ($request->filled('staff_department_id')) {
            $deptIds = is_array($request->staff_department_id) ? $request->staff_department_id : [$request->staff_department_id];
        }
        $deptIds = array_values(array_filter(array_unique(array_map('intval', $deptIds))));

        if (empty($deptIds)) {
            return back()->withErrors(['staff_department_id' => 'Please select at least one department'])->withInput();
        }

        unset($validated['staff_department_ids']);
        $plainPassword = \Illuminate\Support\Str::random(8);
        $validated['password'] = \Illuminate\Support\Facades\Hash::make($plainPassword);
        $validated['institute_id'] = $institute->id;
        $validated['status'] = 1; // Active by default
        $validated['employee_id'] = Staff::generateEmployeeId($institute->id);
        $validated['staff_department_id'] = $deptIds[0];

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('staff_profiles', 'public');
            $validated['profile_image'] = $path;
        }

        $staff = Staff::create($validated);
        $staff->departments()->sync($deptIds);

        // Send welcome email to staff member with login credentials
        try {
            $roleName = $staff->role ? $staff->role->name : 'Staff';
            $departmentNames = $staff->departments->pluck('name')->implode(', ') ?: ($staff->department ? $staff->department->name : 'N/A');

            \App\Services\InstituteMailService::send(
                $institute,
                $staff->email,
                new \App\Mail\StaffAddedMail(
                    $staff->full_name,
                    $staff->email,
                    $staff->employee_id,
                    $roleName,
                    $departmentNames,
                    $institute->institute_name,
                    $institute->logo,
                    $plainPassword
                )
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send staff welcome email: ' . $e->getMessage());
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Staff member added successfully',
                'data' => $staff->load(['role', 'department', 'departments'])
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
        $staff = Staff::with(['role', 'department', 'departments', 'attendances', 'salaries'])
            ->where('institute_id', $institute->id)
            ->findOrFail($id);
        $departments = StaffDepartment::orderBy('name')->get();

        return view('institute.staff.show', compact('staff', 'departments'));
    }

    public function edit($id)
    {
        $institute = Auth::guard('institute')->user();
        $staff = Staff::with('departments')->where('institute_id', $institute->id)->findOrFail($id);
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
            'staff_department_id' => 'nullable',
            'staff_department_ids' => 'nullable',
            'employment_type' => 'required|in:Salary,Hourly',
            'base_salary' => 'required|numeric|min:1|max:999999',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        // Handle departments if provided
        if ($request->has('staff_department_ids') || $request->has('staff_department_id')) {
            $deptIds = [];
            if ($request->has('staff_department_ids')) {
                $deptIds = is_array($request->staff_department_ids) ? $request->staff_department_ids : explode(',', $request->staff_department_ids);
            } elseif ($request->filled('staff_department_id')) {
                $deptIds = is_array($request->staff_department_id) ? $request->staff_department_id : [$request->staff_department_id];
            }
            $deptIds = array_values(array_filter(array_unique(array_map('intval', $deptIds))));

            if (!empty($deptIds)) {
                $validated['staff_department_id'] = $deptIds[0];
                $staff->departments()->sync($deptIds);
            }
        }
        unset($validated['staff_department_ids']);

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
                'message' => 'Staff member updated successfully',
                'data' => $staff->load(['role', 'department', 'departments'])
            ]);
        }

        return redirect()->route('institute.staff.show', $staff->id)->with('success', 'Staff updated successfully');
    }
}
