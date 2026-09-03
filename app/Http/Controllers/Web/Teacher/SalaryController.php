<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StaffSalary;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();

        $salaries = StaffSalary::where('staff_id', $teacher->id)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(12);

        return view('teacher.salary.index', compact('teacher', 'salaries'));
    }
}
