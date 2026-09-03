<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    public function show($id)
    {
        $teacher = Auth::guard('teacher')->user();

        $batch = Batch::where('id', $id)->where('staff_id', $teacher->id)->withCount('students')->firstOrFail();

        return view('teacher.batches.show', compact('teacher', 'batch'));
    }
}
