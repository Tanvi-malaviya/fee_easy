<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    /**
     * Get list of leads with filters.
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            
            $instituteId = $user->id;
            $query = Lead::where('institute_id', $instituteId);

        // Filter by Status
        if ($request->has('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        // Search by Name, Phone, or Email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $leads = $query->with('notes')->orderBy('created_at', 'desc')->paginate($request->get('per_page', 10));

            return response()->json([
                'data' => $leads->items(),
                'pagination' => [
                    'total' => $leads->total(),
                    'per_page' => $leads->perPage(),
                    'current_page' => $leads->currentPage(),
                    'last_page' => $leads->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new lead.
     */
    public function store(Request $request)
    {
        $instituteId = $request->user()->id;

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email' => 'required|email:rfc|max:255',
            'address' => 'nullable|string',
            'course_selection' => 'nullable|string',
            'reference' => 'nullable|string',
            'referer' => 'nullable|string|max:255',
            'status' => 'nullable|in:New,Contacted,Qualified,Lost,Converted',
            'title' => 'nullable|string|max:255', // Allow custom title
            'note' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lead = Lead::create([
            'institute_id' => $instituteId,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'course_selection' => $request->course_selection,
            'reference' => $request->reference,
            'referer' => $request->referer,
            'status' => $request->status ?? 'New'
        ]);

        // Add an initial note with dynamic title
        LeadNote::create([
            'lead_id' => $lead->id,
            'institute_id' => $instituteId,
            'title' => $request->title ?? ($request->note ? 'Initial Note' : 'Lead Created'),
            'note' => $request->note ?? 'New lead registered in the system.'
        ]);

        return response()->json([
            'message' => 'Lead created successfully',
            'data' => $lead->load('notes')
        ], 201);
    }

    /**
     * Show detailed lead info with interaction timeline.
     */
    public function show(Request $request, $id)
    {
        $instituteId = $request->user()->id;
        $lead = Lead::where('institute_id', $instituteId)->with('notes')->find($id);

        if (!$lead) {
            return response()->json(['message' => 'Lead not found'], 404);
        }

        return response()->json([
            'data' => $lead
        ]);
    }

    /**
     * Update lead status.
     */
    public function updateStatus(Request $request, $id)
    {
        $instituteId = $request->user()->id;
        $lead = Lead::where('institute_id', $instituteId)->find($id);

        if (!$lead) {
            return response()->json(['message' => 'Lead not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:New,Contacted,Qualified,Lost,Converted'
        ]);

        $oldStatus = $lead->status;
        $lead->status = $request->status;
        $lead->save();

        // Log the status change in notes
        LeadNote::create([
            'lead_id' => $lead->id,
            'institute_id' => $instituteId,
            'title' => 'Status Updated',
            'note' => "Status changed from {$oldStatus} to {$request->status}."
        ]);

        return response()->json([
            'message' => 'Lead status updated successfully',
            'data' => $lead->load('notes')
        ]);
    }

    /**
     * Update lead details.
     */
    public function update(Request $request, $id)
    {
        $instituteId = $request->user()->id;
        $lead = Lead::where('institute_id', $instituteId)->find($id);

        if (!$lead) {
            return response()->json(['message' => 'Lead not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email' => 'required|email:rfc|max:255',
            'address' => 'nullable|string',
            'course_selection' => 'nullable|string',
            'reference' => 'nullable|string',
            'referer' => 'nullable|string|max:255',
            'status' => 'nullable|in:New,Contacted,Qualified,Lost,Converted'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lead->update($request->all());

        return response()->json([
            'message' => 'Lead updated successfully',
            'data' => $lead->load('notes')
        ]);
    }

    /**
     * Add an interaction note.
     */
    public function addNote(Request $request, $id)
    {
        $instituteId = $request->user()->id;
        $lead = Lead::where('institute_id', $instituteId)->find($id);

        if (!$lead) {
            return response()->json(['message' => 'Lead not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'note' => 'nullable|string'
        ]);

        $note = LeadNote::create([
            'lead_id' => $lead->id,
            'institute_id' => $instituteId,
            'title' => $request->title,
            'note' => $request->note
        ]);

        return response()->json([
            'message' => 'Interaction note added successfully',
            'data' => $note
        ], 201);
    }

    /**
     * Delete a lead.
     */
    public function destroy(Request $request, $id)
    {
        $instituteId = $request->user()->id;
        $lead = Lead::where('institute_id', $instituteId)->find($id);

        if (!$lead) {
            return response()->json(['message' => 'Lead not found'], 404);
        }

        $lead->delete();

        return response()->json([
            'message' => 'Lead deleted successfully'
        ]);
    }

    /**
     * Convert a lead into an enrolled student.
     * Reuses the lead's contact details so staff never re-type them; only
     * fields a lead doesn't capture (batch, standard, DOB, guardian) are asked for.
     */
    public function convert(Request $request, $id)
    {
        $institute = $request->user();
        $instituteId = $institute->id;
        $lead = Lead::where('institute_id', $instituteId)->find($id);

        if (!$lead) {
            return response()->json(['message' => 'Lead not found'], 404);
        }

        if ($lead->converted_student_id) {
            return response()->json(['message' => 'This lead has already been converted to a student.'], 422);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email:rfc|max:255|unique:students,email',
            'phone' => 'nullable|numeric|digits:10',
            'batch_id' => 'nullable|integer|exists:batches,id,institute_id,' . $instituteId,
            'standard' => 'required|string',
            'dob' => 'required|date|before_or_equal:today',
            'guardian_name' => 'required|string|max:255',
            'monthly_fee' => 'nullable|numeric|min:0|max:999999',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $email = $request->filled('email') ? $request->email : $lead->email;
        if (Student::where('email', $email)->exists()) {
            return response()->json(['errors' => ['email' => ['This email is already used by another student.']]], 422);
        }

        $password = Str::random(10);

        $student = Student::create([
            'name' => $request->filled('name') ? $request->name : $lead->full_name,
            'email' => $email,
            'phone' => $request->filled('phone') ? $request->phone : $lead->phone,
            'password' => Hash::make($password),
            'institute_id' => $instituteId,
            'batch_id' => $request->batch_id,
            'standard' => $request->standard,
            'dob' => $request->dob,
            'guardian_name' => $request->guardian_name,
            'monthly_fee' => $request->monthly_fee,
            'status' => 1,
            'id_hash' => Str::random(32),
            'address_line_1' => $lead->address,
        ]);

        $lead->status = 'Converted';
        $lead->converted_student_id = $student->id;
        $lead->converted_at = now();
        $lead->save();

        LeadNote::create([
            'lead_id' => $lead->id,
            'institute_id' => $instituteId,
            'title' => 'Converted to Student',
            'note' => "Lead was converted into an enrolled student ({$student->name})."
        ]);

        try {
            \App\Services\InstituteMailService::send(
                $institute,
                $student->email,
                new \App\Mail\StudentAddedMail(
                    $student->name,
                    $student->email,
                    $password,
                    $institute->institute_name,
                    $institute->logo
                )
            );
        } catch (\Exception $e) {
            \Log::error("Failed to send welcome email to converted student: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'Lead converted to student successfully.',
            'data' => [
                'lead' => $lead->load('notes'),
                'student' => $student,
            ]
        ]);
    }
}
