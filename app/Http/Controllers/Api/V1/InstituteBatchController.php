<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Institute;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class InstituteBatchController extends Controller
{
    /**
     * Display a listing of batches belonging to the authenticated institute.
     */
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = Batch::where('institute_id', $request->user()->id)
            ->withCount('students')
            ->with('students:id,name,batch_id');

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('subject', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        $perPage = 10;
        $batches = $query->paginate($perPage);

        // Calculate total paid and expected for each batch
        foreach ($batches as $batch) {
            $studentIds = \App\Models\Student::where('batch_id', $batch->id)->pluck('id');
            $batch->total_paid = (float) \App\Models\Fee::whereIn('student_id', $studentIds)->sum('paid_amount');
            $batch->total_expected = (float) ($batch->students_count * ($batch->fees ?? 0));
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $batches->items(),
                'total' => $batches->total(),
                'current_page' => $batches->currentPage(),
                'last_page' => $batches->lastPage(),
                'per_page' => $batches->perPage(),
                'from' => $batches->firstItem(),
                'to' => $batches->lastItem(),
            ]
        ]);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batch = Batch::where('institute_id', $request->user()->id)
            ->withCount('students')
            ->find($id);

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found or unauthorized'
            ], 404);
        }

        // Calculate total fees paid and expected for this batch
        $studentIds = $batch->students()->pluck('id');
        $batch->total_paid = (float) \App\Models\Fee::whereIn('student_id', $studentIds)->sum('paid_amount');
        $batch->total_expected = (float) ($batch->students_count * ($batch->fees ?? 0));

        return response()->json([
            'status' => 'success',
            'data' => $batch
        ]);
    }

    /**
     * Store a newly created batch for the institute.
     */
    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fees' => 'nullable|numeric|min:0',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'days' => 'nullable|array',
            'max_capacity' => 'nullable|integer|min:1',
            'classroom' => 'nullable|string|max:255',
        ]);

        if ($request->has('days') && is_array($request->days)) {
            if (count($request->days) !== count(array_unique($request->days))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The days field contains duplicate values.'
                ], 422);
            }
        }

        $batch = Batch::create([
            'institute_id' => $request->user()->id,
            'name' => $request->name,
            'subject' => $request->subject,
            'description' => $request->description,
            'fees' => $request->fees,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'days' => $request->days,
            'max_capacity' => $request->max_capacity ?? 30,
            'classroom' => $request->classroom,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Batch created successfully',
            'data' => $batch
        ], 201);
    }

    /**
     * Update the specified batch.
     */
    public function update(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batch = Batch::where('institute_id', $request->user()->id)->find($id);

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found or unauthorized'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'subject' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'fees' => 'nullable|numeric|min:0',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'days' => 'nullable|array',
            'max_capacity' => 'nullable|integer|min:1',
            'classroom' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['name', 'subject', 'description', 'fees', 'start_time', 'end_time', 'days', 'max_capacity', 'classroom']);
        
        if (isset($data['days']) && is_array($data['days'])) {
            if (count($data['days']) !== count(array_unique($data['days']))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The days field contains duplicate values.'
                ], 422);
            }
        }

        $batch->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Batch updated successfully',
            'data' => $batch
        ]);
    }

    /**
     * Remove the specified batch.
     */
    public function destroy(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batch = Batch::where('institute_id', $request->user()->id)->find($id);

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found or unauthorized'
            ], 404);
        }

        $batch->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Batch deleted successfully'
        ]);
    }

    public function export(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batches = Batch::where('institute_id', $request->user()->id)
            ->withCount('students')
            ->get();

        $pdf = Pdf::loadView('institute.export.batches_pdf', compact('batches'));
        return $pdf->download('batches_report_' . date('Y-m-d') . '.pdf');
    }

    public function removeStudent(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batch = Batch::where('institute_id', $request->user()->id)->find($id);

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found or unauthorized'
            ], 404);
        }

        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
        ]);

        $student = \App\Models\Student::where('id', $request->student_id)
            ->where('institute_id', $request->user()->id)
            ->where('batch_id', $id)
            ->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found in this batch'
            ], 404);
        }

        // Remove student from batch (set batch_id to null)
        $student->update(['batch_id' => null]);

        // ── Send Push Notification for Removal ──
        $fcm = app(\App\Services\FCMService::class);
        $notifTitle = "Batch Removed 🚫";
        $notifBody = "You have been removed from the batch: {$batch->name}";
        $notifData = [
            'type' => 'batch_removal',
            'batch_id' => (string) $batch->id,
        ];

        // Notify Student
        \App\Models\Notification::create([
            'user_type' => 'student',
            'user_id' => $student->id,
            'title' => $notifTitle,
            'message' => $notifBody,
            'type' => 'batch_removal',
            'reference_id' => $batch->id,
            'is_read' => false,
        ]);
        if (!empty($student->fcm_token)) {
            $fcm->send($student->fcm_token, $notifTitle, $notifBody, $notifData);
        }

        // Notify Parent
        $student->load('parent');
        if ($student->parent) {
            \App\Models\Notification::create([
                'user_type' => 'parent',
                'user_id' => $student->parent->id,
                'title' => "Batch Removed: {$student->name}",
                'message' => "{$student->name} has been removed from the batch: {$batch->name}",
                'type' => 'batch_removal',
                'reference_id' => $batch->id,
                'is_read' => false,
            ]);
            if (!empty($student->parent->fcm_token)) {
                $fcm->send($student->parent->fcm_token, "Batch Removed: {$student->name}", "{$student->name} has been removed from the batch: {$batch->name}", $notifData);
            }
        }
        // ────────────────────────────────────────

        return response()->json([
            'status' => 'success',
            'message' => 'Student removed from batch successfully'
        ]);
    }

    public function assignStudents(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $batch = Batch::where('institute_id', $request->user()->id)->find($id);

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found or unauthorized'
            ], 404);
        }

        $request->validate([
            'students' => 'required|array',
            'students.*.id' => 'required|integer|exists:students,id',
            'students.*.fee' => 'nullable|numeric|min:0',
        ]);

        $count = 0;
        $assignedStudents = [];
        $fcm = app(\App\Services\FCMService::class);

        foreach ($request->students as $studentData) {
            $student = \App\Models\Student::with('parent')
                ->where('id', $studentData['id'])
                ->where('institute_id', $request->user()->id)
                ->first();

            if ($student) {
                $oldBatchId = $student->batch_id;

                $updateData = ['batch_id' => $id];
                $fee = $studentData['fee'] ?? $student->monthly_fee;
                if (isset($studentData['fee'])) {
                    $updateData['monthly_fee'] = $studentData['fee'];
                }
                $student->update($updateData);

                $assignedStudents[] = [
                    'id' => $student->id,
                    'name' => $student->name,
                    'fee' => $fee
                ];
                $count++;

                // ── Send Push Notification if Batch Changed ──
                if ($oldBatchId != $id) {
                    $notifTitle = "Batch Assigned 📚";
                    $notifBody = "You have been assigned to the batch: {$batch->name}";
                    $notifData = [
                        'type' => 'batch_assignment',
                        'batch_id' => (string) $batch->id,
                    ];

                    // Notify Student
                    \App\Models\Notification::create([
                        'user_type' => 'student',
                        'user_id' => $student->id,
                        'title' => $notifTitle,
                        'message' => $notifBody,
                        'type' => 'batch_assignment',
                        'reference_id' => $batch->id,
                        'is_read' => false,
                    ]);
                    if (!empty($student->fcm_token)) {
                        $fcm->send($student->fcm_token, $notifTitle, $notifBody, $notifData);
                    }

                    // Notify Parent
                    if ($student->parent) {
                        \App\Models\Notification::create([
                            'user_type' => 'parent',
                            'user_id' => $student->parent->id,
                            'title' => "Batch Assigned: {$student->name}",
                            'message' => "{$student->name} has been assigned to the batch: {$batch->name}",
                            'type' => 'batch_assignment',
                            'reference_id' => $batch->id,
                            'is_read' => false,
                        ]);
                        if (!empty($student->parent->fcm_token)) {
                            $fcm->send($student->parent->fcm_token, "Batch Assigned: {$student->name}", "{$student->name} has been assigned to the batch: {$batch->name}", $notifData);
                        }
                    }
                }
                // ──────────────────────────────────────────────
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Successfully assigned $count students to the batch",
            'data' => [
                'count' => $count,
                'students' => $assignedStudents
            ]
        ]);
    }
}
