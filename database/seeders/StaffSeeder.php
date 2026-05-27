<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaffDepartment;
use App\Models\StaffRole;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instituteId = \App\Models\Institute::first()->id ?? 1;

        // Departments
        $depts = [
            'Administrative Affairs',
            'Finance & Audit',
            'Operations',
            'Academic Oversight',
            'Research Dept',
            'Human Resources',
            'IT Support'
        ];

        foreach ($depts as $dept) {
            StaffDepartment::create([
                'name' => $dept,
                'institute_id' => $instituteId,
            ]);
        }

        // Roles
        $roles = [
            'Senior UX Designer',
            'Lead Developer',
            'Product Manager',
            'Sales Director',
            'HR Operations',
            'Full Stack Engineer',
            'Marketing Specialist',
            'Data Analyst'
        ];

        foreach ($roles as $role) {
            StaffRole::create([
                'name' => $role,
                'institute_id' => $instituteId,
            ]);
        }

        // Sample Staff
        Staff::create([
            'institute_id' => $instituteId,
            'employee_id' => 'EMP-2045',
            'full_name' => 'Sarah Chen',
            'email' => 'sarah.chen@example.com',
            'phone' => '1234567890',
            'staff_role_id' => 1,
            'staff_department_id' => 1,
            'employment_type' => 'Salary',
            'base_salary' => 85000,
            'status' => 'active',
        ]);

        Staff::create([
            'institute_id' => $instituteId,
            'employee_id' => 'EMP-2098',
            'full_name' => 'Marcus Thorne',
            'email' => 'marcus.t@example.com',
            'phone' => '0987654321',
            'staff_role_id' => 2,
            'staff_department_id' => 3,
            'employment_type' => 'Salary',
            'base_salary' => 95000,
            'status' => 'active',
        ]);
    }
}
