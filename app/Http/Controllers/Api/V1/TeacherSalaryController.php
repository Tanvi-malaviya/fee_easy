<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StaffSalary;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TeacherSalaryController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user();
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = StaffSalary::where('staff_id', $teacher->id);

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $salaries = $query->orderBy('payment_date', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $salaries->items(),
            'pagination' => [
                'total' => $salaries->total(),
                'per_page' => $salaries->perPage(),
                'current_page' => $salaries->currentPage(),
                'last_page' => $salaries->lastPage(),
            ],
        ]);
    }

    /**
     * Download the teacher's own salary slip as PDF (reuses the same
     * pdf.salary_slip_single view the institute-side email uses).
     */
    public function download(Request $request, $id)
    {
        $teacher = $request->user();
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $salary = StaffSalary::where('id', $id)->where('staff_id', $teacher->id)->first();
        if (!$salary) {
            return response()->json(['status' => 'error', 'message' => 'Salary record not found.'], 404);
        }

        $teacher->load(['role', 'department', 'departments', 'institute']);

        $pdf = Pdf::loadView('pdf.salary_slip_single', [
            'salary' => $salary,
            'staff' => $teacher,
            'institute' => $teacher->institute,
        ]);

        return $pdf->download('salary_slip_' . $salary->month . '_' . $salary->year . '.pdf');
    }
}
