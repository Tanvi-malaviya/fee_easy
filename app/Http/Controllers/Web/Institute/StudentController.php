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
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $institute = Auth::guard('institute')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $institute->id,
            'standard' => 'nullable|string',
            'dob' => 'nullable|date',
        ]);

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'institute_id' => $institute->id,
            'batch_id' => $request->batch_id,
            'standard' => $request->standard,
            'dob' => $request->dob,
            'status' => 1,
            'id_hash' => Str::random(32),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Student added successfully!', 'student' => $student]);
        }

        return redirect()->back()->with('success', 'Student added successfully to the registry.');
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
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $institute->id,
            'standard' => 'nullable|string',
            'dob' => 'nullable|date',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'batch_id', 'standard', 'dob']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $student->update($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Student details updated successfully!', 'student' => $student]);
        }

        return redirect()->back()->with('success', 'Student details updated successfully.');
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Request $request, Student $student)
    {
        $institute = Auth::guard('institute')->user();
        if ($student->institute_id !== $institute->id) abort(403);

        $student->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Student has been removed from the registry.']);
        }

        return redirect()->back()->with('success', 'Student has been removed from the registry.');
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
