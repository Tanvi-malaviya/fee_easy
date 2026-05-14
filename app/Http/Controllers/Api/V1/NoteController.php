<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\NoteCategory;
use Illuminate\Http\Request;
use Exception;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $userId = auth()->id() ?? auth('institute')->id();
        $query = Note::where('user_id', $userId)->with('category_relation');

        // Filter by Category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Bookmark
        if ($request->has('is_bookmarked')) {
            $query->where('is_bookmarked', $request->boolean('is_bookmarked'));
        }

        // Filter by Archived
        if ($request->has('is_archived')) {
            $query->where('is_archived', $request->boolean('is_archived'));
        }

        $notes = $query->latest()->paginate($request->get('per_page', 15));

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'institute_id' => 'nullable|exists:institutes,id',
            'notable_id' => 'nullable',
            'notable_type' => 'nullable',
            'title' => 'required|string',
            'category_id' => 'nullable|exists:note_categories,id',
            'category' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image',
            'checklists' => 'nullable|array'
        ]);

        $data['user_id'] = auth()->id() ?? auth('institute')->id();

        // Handle Category Fix: If category name is provided but no category_id, find or create it
        if (!empty($data['category']) && empty($data['category_id'])) {
            try {
                $category = NoteCategory::firstOrCreate(
                    ['name' => $data['category']],
                    ['color' => '#6366f1']
                );
                $data['category_id'] = $category->id;
            } catch (Exception $e) {
                \Log::error('Note category error: ' . $e->getMessage());
            }
        }

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

        // Return cleaned response
        return response()->json([
            'status' => 'success',
            'message' => 'Note added successfully.',
            'data' => $note
        ], 201);
    }

    public function show($id)
    {
        $userId = auth()->id() ?? auth('institute')->id();
        $note = Note::where('user_id', $userId)->with(['checklists', 'images', 'category_relation'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $note]);
    }

    public function update(Request $request, $id)
    {
        $userId = auth()->id() ?? auth('institute')->id();
        $note = Note::where('user_id', $userId)->findOrFail($id);

        $data = $request->validate([
            'remove_image' => 'nullable',
            'institute_id' => 'nullable|exists:institutes,id',
            'notable_id' => 'nullable',
            'notable_type' => 'nullable',
            'title' => 'nullable|string',
            'category_id' => 'nullable|exists:note_categories,id',
            'category' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image',
            'is_bookmarked' => 'nullable|boolean',
            'is_archived' => 'nullable|boolean'
        ]);

        // Handle Category Fix: If category name is provided but no category_id, find or create it
        if (!empty($data['category']) && empty($data['category_id'])) {
            try {
                $category = NoteCategory::firstOrCreate(
                    ['name' => $data['category']],
                    ['color' => '#6366f1']
                );
                $data['category_id'] = $category->id;
            } catch (Exception $e) {
                \Log::error('Note category error: ' . $e->getMessage());
            }
        }

        // Handle image removal
        if ($request->remove_image == '1') {
            if ($note->cover_image) \Storage::disk('public')->delete($note->cover_image);
            $data['cover_image'] = null;
        }

        if ($request->hasFile('image')) {
            $data['cover_image'] = $request->file('image')->store('notes', 'public');
        }

        $note->update($data);

        // Optional: Update checklists if provided
        if ($request->has('checklists') && is_array($request->checklists)) {
            // Simple approach: delete and recreate or just add new ones
            // For now, let's just allow adding new ones or updating existing ones via separate API if needed
            // But here we can implement a basic sync
            $note->checklists()->delete();
            foreach ($request->checklists as $item) {
                $note->checklists()->create([
                    'title' => $item['title'],
                    'is_completed' => $item['is_completed'] ?? false
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Note updated.',
            'data' => $note
        ]);
    }

    public function bookmark($id)
    {
        $userId = auth()->id() ?? auth('institute')->id();
        $note = Note::where('user_id', $userId)->findOrFail($id);
        $note->update(['is_bookmarked' => !$note->is_bookmarked]);

        return response()->json([
            'status' => 'success',
            'message' => $note->is_bookmarked ? 'Note bookmarked.' : 'Note unbookmarked.',
            'data' => [
                'is_bookmarked' => $note->is_bookmarked
            ]
        ]);
    }

    public function destroy($id)
    {
        $userId = auth()->id() ?? auth('institute')->id();
        Note::where('user_id', $userId)->findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Note deleted.']);
    }

    // Toggle Checklist Item Completion
    public function toggleChecklist($id)
    {
        $userId = auth()->id() ?? auth('institute')->id();
        $item = \App\Models\NoteChecklist::whereHas('note', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->findOrFail($id);

        $item->update(['is_completed' => !$item->is_completed]);

        return response()->json([
            'status' => 'success',
            'message' => $item->is_completed ? 'Marked as completed' : 'Marked as pending',
            'data' => $item
        ]);
    }

    // Remove Checklist Item
    public function destroyChecklist($id)
    {
        $userId = auth()->id() ?? auth('institute')->id();
        $item = \App\Models\NoteChecklist::whereHas('note', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->findOrFail($id);

        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Checklist item removed successfully.'
        ]);
    }
}
