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
        $instituteId = $request->user()->id;
        $query = Staff::where('institute_id', $instituteId)->with(['role', 'department']);

        // Search by name or employee ID
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                  ->orWhere('employee_id', 'like', "%$search%");
            });
        }

        // Filter by role
        if ($request->has('role_id')) {
            $query->where('staff_role_id', $request->role_id);
        }

        // Filter by department
        if ($request->has('department_id')) {
            $query->where('staff_department_id', $request->department_id);
        }

        $staff = $query->paginate($request->get('per_page', 8));

        return response()->json([
            'status' => 'success',
            'data' => $staff->items(),
            'pagination' => [
                'total' => $staff->total(),
                'per_page' => $staff->perPage(),
                'current_page' => $staff->currentPage(),
                'last_page' => $staff->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created staff in storage.
     */
    public function store(Request $request)
    {
        $instituteId = $request->user()->id;

        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,NULL,id,institute_id,' . $instituteId,
            'staff_role_id' => 'required|exists:staff_roles,id,institute_id,' . $instituteId,
            'staff_department_id' => 'required|exists:staff_departments,id,institute_id,' . $instituteId,
            'employment_type' => 'required|in:Salary,Hourly',
            'base_salary' => 'required|numeric',
            'phone' => 'nullable|string|max:10',
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
            'message' => 'Staff created successfully',
            'data' => $staff->load(['role', 'department'])
        ], 201);
    }

    /**
     * Display the specified staff.
     */
    public function show(Request $request, $id)
    {
        $instituteId = $request->user()->id;
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
        $instituteId = $request->user()->id;
        $staff = Staff::where('institute_id', $instituteId)->find($id);

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable',
            'full_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:staff,email,' . $id . ',id,institute_id,' . $instituteId,
            'staff_role_id' => 'sometimes|required|exists:staff_roles,id,institute_id,' . $instituteId,
            'staff_department_id' => 'sometimes|required|exists:staff_departments,id,institute_id,' . $instituteId,
            'employment_type' => 'sometimes|required|in:Salary,Hourly',
            'base_salary' => 'sometimes|required|numeric',
            'phone' => 'sometimes|nullable|string|max:15',
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
            'message' => 'Staff updated successfully',
            'data' => $staff->load(['role', 'department'])
        ]);
    }

    /**
     * Remove the specified staff from storage.
     */
    public function destroy(Request $request, $id)
    {
        $instituteId = $request->user()->id;
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
        $instituteId = $request->user()->id;
        return response()->json(StaffRole::where('institute_id', $instituteId)->get());
    }

    /**
     * Get all departments for dropdowns.
     */
    public function getDepartments(Request $request)
    {
        $instituteId = $request->user()->id;
        return response()->json(StaffDepartment::where('institute_id', $instituteId)->get());
    }

    /**
     * Store a new role for the institute.
     */
    public function storeRole(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        $role = StaffRole::create([
            'name' => $request->name,
            'institute_id' => $request->user()->id
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
            'name' => $request->name,
            'institute_id' => $request->user()->id
        ]);

        return response()->json(['message' => 'Department created successfully', 'data' => $department], 201);
    }

    /**
     * Get simple staff list (ID and Name) for dropdowns.
     */
    public function getStaffSimpleList(Request $request)
    {
        $instituteId = $request->user()->id;
        $staff = Staff::where('institute_id', $instituteId)
            ->select('id', 'full_name', 'employee_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $staff
        ]);
    }
}
