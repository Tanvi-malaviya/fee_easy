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
use Illuminate\Support\Facades\DB;

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
            'pending_fees' => $institute->fees()->where('status', '!=', 'Paid')->sum(DB::raw('total_amount - paid_amount')),
            'today_attendance' => Student::where('institute_id', $institute->id)
                ->whereHas('attendance', function ($q) use ($today) {
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
        if ($student->institute_id !== $institute->id)
            abort(403);

        $referer = request()->headers->get('referer');
        if ($referer && !str_contains($referer, "/students/{$student->id}")) {
            session(['student_back_url' => $referer]);
        }

        $batches = $institute->batches()->get();
        return view('institute.students.edit', compact('student', 'batches'));
    }

    /**
     * Display the specified student profile.
     */
    public function show(Student $student)
    {
        $institute = Auth::guard('institute')->user();
        if ($student->institute_id !== $institute->id)
            abort(403);

        $referer = request()->headers->get('referer');
        if ($referer && !str_contains($referer, "/students/{$student->id}")) {
            session(['student_back_url' => $referer]);
        }

        $student->load(['batch', 'fees.payments', 'attendance', 'homeworkSubmissions']);

        // Calculate balance (Monthly Fee - Total Payments)
        $totalPaid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
        $balance = max(0, ($student->monthly_fee ?? 0) - $totalPaid);

        // Attendance stats
        $totalDays = $student->attendance()->count();
        $presentDays = $student->attendance()->where('status', 'Present')->count();
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

        // Homework stats (Average Score)
        $averageGrade = $student->homeworkSubmissions()->whereNotNull('score')->avg('score');
        $averageGrade = $averageGrade ? round($averageGrade, 1) : 0;

        return view('institute.students.show', compact('student', 'balance', 'attendancePercentage', 'averageGrade'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $institute = Auth::guard('institute')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:students,email',
            'phone' => 'required|numeric|digits:10',
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $institute->id,
            'standard' => 'required|string',
            'dob' => 'required|date|before_or_equal:today',
            'guardian_name' => 'required|string|max:255',
            'monthly_fee' => 'nullable|numeric|min:0|max:999999',
            'profile_image' => 'nullable|image|max:2048',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|digits:6',
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
            Mail::to($student->email)->send(new \App\Mail\StudentAddedMail(
                $student->name,
                $student->email,
                $password,
                $institute->institute_name,
                $institute->logo
            ));
        } catch (\Exception $e) {
            // Log error or handle gracefully if mail fails
            \Log::error("Failed to send welcome email to student: " . $e->getMessage());
        }

        // Notify student & parent if the student was created already assigned to a batch
        if (!empty($student->batch_id)) {
            $this->notifyBatchChange($student->fresh(), null, $student->batch_id);
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
        if ($student->institute_id !== $institute->id)
            abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:students,email,' . $student->id,
            'phone' => 'required|numeric|digits:10',
            'password' => 'nullable|string|min:6',
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $institute->id,
            'standard' => 'required|string',
            'dob' => 'required|date|before_or_equal:today',
            'guardian_name' => 'required|string|max:255',
            'monthly_fee' => 'nullable|numeric|min:0|max:999999',
            'status' => 'nullable|integer',
            'profile_image' => 'nullable|image|max:2048',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|digits:6',
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

        $oldBatchId = $student->batch_id;
        $student->update($data);

        // Notify student & parent if the batch assignment changed
        if (array_key_exists('batch_id', $data) && $oldBatchId != $data['batch_id']) {
            $this->notifyBatchChange($student->fresh(), $oldBatchId, $data['batch_id']);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Student details updated successfully!', 'student' => $student]);
        }

        if (session()->has('student_back_url')) {
            $backUrl = session('student_back_url');
            session()->forget('student_back_url');
            return redirect($backUrl)->with('success', 'Student details updated successfully.');
        }

        return redirect()->route('institute.students.index')->with('success', 'Student details updated successfully.');
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Request $request, Student $student)
    {
        $institute = Auth::guard('institute')->user();
        if ($student->institute_id !== $institute->id)
            abort(403);

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
            $query->where(function ($q) use ($search) {
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

            $callback = function () use ($students) {
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

    /**
     * Send a fee reminder notification to the student and parent.
     */
    public function sendFeeReminder(Student $student, \App\Services\FCMService $fcm)
    {
        $institute = Auth::guard('institute')->user();
        if ($student->institute_id !== $institute->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        // Calculate balance
        $totalPaid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
        $balance = max(0, ($student->monthly_fee ?? 0) - $totalPaid);

        if ($balance <= 0) {
            return response()->json(['status' => 'error', 'message' => 'This student has no pending fee balance.'], 400);
        }

        $title = 'Fee Payment Reminder';
        $message = "Dear {$student->name}, this is a friendly reminder that you have a pending fee balance of ₹" . number_format($balance) . ". Please clear it at your earliest convenience.";

        // 1. Save Notification to DB for Student
        \App\Models\Notification::create([
            'user_type' => 'student',
            'user_id' => $student->id,
            'title' => $title,
            'message' => $message,
            'type' => 'fee_reminder',
            'is_read' => false,
        ]);

        // 2. Save Notification to DB for Parent
        if ($student->parent_id) {
            \App\Models\Notification::create([
                'user_type' => 'parent',
                'user_id' => $student->parent_id,
                'title' => $title,
                'message' => $message,
                'type' => 'fee_reminder',
                'is_read' => false,
            ]);
        }

        // 3. Send Firebase Push Notification to student
        if ($student->fcm_token) {
            $fcm->send($student->fcm_token, $title, $message, [
                'type' => 'fee_reminder',
            ]);
        }

        // 4. Send Firebase Push Notification to parent
        if ($student->parent && $student->parent->fcm_token) {
            $fcm->send($student->parent->fcm_token, $title, $message, [
                'type' => 'fee_reminder',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Fee reminder sent successfully!'
        ]);
    }

    /**
     * Create DB + push notifications for the student (and their parent) when a
     * batch is assigned, changed, or removed. Mirrors the API behaviour so the
     * web institute panel keeps students informed too.
     */
    private function notifyBatchChange(Student $student, $oldBatchId, $newBatchId): void
    {
        $fcm = app(\App\Services\FCMService::class);
        $student->loadMissing('parent');

        // Assigned to / moved to a batch
        if (!empty($newBatchId)) {
            $batch = \App\Models\Batch::find($newBatchId);
            if (!$batch) {
                return;
            }

            $title = 'Batch Updated';
            $studentBody = $oldBatchId
                ? "You've been moved to {$batch->name}."
                : "You have been assigned to the batch: {$batch->name}";
            $pushData = ['type' => 'batch_assignment', 'batch_id' => (string) $batch->id];

            \App\Models\Notification::create([
                'user_type'    => 'student',
                'user_id'      => $student->id,
                'title'        => $title,
                'message'      => $studentBody,
                'type'         => 'batch_assignment',
                'reference_id' => $batch->id,
                'is_read'      => false,
            ]);
            if (!empty($student->fcm_token)) {
                $fcm->send($student->fcm_token, $title, $studentBody, $pushData);
            }

            if ($student->parent) {
                $parentBody = "{$student->name} has been assigned to the batch: {$batch->name}";
                \App\Models\Notification::create([
                    'user_type'    => 'parent',
                    'user_id'      => $student->parent->id,
                    'title'        => "Batch Assigned: {$student->name}",
                    'message'      => $parentBody,
                    'type'         => 'batch_assignment',
                    'reference_id' => $batch->id,
                    'is_read'      => false,
                ]);
                if (!empty($student->parent->fcm_token)) {
                    $fcm->send($student->parent->fcm_token, "Batch Assigned: {$student->name}", $parentBody, $pushData);
                }
            }

            return;
        }

        // Removed from a batch
        $oldBatch = \App\Models\Batch::find($oldBatchId);
        if (!$oldBatch) {
            return;
        }

        $title = 'Batch Updated';
        $studentBody = "You've been removed from {$oldBatch->name}.";
        $pushData = ['type' => 'batch_removal', 'batch_id' => (string) $oldBatch->id];

        \App\Models\Notification::create([
            'user_type'    => 'student',
            'user_id'      => $student->id,
            'title'        => $title,
            'message'      => $studentBody,
            'type'         => 'batch_removal',
            'reference_id' => $oldBatch->id,
            'is_read'      => false,
        ]);
        if (!empty($student->fcm_token)) {
            $fcm->send($student->fcm_token, $title, $studentBody, $pushData);
        }

        if ($student->parent) {
            $parentBody = "{$student->name} has been removed from {$oldBatch->name}.";
            \App\Models\Notification::create([
                'user_type'    => 'parent',
                'user_id'      => $student->parent->id,
                'title'        => $title,
                'message'      => $parentBody,
                'type'         => 'batch_removal',
                'reference_id' => $oldBatch->id,
                'is_read'      => false,
            ]);
            if (!empty($student->parent->fcm_token)) {
                $fcm->send($student->parent->fcm_token, $title, $parentBody, $pushData);
            }
        }
    }

    /**
     * Generate a new random password and send it to the student via email.
     */
    public function sendPasswordEmail(Student $student)
    {
        $institute = Auth::guard('institute')->user();
        if ($student->institute_id !== $institute->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '@#$%&*';
        
        $password = $uppercase[rand(0, strlen($uppercase)-1)] . 
                    $lowercase[rand(0, strlen($lowercase)-1)] . 
                    $numbers[rand(0, strlen($numbers)-1)] . 
                    $special[rand(0, strlen($special)-1)] . 
                    Str::random(4); // Total 8 characters
                    
        $student->update([
            'password' => Hash::make($password),
        ]);

        try {
            Mail::to($student->email)->send(new \App\Mail\StudentPasswordSentMail(
                $student->name,
                $student->email,
                $password,
                $institute->institute_name,
                $institute->logo
            ));
        } catch (\Exception $e) {
            \Log::error("Failed to send student password email: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Password was updated in database, but failed to send email. Please check mail settings.'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Password has been generated and sent to student email successfully!'
        ]);
    }

    /**
     * Directly reset/change the student password from the admin panel.
     */
    public function resetPasswordDirect(Request $request, Student $student)
    {
        $institute = Auth::guard('institute')->user();
        if ($student->institute_id !== $institute->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'max:15',
                'regex:/[a-z]/',      // at least one lowercase
                'regex:/[A-Z]/',      // at least one uppercase
                'regex:/[0-9]/',      // at least one number
                'regex:/[\W_]/',      // at least one special character
            ],
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 15 characters.',
            'password.regex' => 'Password must include an uppercase letter, a lowercase letter, a number, and a special character.',
        ]);

        $student->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Student password has been reset successfully!'
        ]);
    }
}
