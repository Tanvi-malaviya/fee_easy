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
        
        $query = Note::where('institute_id', $institute->id)->with('notable');

        // Optional filtering by notable_type and notable_id
        if ($request->has('notable_type') && $request->has('notable_id')) {
            $type = $request->notable_type; // 'student' or 'batch'
            
            if ($type === 'student') {
                $query->where('notable_type', Student::class);
            } elseif ($type === 'batch') {
                $query->where('notable_type', Batch::class);
            }
            
            $query->where('notable_id', $request->notable_id);
        }

        $paginator = $query->orderBy('created_at', 'desc')->paginate(15);

        $paginator->getCollection()->transform(function ($note) {
            $data = $note->toArray();
            $data['type'] = class_basename($note->notable_type);
            $data['target'] = $note->notable;
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
            'notable_type' => 'required|in:student,batch',
            'notable_id' => 'required|integer',
            'content' => 'required|string',
        ]);

        $modelClass = $request->notable_type === 'student' ? Student::class : Batch::class;
        $table = $request->notable_type === 'student' ? 'students' : 'batches';

        // Validate that the target exists and belongs to this institute
        $request->validate([
            'notable_id' => 'exists:' . $table . ',id,institute_id,' . $institute->id
        ], [
            'notable_id.exists' => 'The selected ' . $request->notable_type . ' is invalid or does not belong to your institute.'
        ]);

        $note = Note::create([
            'institute_id' => $institute->id,
            'notable_id' => $request->notable_id,
            'notable_type' => $modelClass,
            'content' => $request->content,
        ]);

        $note->load('notable');

        $data = $note->toArray();
        $data['type'] = class_basename($note->notable_type);
        $data['target'] = $note->notable;
        unset($data['notable_type']);
        unset($data['notable']);

        return response()->json([
            'status' => 'success',
            'message' => 'Note added successfully.',
            'data' => $data
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
            'content' => 'required|string',
        ]);

        $note->update([
            'content' => $request->content,
        ]);

        $note->load('notable');
        $data = $note->toArray();
        $data['type'] = class_basename($note->notable_type);
        $data['target'] = $note->notable;
        unset($data['notable_type']);
        unset($data['notable']);

        return response()->json([
            'status' => 'success',
            'message' => 'Note updated successfully.',
            'data' => $data
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
