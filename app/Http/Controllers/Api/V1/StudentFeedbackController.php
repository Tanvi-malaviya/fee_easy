<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentFeedbackController extends Controller
{
    /**
     * POST /api/v1/student/feedback
     *
     * Body:
     *   rating  : "love_it" | "useful" | "meh" | "broken"  (optional)
     *   message : string  (optional, but at least one required)
     */
    public function store(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'rating'  => 'nullable|in:love_it,useful,meh,broken',
            'message' => 'nullable|string|max:2000',
        ]);

        // At least one of rating or message must be provided
        if (empty($request->rating) && empty($request->message)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Please select a rating or write a message.',
            ], 422);
        }

        $student  = $request->user();

        Feedback::create([
            'user_type' => 'student',
            'user_id'   => $student->id,
            'rating'    => $request->rating,
            'message'   => $request->message,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Thank you! Your feedback has been sent.',
        ], 201);
    }
}
