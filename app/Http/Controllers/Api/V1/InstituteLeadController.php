<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;

class InstituteLeadController extends Controller
{
    /**
     * Get all leads for the institute (Paginated).
     */
    public function index(Request $request)
    {
        $institute = $request->user();
        
        $query = $institute->leads()->with('assignedTeacher:id,name');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $paginator = $query->orderBy('created_at', 'desc')->paginate(15);

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
     * Add a new lead.
     */
    public function store(Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'course_interest' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:New,Contacted,Converted,Lost',
            'assigned_to' => 'nullable|exists:teachers,id,institute_id,' . $institute->id,
        ]);

        $lead = $institute->leads()->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Lead captured successfully.',
            'data' => $lead->load('assignedTeacher:id,name')
        ], 201);
    }

    /**
     * Update an existing lead.
     */
    public function update(Request $request, $id)
    {
        $institute = $request->user();
        $lead = $institute->leads()->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'course_interest' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:New,Contacted,Converted,Lost',
            'assigned_to' => 'nullable|exists:teachers,id,institute_id,' . $institute->id,
        ]);

        $lead->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Lead updated successfully.',
            'data' => $lead->load('assignedTeacher:id,name')
        ]);
    }

    /**
     * Delete a lead.
     */
    public function destroy(Request $request, $id)
    {
        $institute = $request->user();
        $lead = $institute->leads()->findOrFail($id);
        
        $lead->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lead deleted successfully.'
        ]);
    }
}
