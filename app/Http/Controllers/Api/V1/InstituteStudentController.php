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
            ->with('batch')
            ->withAvg('homeworkSubmissions', 'score');

        if ($request->boolean('has_fees')) {
            $query->has('fees');
        }

        // Search Filter (Name, Email, Phone)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Batch Filter
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        // Filter out students already in this batch
        if ($request->filled('not_in_batch_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('batch_id', '!=', $request->not_in_batch_id)
                    ->orWhereNull('batch_id');
            });
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

            // Append totals for reports
            foreach ($students as $student) {
                $student->total_paid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
                $student->total_due = ($student->monthly_fee ?? 0) - $student->total_paid;
            }

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

        $paginator = $query->paginate(10);

        $items = collect($paginator->items())->map(function ($student) {
            $student->total_paid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
            $student->total_due = ($student->monthly_fee ?? 0) - $student->total_paid;
            return $student;
        });

        // Calculate Stats
        $graduatingCount = Student::where('institute_id', $request->user()->id)
            ->where(function($q) {
                $q->where('standard', 'like', '%12%')
                  ->orWhere('standard', 'like', '%Final%')
                  ->orWhere('standard', 'like', '%Graduate%');
            })
            ->count();

        $performanceAvg = \App\Models\HomeworkSubmission::whereHas('student', function($q) use ($request) {
            $q->where('institute_id', $request->user()->id);
        })->avg('score') ?? 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $items,
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'stats' => [
                    'graduating' => $graduatingCount,
                    'performance' => round($performanceAvg, 1) . '%'
                ]
            ]
        ]);
    }

    /**
     * Store a newly created student for the institute.
     */
    public function store(Request $request)
    {
        // Fix for trailing spaces/tabs in Postman keys
        $cleanData = [];
        foreach ($request->all() as $key => $value) {
            $cleanData[trim($key)] = $value;
        }
        $request->merge($cleanData);

        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $request->user()->id,
            'standard' => 'nullable|string',
            'dob' => 'nullable|date',
            'guardian_name' => 'nullable|string|max:255',
            'monthly_fee' => 'nullable|numeric|min:0',
            'profile_image_url' => 'nullable|image|max:2048',
        ]);

        $profileImagePath = null;
        $file = $request->file('profile_image_url');

        // Robust check for any uploaded file if 'profile_image_url' key is missing
        if (!$file && count($request->allFiles()) > 0) {
            $file = array_values($request->allFiles())[0];
        }

        if ($file) {
            $profileImagePath = $file->store('students', 'public');
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'institute_id' => $request->user()->id,
            'batch_id' => $request->batch_id,
            'standard' => $request->standard,
            'dob' => $request->dob,
            'guardian_name' => $request->guardian_name,
            'monthly_fee' => $request->monthly_fee,
            'profile_image' => $profileImagePath,
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
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $request->user()->id,
            'standard' => 'nullable|string',
            'dob' => 'nullable|date',
            'guardian_name' => 'nullable|string|max:255',
            'monthly_fee' => 'nullable|numeric|min:0',
            'status' => 'sometimes|integer',
            'profile_image_url' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'batch_id', 'standard', 'status', 'dob', 'guardian_name', 'monthly_fee']);

        if ($request->hasFile('profile_image_url')) {
            // Delete old image if exists
            if ($student->profile_image && \Storage::disk('public')->exists($student->profile_image)) {
                \Storage::disk('public')->delete($student->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image_url')->store('students', 'public');
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
