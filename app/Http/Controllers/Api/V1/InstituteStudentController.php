<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InstituteStudentController extends Controller
{
    /**
     * Display a listing of students belonging to the authenticated institute.
     */
    public function index(Request $request)
    {
        \Log::debug('API Request User:', ['user' => $request->user(), 'guards' => config('sanctum.guard')]);
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = Student::where('institute_id', $request->user()->id)
            ->with('batch');

        // Search Filter (Name, Email, Phone)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Batch Filter
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        // Status Filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Standard Filter
        if ($request->filled('standard')) {
            $query->where('standard', 'like', "%{$request->standard}%");
        }

        $query->orderBy('created_at', 'desc');

        // If batch_id/filters are provided but no page, we might want all for some views,
        // but for the Student Registry we usually want pagination.
        // We'll keep the existing logic for attendance compatibility but prioritize pagination.
        
        if ($request->has('batch_id') && !$request->has('page') && !$request->has('search')) {
            $students = $query->get();
            return response()->json([
                'status' => 'success',
                'data' => [
                    'items' => $students,
                    'total' => $students->count(),
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => $students->count(),
                ]
            ]);
        }

        $paginator = $query->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $paginator->items(),
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
            ]
        ]);
    }

    /**
     * Store a newly created student for the institute.
     */
    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $request->user()->id,
            'standard' => 'nullable|string',
            'dob' => 'nullable|date',
        ]);

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'institute_id' => $request->user()->id,
            'batch_id' => $request->batch_id,
            'standard' => $request->standard,
            'dob' => $request->dob,
            'status' => 1,
            'id_hash' => Str::random(32), // Unique secure hash for ID card
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Student created successfully',
            'data' => $student
        ], 201);
    }

    /**
     * Display the specified student.
     */
    public function show(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = Student::where('institute_id', $request->user()->id)->with('batch')->find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found or unauthorized'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $student
        ]);
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

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
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $request->user()->id,
            'standard' => 'nullable|string',
            'dob' => 'nullable|date',
            'status' => 'sometimes|integer',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'batch_id', 'standard', 'status', 'dob']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $student->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Student updated successfully',
            'data' => $student
        ]);
    }

    /**
     * Get upcoming birthdays.
     */
    public function birthdays(Request $request)
    {
        $institute = $request->user();
        $today = Carbon::today();
        
        // Find students whose birthday (month and day) is after today or in the current month
        $students = Student::where('institute_id', $institute->id)
            ->whereNotNull('dob')
            ->get()
            ->filter(function ($student) use ($today) {
                $dob = Carbon::parse($student->dob);
                $birthdayThisYear = $dob->copy()->year($today->year);
                
                // If birthday already passed this year, look at next year
                if ($birthdayThisYear->isPast() && !$birthdayThisYear->isToday()) {
                    $birthdayThisYear->addYear();
                }

                // Show birthdays in next 30 days
                return $birthdayThisYear->diffInDays($today) <= 30;
            })
            ->sortBy(function ($student) use ($today) {
                $dob = Carbon::parse($student->dob);
                $birthdayThisYear = $dob->copy()->year($today->year);
                if ($birthdayThisYear->isPast() && !$birthdayThisYear->isToday()) {
                    $birthdayThisYear->addYear();
                }
                return $birthdayThisYear->timestamp;
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'data' => $students
        ]);
    }

    /**
     * Get Digital ID Card Data.
     */
    public function idCard(Request $request, $id)
    {
        $institute = $request->user();
        $student = Student::where('institute_id', $institute->id)
            ->with(['batch', 'institute:id,institute_name,logo,address,city,phone'])
            ->findOrFail($id);

        // Generate a data string for the QR code
        $qrPayload = json_encode([
            'type' => 'student_id_verification',
            'hash' => $student->id_hash,
            'name' => $student->name,
            'institute' => $student->institute->institute_name
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'phone' => $student->phone,
                    'standard' => $student->standard,
                    'dob' => $student->dob,
                    'batch' => $student->batch ? $student->batch->name : 'N/A',
                ],
                'institute' => $student->institute,
                'qr_payload' => $qrPayload,
                'verification_hash' => $student->id_hash
            ]
        ]);
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

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
