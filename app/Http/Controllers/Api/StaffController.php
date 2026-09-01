<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffRole;
use App\Models\StaffDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff.
     */
    public function index(Request $request)
    {
        $instituteId = auth('institute')->id() ?? ($request->user() ? $request->user()->id : null);

        if (!$instituteId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = Staff::where('institute_id', $instituteId)->with(['role', 'department', 'departments']);

        // Search by name or employee ID
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                    ->orWhere('employee_id', 'like', "%$search%");
            });
        }

        // Filter by role
        if ($request->filled('role_id')) {
            $query->where('staff_role_id', $request->role_id);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $deptId = $request->department_id;
            $query->where(function ($q) use ($deptId) {
                $q->where('staff_department_id', $deptId)
                    ->orWhereHas('departments', function ($sq) use ($deptId) {
                        $sq->where('staff_departments.id', $deptId);
                    });
            });
        }

        if ($request->has('all')) {
            $staff = $query->latest()->get();
            return response()->json([
                'status' => 'success',
                'data' => $staff
            ]);
        }

        $staff = $query->latest()->paginate($request->get('per_page', 10));

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $staff->items(),
                'total' => $staff->total(),
                'current_page' => $staff->currentPage(),
                'last_page' => $staff->lastPage(),
                'per_page' => $staff->perPage(),
                'from' => $staff->firstItem(),
                'to' => $staff->lastItem(),
            ]
        ]);
    }

    /**
     * Store a newly created staff in storage.
     */
    public function store(Request $request)
    {
        $instituteId = auth('institute')->id() ?? ($request->user() ? $request->user()->id : null);

        if (!$instituteId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:staff,email,NULL,id,institute_id,' . $instituteId,
            'staff_role_id' => 'nullable|exists:staff_roles,id,institute_id,' . $instituteId,
            'staff_department_id' => 'nullable',
            'staff_department_ids' => 'nullable',
            'employment_type' => 'required|in:Salary,Hourly',
            'base_salary' => 'required|numeric',
            'phone' => 'required|digits:10',
            'status' => 'nullable|in:active,away,offline',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Parse department IDs
        $deptIds = [];
        if ($request->has('staff_department_ids')) {
            $deptIds = is_array($request->staff_department_ids) ? $request->staff_department_ids : explode(',', $request->staff_department_ids);
        } elseif ($request->filled('staff_department_id')) {
            $deptIds = is_array($request->staff_department_id) ? $request->staff_department_id : [$request->staff_department_id];
        }
        $deptIds = array_values(array_filter(array_unique(array_map('intval', $deptIds))));

        if (empty($deptIds)) {
            return response()->json(['errors' => ['staff_department_id' => ['Please select at least one department']]], 422);
        }

        $data = $request->except(['staff_department_ids', 'password']);
        $plainPassword = $request->password ?: \Illuminate\Support\Str::random(8);
        $data['password'] = \Illuminate\Support\Facades\Hash::make($plainPassword);
        $data['institute_id'] = $instituteId;
        $data['staff_department_id'] = $deptIds[0];

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('staff/profiles', 'public');
            $data['profile_image'] = $path;
        }

        $staff = Staff::create($data);
        $staff->departments()->sync($deptIds);

        // Send welcome email to staff member with login credentials
        try {
            $institute = auth('institute')->user() ?: \App\Models\Institute::find($instituteId);
            if ($institute) {
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
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send staff welcome email: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Staff created successfully',
            'data' => $staff->load(['role', 'department', 'departments'])
        ], 201);
    }

    /**
     * Display the specified staff.
     */
    public function show(Request $request, $id)
    {
        $instituteId = auth('institute')->id() ?? ($request->user() ? $request->user()->id : null);
        $staff = Staff::where('institute_id', $instituteId)->with(['role', 'department', 'departments'])->find($id);

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $staff
        ]);
    }

    /**
     * Update the specified staff in storage.
     */
    public function update(Request $request, $id)
    {
        $instituteId = auth('institute')->id() ?? ($request->user() ? $request->user()->id : null);

        if (!$instituteId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $staff = Staff::where('institute_id', $instituteId)->find($id);

        if (!$staff) {
            return response()->json(['status' => 'error', 'message' => 'Staff not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable',
            'full_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email:rfc|unique:staff,email,' . $id . ',id,institute_id,' . $instituteId,
            'staff_role_id' => 'nullable|exists:staff_roles,id,institute_id,' . $instituteId,
            'staff_department_id' => 'nullable',
            'staff_department_ids' => 'nullable',
            'employment_type' => 'sometimes|required|in:Salary,Hourly',
            'base_salary' => 'sometimes|required|numeric',
            'phone' => 'sometimes|required|digits:10',
            'status' => 'sometimes|required|in:active,away,offline',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except(['staff_department_ids']);

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
                $data['staff_department_id'] = $deptIds[0];
                $staff->departments()->sync($deptIds);
            }
        }

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($staff->profile_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($staff->profile_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($staff->profile_image);
            }

            $path = $request->file('profile_image')->store('staff/profiles', 'public');
            $data['profile_image'] = $path;
        }

        $staff->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff updated successfully',
            'data' => $staff->load(['role', 'department', 'departments'])
        ]);
    }

    /**
     * Remove the specified staff from storage.
     */
    public function destroy(Request $request, $id)
    {
        $instituteId = auth('institute')->id() ?? ($request->user() ? $request->user()->id : null);
        $staff = Staff::where('institute_id', $instituteId)->find($id);

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $staff->delete();

        return response()->json(['message' => 'Staff deleted successfully']);
    }

    /**
     * Get all roles for dropdowns.
     */
    public function getRoles(Request $request)
    {
        $instituteId = auth('institute')->id() ?? ($request->user() ? $request->user()->id : null);
        return response()->json(StaffRole::where('institute_id', $instituteId)->get());
    }

    /**
     * Get all departments for dropdowns.
     */
    public function getDepartments(Request $request)
    {
        return response()->json(StaffDepartment::orderBy('name')->get());
    }

    /**
     * Store a new role for the institute.
     */
    public function storeRole(Request $request)
    {
        $instituteId = auth('institute')->id() ?? ($request->user() ? $request->user()->id : null);
        $request->validate(['name' => 'required|string|max:255']);

        $role = StaffRole::create([
            'name' => $request->name,
            'institute_id' => $instituteId
        ]);

        return response()->json(['message' => 'Role created successfully', 'data' => $role], 201);
    }

    /**
     * Store a new department for the institute.
     */
    public function storeDepartment(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $department = StaffDepartment::create([
            'name' => $request->name
        ]);

        return response()->json(['message' => 'Department created successfully', 'data' => $department], 201);
    }

}
