<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Institute;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstituteResourceController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'batch_id' => 'nullable|exists:batches,id'
        ]);

        $query = Resource::where('institute_id', $request->user()->id)
            ->with('batch:id,name')
            ->orderBy('created_at', 'desc');

        if ($request->batch_id) {
            $query->where('batch_id', $request->batch_id);
        }

        $resources = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $resources
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_type' => 'required|in:document,video,image',
            'file' => 'required|file|max:1024000', // Max 1GB (adjusted from 50MB)
        ]);

        $institute = $request->user();
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('batch_resources', $filename, 'public');
            $size = round($file->getSize() / 1024 / 1024, 2) . ' MB';

            $resource = Resource::create([
                'institute_id' => $institute->id,
                'batch_id' => $request->batch_id,
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $path,
                'file_type' => $request->file_type,
                'file_size' => $size,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Resource uploaded successfully.',
                'data' => $resource
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'File upload failed.'
        ], 400);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $resource = Resource::where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$resource) {
            return response()->json(['status' => 'error', 'message' => 'Resource not found or unauthorized'], 404);
        }

        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Resource deleted successfully.'
        ]);
    }

    public function download(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $resource = Resource::where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$resource || !$resource->file_path) {
            return response()->json(['status' => 'error', 'message' => 'Resource not found'], 404);
        }

        if (!Storage::disk('public')->exists($resource->file_path)) {
            return response()->json(['status' => 'error', 'message' => 'File not found on server'], 404);
        }

        return Storage::disk('public')->download($resource->file_path, $resource->title);
    }
}
