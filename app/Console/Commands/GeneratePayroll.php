<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Staff;
use App\Models\StaffSalary;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GeneratePayroll extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'payroll:generate';

    /**
     * The console command description.
     */
    protected $description = 'Auto-create a draft (Pending) salary record for every active staff member for the current month, so the institute only needs to review and mark as Paid instead of entering it from scratch.';

    public function handle(): void
    {
        $month = (int) now()->month;
        $year = (int) now()->year;

        $staffMembers = Staff::where('status', 'active')
            ->whereNotNull('base_salary')
            ->get();

        $createdByInstitute = [];

        foreach ($staffMembers as $staff) {
            $exists = StaffSalary::where('staff_id', $staff->id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if ($exists) {
                continue;
            }

            $baseSalary = (float) $staff->base_salary;

            StaffSalary::create([
                'staff_id' => $staff->id,
                'institute_id' => $staff->institute_id,
                'month' => $month,
                'year' => $year,
                'base_salary' => $baseSalary,
                'bonus' => 0,
                'deductions' => 0,
                'net_salary' => $baseSalary,
                'payment_date' => null,
                'payment_method' => 'Cash',
                'status' => 'Pending',
                'notes' => 'Auto-generated draft — review and confirm to mark as Paid.',
            ]);

            $createdByInstitute[$staff->institute_id] = ($createdByInstitute[$staff->institute_id] ?? 0) + 1;
        }

        foreach ($createdByInstitute as $instituteId => $count) {
            $periodLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');

            Notification::create([
                'user_type' => 'institute',
                'user_id' => $instituteId,
                'title' => 'Payroll Draft Ready',
                'message' => "{$count} draft salary record(s) for {$periodLabel} have been created and are awaiting your review in Staff Salary.",
                'type' => 'payroll_draft',
                'is_read' => false,
            ]);
        }

        $total = array_sum($createdByInstitute);
        $this->info("Created {$total} draft salary record(s) across " . count($createdByInstitute) . " institute(s).");
    }
}
