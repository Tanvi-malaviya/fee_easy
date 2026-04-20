<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class PublicVerificationController extends Controller
{
    /**
     * Verify a student ID card via QR hash.
     * Accessible by anyone with the QR link.
     */
    public function verifyID(Request $request)
    {
        $request->validate([
            'hash' => 'required|string|size:32'
        ]);

        $student = Student::where('id_hash', $request->hash)
            ->with(['institute:id,institute_name,logo,city', 'batch:id,name'])
            ->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired Student ID Card.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'ID Verified!',
            'data' => [
                'student_name' => $student->name,
                'institute_name' => $student->institute->institute_name,
                'city' => $student->institute->city,
                'batch' => $student->batch ? $student->batch->name : 'N/A',
                'status' => 'Active Member'
            ]
        ]);
    }
}
