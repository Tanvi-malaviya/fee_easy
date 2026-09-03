<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentIdCardController extends Controller
{
    /**
     * The authenticated student's own digital ID card — same shape as the
     * institute-facing `InstituteStudentController::idCard()`, scoped to
     * `$request->user()` instead of a path {id} so a student can only ever
     * fetch their own card.
     */
    public function show(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user()->load([
            'batch',
            'institute:id,institute_name,logo,address,city,phone',
        ]);

        $qrPayload = json_encode([
            'type' => 'student_id_verification',
            'hash' => $student->id_hash,
            'name' => $student->name,
            'institute' => $student->institute->institute_name,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'phone' => $student->phone,
                    'standard' => $student->standard,
                    'dob' => $student->dob,
                    'profile_image_url' => $student->profile_image_url,
                    'batch' => $student->batch ? $student->batch->name : 'N/A',
                ],
                'institute' => $student->institute,
                'qr_payload' => $qrPayload,
                'verification_hash' => $student->id_hash,
            ],
        ]);
    }
}
