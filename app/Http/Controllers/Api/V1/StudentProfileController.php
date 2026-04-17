<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function show(Request $request)
    {
        $student = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => $student,
        ]);
    }
}
