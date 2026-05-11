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

        $query = Staff::where('institute_id', $instituteId)->with(['role', 'department']);

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
            $query->where('staff_department_id', $request->department_id);
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
            'email' => 'required|email:rfc,dns|unique:staff,email,NULL,id,institute_id,' . $instituteId,
            'staff_role_id' => 'required|exists:staff_roles,id,institute_id,' . $instituteId,
            'staff_department_id' => 'required|exists:staff_departments,id,institute_id,' . $instituteId,
            'employment_type' => 'required|in:Salary,Hourly',
            'base_salary' => 'required|numeric',
            'phone' => 'required|string|max:10',
            'status' => 'nullable|in:active,away,offline',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['institute_id'] = $instituteId;

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('staff/profiles', 'public');
            $data['profile_image'] = $path;
        }

        $staff = Staff::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff created successfully',
            'data' => $staff->load(['role', 'department'])
        ], 201);
    }

    /**
     * Display the specified staff.
     */
    public function show(Request $request, $id)
    {
        $instituteId = auth('institute')->id() ?? ($request->user() ? $request->user()->id : null);
        $staff = Staff::where('institute_id', $instituteId)->with(['role', 'department'])->find($id);

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        return response()->json($staff);
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
            'email' => 'sometimes|required|email:rfc,dns|unique:staff,email,' . $id . ',id,institute_id,' . $instituteId,
            'staff_role_id' => 'sometimes|required|exists:staff_roles,id,institute_id,' . $instituteId,
            'staff_department_id' => 'sometimes|required|exists:staff_departments,id,institute_id,' . $instituteId,
            'employment_type' => 'sometimes|required|in:Salary,Hourly',
            'base_salary' => 'sometimes|required|numeric',
            'phone' => 'sometimes|required|string|max:10',
            'status' => 'sometimes|required|in:active,away,offline',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

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
            'data' => $staff->load(['role', 'department'])
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
        $instituteId = auth('institute')->id() ?? ($request->user() ? $request->user()->id : null);
        return response()->json(StaffDepartment::where('institute_id', $instituteId)->get());
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
        $instituteId = auth('institute')->id() ?? ($request->user() ? $request->user()->id : null);
        $request->validate(['name' => 'required|string|max:255']);

        $department = StaffDepartment::create([
            'name' => $request->name,
            'institute_id' => $instituteId
        ]);

        return response()->json(['message' => 'Department created successfully', 'data' => $department], 201);
    }

}
