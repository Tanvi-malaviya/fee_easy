<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct() { $this->middleware('auth:sanctum'); }

    public function index(Request $request) {
        $notes = Note::where('user_id', auth()->id())
            ->with(['checklists', 'images'])
            ->latest()
            ->paginate($request->get('per_page', 15));
            
        return response()->json([
            'status' => 'success',
            'message' => 'Notes fetched successfully.',
            'data' => $notes->items(),
            'pagination' => [
                'total' => $notes->total(),
                'count' => $notes->count(),
                'per_page' => $notes->perPage(),
                'current_page' => $notes->currentPage(),
                'last_page' => $notes->lastPage()
            ]
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'institute_id' => 'nullable|exists:institutes,id',
            'notable_id' => 'nullable',
            'notable_type' => 'nullable',
            'title' => 'required|string',
            'category' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image',
            'checklists' => 'nullable|array'
        ]);

        $data['user_id'] = auth()->id();
        
        // Handle cover image (mapping 'image' from request to 'cover_image' in DB)
        if ($request->hasFile('image')) {
            $data['cover_image'] = $request->file('image')->store('notes', 'public');
        }

        $note = Note::create($data);

        // Handle checklists if provided
        if (!empty($request->checklists)) {
            foreach ($request->checklists as $item) {
                $note->checklists()->create(['title' => $item['title']]);
            }
        }

        // Return EXACT response format as before
        return response()->json([
            'status' => 'success',
            'message' => 'Note added successfully.',
            'data' => [
                'institute_id' => $note->institute_id,
                'notable_id' => $note->notable_id,
                'notable_type' => $note->notable_type,
                'title' => $note->title,
                'category' => $note->category,
                'content' => $note->content,
                'image' => $note->cover_image,
                'updated_at' => $note->updated_at,
                'created_at' => $note->created_at,
                'id' => $note->id,
                'image_url' => $note->image_url,
                'checklists' => $note->checklists, // New rich feature
            ]
        ], 201);
    }

    public function show($id) {
        $note = Note::where('user_id', auth()->id())->with(['checklists', 'images'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $note]);
    }

    public function update(Request $request, $id) {
        $note = Note::where('user_id', auth()->id())->findOrFail($id);
        $note->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Note updated.', 'data' => $note]);
    }

    public function destroy($id) {
        Note::where('user_id', auth()->id())->findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Note deleted.']);
    }

    // Toggle Checklist Item Completion
    public function toggleChecklist($id) {
        $item = \App\Models\NoteChecklist::whereHas('note', function($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $item->update(['is_completed' => !$item->is_completed]);

        return response()->json([
            'status' => 'success', 
            'message' => $item->is_completed ? 'Marked as completed' : 'Marked as pending',
            'data' => $item
        ]);
    }

    // Remove Checklist Item
    public function destroyChecklist($id) {
        $item = \App\Models\NoteChecklist::whereHas('note', function($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $item->delete();

        return response()->json([
            'status' => 'success', 
            'message' => 'Checklist item removed successfully.'
        ]);
    }
}
