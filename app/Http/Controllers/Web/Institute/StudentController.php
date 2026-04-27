<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        
        // If it's an AJAX request, return the JSON data
        if ($request->expectsJson()) {
            $students = $institute->students()->with('batch')->orderBy('created_at', 'desc')->paginate(10);
            return response()->json($students);
        }

        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Fetch batches for the dropdowns
        $batches = $institute->batches()->get();

        // Fetch stats needed for the registry header
        $stats = [
            'total_students' => $institute->students()->count(),
            'monthly_revenue' => Payment::whereIn('student_id', $institute->students()->pluck('id'))
                ->where('created_at', '>=', $startOfMonth)
                ->sum('amount'),
            'pending_fees' => $institute->fees()->where('status', '!=', 'Paid')->sum('due_amount'),
            'today_attendance' => Student::where('institute_id', $institute->id)
                ->whereHas('attendance', function($q) use ($today) {
                    $q->where('date', $today)->where('status', 'Present');
                })->count(),
        ];

        $stats['monthly_revenue_formatted'] = number_format($stats['monthly_revenue']);

        return view('institute.students.index', compact('batches', 'institute', 'stats'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $institute = Auth::guard('institute')->user();
        $batches = $institute->batches()->get();
        return view('institute.students.create', compact('batches'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $institute = Auth::guard('institute')->user();
        if ($student->institute_id !== $institute->id) abort(403);
        
        $batches = $institute->batches()->get();
        return view('institute.students.edit', compact('student', 'batches'));
    }

    /**
     * Display the specified student profile.
     */
    public function show(Student $student)
    {
        $institute = Auth::guard('institute')->user();
        if ($student->institute_id !== $institute->id) abort(403);

        $student->load(['batch', 'fees']);
        
        // Calculate balance (Difference between total assigned fees and total paid amount)
        $totalAssigned = $student->fees->sum('total_amount');
        $totalPaid = $student->fees->sum('paid_amount');
        
        // If no fee records exist, show monthly_fee as the pending balance for the current month
        if ($student->fees->count() === 0) {
            $balance = $student->monthly_fee;
        } else {
            $balance = $totalAssigned - $totalPaid;
        }

        return view('institute.students.show', compact('student', 'balance'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $institute = Auth::guard('institute')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:10',
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $institute->id,
            'standard' => 'required|string',
            'dob' => 'required|date',
            'guardian_name' => 'required|string|max:255',
            'monthly_fee' => 'nullable|numeric|min:0',
            'profile_image' => 'nullable|image|max:2048',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
        ]);

        // Generate a random password since the field is removed from UI
        $password = Str::random(10);

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('students', 'public');
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($password),
            'institute_id' => $institute->id,
            'batch_id' => $request->batch_id,
            'standard' => $request->standard,
            'dob' => $request->dob,
            'guardian_name' => $request->guardian_name,
            'monthly_fee' => $request->monthly_fee,
            'profile_image' => $profileImagePath,
            'status' => 1,
            'id_hash' => Str::random(32),
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pincode' => $request->pincode,
        ]);

        // Send password to student via email
        try {
            Mail::raw("Welcome to FeeEasy! Your account has been created. Your login password is: " . $password . ". Please use your email to login.", function ($message) use ($student) {
                $message->to($student->email)
                    ->subject('Your Account Password - FeeEasy');
            });
        } catch (\Exception $e) {
            // Log error or handle gracefully if mail fails
            \Log::error("Failed to send welcome email to student: " . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Student added successfully!', 'student' => $student]);
        }

        return redirect()->route('institute.students.index')->with('success', 'Student added successfully to the registry.');
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $institute = Auth::guard('institute')->user();
        if ($student->institute_id !== $institute->id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required|string|max:10',
            'password' => 'nullable|string|min:6',
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $institute->id,
            'standard' => 'required|string',
            'dob' => 'required|date',
            'guardian_name' => 'required|string|max:255',
            'monthly_fee' => 'nullable|numeric|min:0',
            'status' => 'nullable|integer',
            'profile_image' => 'nullable|image|max:2048',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'batch_id', 'standard', 'dob', 'guardian_name', 'monthly_fee', 'status', 'address_line_1', 'address_line_2', 'city', 'state', 'country', 'pincode']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($student->profile_image && \Storage::disk('public')->exists($student->profile_image)) {
                \Storage::disk('public')->delete($student->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('students', 'public');
        }

        $student->update($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Student details updated successfully!', 'student' => $student]);
        }

        return redirect()->route('institute.students.index')->with('success', 'Student details updated successfully.');
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Request $request, Student $student)
    {
        $institute = Auth::guard('institute')->user();
        if ($student->institute_id !== $institute->id) abort(403);

        $student->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Student has been removed from the registry.']);
        }

        return redirect()->route('institute.students.index')->with('success', 'Student has been removed from the registry.');
    }

    /**
     * Export students based on format and filters.
     */
    public function export(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        $query = $institute->students()->with('batch');

        // Apply Filters (same logic as API)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('name', 'asc')->get();
        $format = $request->get('format', 'pdf');

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('institute.export.students', [
                'students' => $students,
                'institute' => $institute,
                'date' => Carbon::now()->format('d M, Y'),
                'batch' => $request->filled('batch_id') ? \App\Models\Batch::find($request->batch_id) : null
            ]);
            
            return $pdf->download('Student_Registry_' . Carbon::now()->format('YmdHis') . '.pdf');
        } else {
            // CSV / Excel Format
            $filename = 'Student_Export_' . Carbon::now()->format('YmdHis') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($students) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Name', 'Email', 'Phone', 'Batch', 'Standard', 'Status']);

                foreach ($students as $student) {
                    fputcsv($file, [
                        $student->name,
                        $student->email,
                        $student->phone,
                        $student->batch ? $student->batch->name : 'N/A',
                        $student->standard,
                        $student->status == 1 ? 'Active' : 'Inactive'
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }
}
