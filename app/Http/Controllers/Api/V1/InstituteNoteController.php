<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Student;
use App\Models\Batch;

class InstituteNoteController extends Controller
{
    /**
     * Display a listing of the notes.
     */
    public function index(Request $request)
    {
        $institute = $request->user();
        $query = Note::where('institute_id', $institute->id);
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by archive status
        if ($request->has('is_archived')) {
            $query->where('is_archived', $request->is_archived);
        } else {
            $query->where('is_archived', false);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $paginator = $query->orderBy('created_at', 'desc')->paginate(15);

        $paginator->getCollection()->transform(function ($note) {
            $data = $note->toArray();
            if ($note->notable_type) {
                $data['type'] = class_basename($note->notable_type);
                $data['target'] = $note->notable;
            } else {
                $data['type'] = 'General';
                $data['target'] = null;
            }
            unset($data['notable_type']);
            unset($data['notable']);
            return $data;
        });

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
     * Store a newly created note in storage.
     */
    public function store(Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'notable_type' => 'nullable|in:student,batch',
            'notable_id' => 'nullable|integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('notes', 'public');
        }

        $modelClass = null;
        if ($request->notable_type) {
            $modelClass = $request->notable_type === 'student' ? Student::class : Batch::class;
            $table = $request->notable_type === 'student' ? 'students' : 'batches';
            
            $request->validate([
                'notable_id' => 'required|exists:' . $table . ',id,institute_id,' . $institute->id
            ]);
        }

        $note = Note::create([
            'institute_id' => $institute->id,
            'notable_id' => $request->notable_id,
            'notable_type' => $modelClass,
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Note added successfully.',
            'data' => $note
        ], 201);
    }

    /**
     * Update the specified note.
     */
    public function update(Request $request, $id)
    {
        $institute = $request->user();

        $note = Note::where('institute_id', $institute->id)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($note->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($note->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($note->image);
            }
            $data['image'] = $request->file('image')->store('notes', 'public');
        }

        $note->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Note updated successfully.',
            'data' => $note
        ]);
    }

    /**
     * Archive/Unarchive a note.
     */
    public function archive(Request $request, $id)
    {
        $institute = $request->user();

        $note = Note::where('institute_id', $institute->id)
            ->where('id', $id)
            ->firstOrFail();

        $note->update([
            'is_archived' => ! $note->is_archived
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $note->is_archived ? 'Note archived successfully.' : 'Note unarchived successfully.',
            'data' => $note
        ]);
    }

    /**
     * Remove the specified note from storage.
     */
    public function destroy(Request $request, $id)
    {
        $institute = $request->user();

        $note = Note::where('institute_id', $institute->id)
            ->where('id', $id)
            ->firstOrFail();

        $note->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Note deleted successfully.'
        ]);
    }
}
