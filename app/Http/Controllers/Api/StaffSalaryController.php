<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaffSalary;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffSalaryController extends Controller
{
    /**
     * Get salary records list.
     */
    public function index(Request $request)
    {
        $instituteId = $request->user()->id;
        $query = StaffSalary::where('institute_id', $instituteId)->with('staff:id,full_name,employee_id');

        if ($request->has('month')) {
            $query->where('month', $request->month);
        }

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('staff', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
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
            ]
        ]);
    }

    /**
     * Store new salary record.
     */
    public function store(Request $request)
    {
        $instituteId = $request->user()->id;

        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:staff,id,institute_id,' . $instituteId,
            'payment_date' => 'required|date|before_or_equal:today',
            'base_salary' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'payment_method' => 'nullable|in:Cash,Online',
            'status' => 'required|in:Paid,Pending',
            'note' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['institute_id'] = $instituteId;
        
        // Extract month and year from payment_date
        $date = \Carbon\Carbon::parse($request->payment_date);
        $data['month'] = $date->month;
        $data['year'] = $date->year;
        
        $data['net_salary'] = $data['base_salary'] + ($data['bonus'] ?? 0) - ($data['deductions'] ?? 0);

        $salary = StaffSalary::updateOrCreate(
            [
                'staff_id' => $data['staff_id'],
                'month' => $data['month'],
                'year' => $data['year'],
                'institute_id' => $instituteId
            ],
            $data
        );

        return response()->json([
            'message' => 'Salary record saved successfully',
            'data' => $salary
        ]);
    }

    /**
     * Preview salary for a staff (Summary).
     */
    public function preview(Request $request, $staff_id)
    {
        $instituteId = $request->user()->id;
        $staff = Staff::where('institute_id', $instituteId)->find($staff_id);

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        // Simple preview logic
        return response()->json([
            'status' => 'success',
            'data' => [
                'staff_name' => $staff->full_name,
                'employee_id' => $staff->employee_id,
                'base_salary' => $staff->base_salary,
                'suggested_deductions' => 0, // Could be calculated from attendance
                'suggested_bonus' => 0
            ]
        ]);
    }

    /**
     * Export salaries (Simplified JSON for now).
     */
    public function export(Request $request)
    {
        $institute = $request->user();
        $query = StaffSalary::where('institute_id', $institute->id)
            ->with('staff:id,full_name,employee_id');

        if ($request->has('month')) {
            $query->where('month', $request->month);
        }

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        $salaries = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.staff_salary', [
            'institute' => $institute,
            'salaries' => $salaries
        ]);

        return $pdf->download('staff_salary_' . date('Y-m-d') . '.pdf');
    }
}
