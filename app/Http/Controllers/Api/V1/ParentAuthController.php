<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StudentParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $parent = StudentParent::where('email', $request->email)->first();

        if (! $parent || ! Hash::check($request->password, $parent->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = $parent->createToken('parent_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'data' => array_merge(
                ['token' => $token],
                $parent->toArray()
            )
        ]);
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }

    public function profile(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => 'success',
            'data' => $request->user()->load('students')
        ]);
    }
}
