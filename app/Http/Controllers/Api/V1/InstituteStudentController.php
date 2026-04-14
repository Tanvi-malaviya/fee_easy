<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstituteStudentController extends Controller
{
    /**
     * Display a listing of students belonging to the authenticated institute.
     */
    public function index(Request $request)
    {
        $students = Student::where('institute_id', $request->user()->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'students' => $students
            ]
        ]);
    }

    /**
     * Store a newly created student for the institute.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'batch_id' => 'nullable|integer',
            'standard' => 'nullable|string',
            'school_name' => 'nullable|string',
        ]);

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'institute_id' => $request->user()->id, // Automatically assign the institute
            'batch_id' => $request->batch_id,
            'standard' => $request->standard,
            'school_name' => $request->school_name,
            'status' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Student created successfully',
            'data' => [
                'student' => $student
            ]
        ], 201);
    }

    /**
     * Display the specified student.
     */
    public function show(Request $request, $id)
    {
        $student = Student::where('institute_id', $request->user()->id)->find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found or unauthorized'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'student' => $student
            ]
        ]);
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, $id)
    {
        $student = Student::where('institute_id', $request->user()->id)->find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found or unauthorized'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:students,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'batch_id' => 'nullable|integer',
            'standard' => 'nullable|string',
            'school_name' => 'nullable|string',
            'status' => 'sometimes|integer',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'batch_id', 'standard', 'school_name', 'status']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $student->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Student updated successfully',
            'data' => [
                'student' => $student
            ]
        ]);
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Request $request, $id)
    {
        $student = Student::where('institute_id', $request->user()->id)->find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found or unauthorized'
            ], 404);
        }

        $student->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Student deleted successfully'
        ]);
    }
}
