<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Get list of leads with filters.
     */
    public function index(Request $request)
    {
        $instituteId = $request->user()->id;
        $query = Lead::where('institute_id', $instituteId)->with('notes');

        // Filter by Status
        if ($request->has('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        // Search by Name, Phone, or Email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 10));

        return response()->json([
            'data' => $leads->items(),
            'pagination' => [
                'total' => $leads->total(),
                'per_page' => $leads->perPage(),
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
            ]
        ]);
    }

    /**
     * Store a new lead.
     */
    public function store(Request $request)
    {
        $instituteId = $request->user()->id;

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'course_selection' => 'nullable|string',
            'reference' => 'nullable|string',
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
}
