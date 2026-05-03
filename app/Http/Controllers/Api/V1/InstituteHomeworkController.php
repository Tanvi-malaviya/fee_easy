<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Homework;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstituteHomeworkController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = $request->user()
            ->homeworks();

        if ($request->has('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $homeworks = $query->select('id', 'batch_id', 'title', 'description', 'due_date', 'attachment', 'created_at')
            ->with([
                'batch' => function($q) {
                    $q->select('id', 'name')->with('students:id,name,batch_id');
                },
                'submissions' => function ($q) {
                    $q->select('id', 'homework_id', 'student_id', 'score', 'status')
                        ->with('student:id,name');
                }
            ])
            ->withCount('submissions')
            ->orderByDesc('created_at')
            ->paginate(10);

        // Transform to include pending students
        $homeworks->each(function($homework) {
            $submissions = $homework->submissions->keyBy('student_id');
            $allSubmissions = [];

            if ($homework->batch && $homework->batch->students) {
                foreach ($homework->batch->students as $student) {
                    if ($submissions->has($student->id)) {
                        $sub = $submissions->get($student->id);
                        $allSubmissions[] = $sub;
                    } else {
                        $allSubmissions[] = [
                            'id' => null,
                            'homework_id' => $homework->id,
                            'student_id' => $student->id,
                            'score' => 0,
                            'status' => 'pending',
                            'student' => [
                                'id' => $student->id,
                                'name' => $student->name,
                                'profile_image_url' => $student->profile_image_url // Assuming this accessor exists
                            ]
                        ];
                    }
                }
            }
            
            $homework->setRelation('submissions', collect($allSubmissions));
            
            if ($homework->attachment) {
                $homework->attachment = asset('storage/' . $homework->attachment);
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $homeworks,
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'batch_id' => 'required|integer|exists:batches,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'attachment' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $institute = $request->user();
        $batch = Batch::where('id', $request->batch_id)
            ->where('institute_id', $institute->id)
            ->first();

        if (!$batch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch not found for this institute.',
            ], 404);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('homework_attachments', $filename, 'public');
        }

        $homework = Homework::create([
            'batch_id' => $batch->id,
            'institute_id' => $institute->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'attachment' => $attachmentPath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Homework created successfully.',
            'data' => $homework,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = Homework::select('id', 'batch_id', 'institute_id', 'title', 'description', 'due_date', 'attachment', 'created_at')
            ->with([
                'batch' => function ($q) {
                    $q->select('id', 'name', 'subject');
                    $q->with([
                        'students' => function ($sq) {
                            $sq->select('id', 'name', 'profile_image', 'batch_id');
                        }
                    ]);
                }
            ])
            ->with([
                'submissions' => function ($q) {
                    $q->select('id', 'homework_id', 'student_id', 'status', 'score');
                }
            ])
            ->where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        // Transform to include pending students
        $submissions = $homework->submissions->keyBy('student_id');
        $allSubmissions = [];

        if ($homework->batch && $homework->batch->students) {
            foreach ($homework->batch->students as $student) {
                if ($submissions->has($student->id)) {
                    $submission = $submissions->get($student->id);
                    $submission->student = [
                        'id' => $student->id,
                        'name' => $student->name,
                        'profile_image_url' => $student->profile_image_url
                    ];
                    $allSubmissions[] = $submission;
                } else {
                    $allSubmissions[] = [
                        'id' => null,
                        'homework_id' => $homework->id,
                        'student_id' => $student->id,
                        'score' => 0,
                        'status' => 'pending',
                        'student' => [
                            'id' => $student->id,
                            'name' => $student->name,
                            'profile_image_url' => $student->profile_image_url
                        ]
                    ];
                }
            }
        }

        $homework->setRelation('submissions', collect($allSubmissions));

        // Convert attachment to full URL if exists
        if ($homework->attachment) {
            $homework->attachment = asset('storage/' . $homework->attachment);
        }

        return response()->json([
            'status' => 'success',
            'data' => $homework,
        ]);
    }

    public function updateScore(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'scores' => 'required|array',
            'scores.*.student_id' => 'required|integer|exists:students,id',
            'scores.*.score' => 'required|numeric|min:0',
        ]);

        $homework = Homework::where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        $savedSubmissions = [];
        foreach ($request->scores as $scoreData) {
            $submission = \App\Models\HomeworkSubmission::updateOrCreate(
                [
                    'homework_id' => $homework->id,
                    'student_id' => $scoreData['student_id'],
                ],
                [
                    'score' => $scoreData['score'],
                    'status' => 'submitted',
                ]
            );
            $savedSubmissions[] = $submission;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Scores updated successfully.',
            'data' => $savedSubmissions,
        ]);
    }

    public function updateGrades(Request $request, $id)
    {
        if (!$request->user() || !($request->user() instanceof Institute)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $homework = Homework::where('id', $id)
            ->where('institute_id', $request->user()->id)
            ->first();

        if (!$homework) {
            return response()->json(['status' => 'error', 'message' => 'Homework not found'], 404);
        }

        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|integer|exists:students,id',
            'grades.*.score' => 'nullable|numeric',
            'grades.*.status' => 'required|string',
        ]);

        foreach ($request->grades as $gradeData) {
            // Standardize status to Title Case for consistency
            $status = ucfirst(strtolower($gradeData['status']));
            
            // Validate standardized status
            if (!in_array($status, ['Pending', 'Missing', 'Late', 'Submitted'])) {
                continue;
            }

            $homework->submissions()->updateOrCreate(
                ['student_id' => $gradeData['student_id']],
                [
                    'score' => $gradeData['score'],
                    'status' => $status,
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Grades updated successfully',
        ]);
    }
}
