<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\StaffDepartment;
use App\Models\Activity;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the departments.
     */
    public function index(Request $request)
    {
        $query = StaffDepartment::with(['staff']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $departments = $query->latest()->paginate(10)->withQueryString();

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department = StaffDepartment::create($validated);

        Activity::log("Created new global department '{$department->name}'");

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit($id)
    {
        $department = StaffDepartment::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, $id)
    {
        $department = StaffDepartment::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department->update($validated);

        Activity::log("Updated global department '{$department->name}'");

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy($id)
    {
        $department = StaffDepartment::findOrFail($id);
        $name = $department->name;

        $department->delete();

        Activity::log("Deleted global department '{$name}'");

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
