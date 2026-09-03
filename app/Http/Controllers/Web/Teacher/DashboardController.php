<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();

        $batches = Batch::where('staff_id', $teacher->id)
            ->withCount('students')
            ->orderBy('name')
            ->get();

        return view('teacher.dashboard', compact('teacher', 'batches'));
    }
}
