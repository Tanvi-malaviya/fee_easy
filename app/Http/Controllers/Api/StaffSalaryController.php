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
        $query = StaffSalary::where('institute_id', $instituteId)->with('staff:id,full_name,employee_id,profile_image');

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

        // Calculate total amount before pagination
        $totalAmount = (clone $query)->sum('net_salary');

        $salaries = $query->orderBy('payment_date', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'total_amount' => $totalAmount,
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

        $allowedFields = ['staff_id', 'salary_id', 'payment_date', 'base_salary', 'bonus', 'deductions', 'payment_method', 'status', 'notes'];
        $unwantedFields = array_diff(array_keys($request->all()), $allowedFields);

        if (!empty($unwantedFields)) {
            return response()->json([
                'errors' => [
                    'unexpected_fields' => ['The following fields are not allowed: ' . implode(', ', $unwantedFields)]
                ]
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:staff,id,institute_id,' . $instituteId,
            'payment_date' => 'required|date|before_or_equal:today',
            'base_salary' => 'required|numeric|min:1|max:999999',
            'bonus' => 'nullable|numeric|max:999999',
            'deductions' => 'nullable|numeric|min:0|max:999999',
            'payment_method' => 'nullable|in:Cash,Online',
            'status' => 'required|in:Paid,Pending',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['institute_id'] = $instituteId;
        
        // Extract month and year from payment_date
        $date = \Carbon\Carbon::parse($request->payment_date);
        $month = $date->month;
        $year = $date->year;
        
        $data['month'] = $month;
        $data['year'] = $year;
        $data['net_salary'] = $data['base_salary'] + ($data['bonus'] ?? 0) - ($data['deductions'] ?? 0);

        if ($request->filled('salary_id')) {
            $salary = StaffSalary::where('institute_id', $instituteId)->find($request->salary_id);
            if ($salary) {
                $salary->update($data);
            } else {
                return response()->json(['message' => 'Salary record not found'], 404);
            }
        } else {
            $salary = StaffSalary::create($data);
        }

        return response()->json([
            'message' => 'Salary record saved successfully',
            'data' => $salary
        ]);
    }

    public function preview(Request $request, $staff_id)
    {
        $instituteId = $request->user()->id;
        $staff = Staff::where('institute_id', $instituteId)->find($staff_id);

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $absentCount = \App\Models\StaffAttendance::where('staff_id', $staff_id)
            ->where('status', 'absent')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->count();

        // Simple preview logic
        return response()->json([
            'status' => 'success',
            'data' => [
                'staff_name' => $staff->full_name,
                'employee_id' => $staff->employee_id,
                'base_salary' => $staff->base_salary,
                'suggested_deductions' => 0, // Could be calculated from attendance
                'suggested_bonus' => 0,
                'leaves' => $absentCount
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

    /**
     * Get salary records for a particular staff.
     */
    public function showByStaff(Request $request, $staffId)
    {
        $instituteId = $request->user()->id;
        $query = StaffSalary::where('institute_id', $instituteId)
            ->where('staff_id', $staffId);

        if ($request->has('month')) {
            $query->where('month', $request->month);
        }

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        $totalAmount = (clone $query)->sum('net_salary');
        $salaries = $query->orderBy('payment_date', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'total_amount' => $totalAmount,
            'data' => $salaries->items(),
            'pagination' => [
                'total' => $salaries->total(),
                'per_page' => $salaries->perPage(),
                'current_page' => $salaries->currentPage(),
                'last_page' => $salaries->lastPage(),
            ]
        ]);
    }
}
