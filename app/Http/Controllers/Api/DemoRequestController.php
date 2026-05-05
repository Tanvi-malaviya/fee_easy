<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DemoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoBookedMail;

class DemoRequestController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'institute_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'designation' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $demoRequest = DemoRequest::create($request->all());

        // Send confirmation email
        try {
            Mail::to($demoRequest->email)->send(new DemoBookedMail($demoRequest->toArray()));
        } catch (\Exception $e) {
            // Log error or handle as needed, but don't block the response
            \Log::error('Mail failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Demo request submitted successfully!',
            'data' => $demoRequest
        ], 201);
    }
}
