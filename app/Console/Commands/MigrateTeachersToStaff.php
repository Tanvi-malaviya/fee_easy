<?php

namespace App\Console\Commands;

use App\Mail\StaffAddedMail;
use App\Models\Institute;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffDepartment;
use App\Models\StaffRole;
use App\Models\Teacher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MigrateTeachersToStaff extends Command
{
    /**
     * One-time migration of the legacy `teachers` / `teacher_attendances`
     * roster into `staff` / `staff_attendances`. The old tables are left
     * intact as a read-only archive — nothing here deletes them.
     */
    protected $signature = 'teachers:migrate-to-staff
        {--institute= : Only migrate teachers belonging to this institute ID}
        {--dry-run : Report what would happen without writing anything}
        {--no-notify : Skip sending the welcome/credentials email to migrated teachers}';

    protected $description = 'Migrate legacy teachers/teacher_attendances rows into staff/staff_attendances.';

    /** @var array<int,int> old teacher_id => new staff_id */
    protected array $idMap = [];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $notify = !$this->option('no-notify') && !$dryRun;

        $query = Teacher::query();
        if ($instituteId = $this->option('institute')) {
            $query->where('institute_id', $instituteId);
        }

        $teachers = $query->get();
        if ($teachers->isEmpty()) {
            $this->info('No legacy teacher records found to migrate.');
            return self::SUCCESS;
        }

        $facultyDept = StaffDepartment::firstOrCreate(['name' => 'Faculty / Teacher']);

        $migrated = 0;
        $skipped = 0;

        foreach ($teachers as $teacher) {
            if (empty($teacher->email)) {
                $this->warn("Skipping teacher #{$teacher->id} ({$teacher->name}) — no email, cannot create a login.");
                $skipped++;
                continue;
            }

            $existing = Staff::where('email', $teacher->email)->first();
            if ($existing) {
                $this->line("Already migrated: {$teacher->email} -> staff #{$existing->id}");
                $this->idMap[$teacher->id] = $existing->id;
                continue;
            }

            $status = strtolower((string) $teacher->status) === 'inactive' ? 'offline' : 'active';

            $roleId = null;
            if (!empty($teacher->designation)) {
                $role = StaffRole::firstOrCreate([
                    'name' => $teacher->designation,
                    'institute_id' => $teacher->institute_id,
                ]);
                $roleId = $role->id;
            }

            $plainPassword = Str::random(10);

            if ($dryRun) {
                $this->info("[dry-run] Would migrate teacher #{$teacher->id} ({$teacher->name}, {$teacher->email}) -> staff (institute #{$teacher->institute_id})");
                $migrated++;
                continue;
            }

            $staff = DB::transaction(function () use ($teacher, $roleId, $facultyDept, $status, $plainPassword) {
                $staff = Staff::create([
                    'full_name' => $teacher->name,
                    'email' => $teacher->email,
                    'phone' => $teacher->phone,
                    'staff_role_id' => $roleId,
                    'staff_department_id' => $facultyDept->id,
                    'employment_type' => 'Salary',
                    'base_salary' => $teacher->salary ?? 0,
                    'status' => $status,
                    'password' => Hash::make($plainPassword),
                    'institute_id' => $teacher->institute_id,
                    'must_change_password' => true,
                    'is_login_blocked' => false,
                ]);

                if ($teacher->join_date) {
                    $staff->created_at = $teacher->join_date;
                    $staff->save();
                }

                $staff->departments()->syncWithoutDetaching([$facultyDept->id]);

                return $staff;
            });

            $this->idMap[$teacher->id] = $staff->id;
            $migrated++;

            $this->info("Migrated teacher #{$teacher->id} ({$teacher->name}) -> staff #{$staff->id}");

            if ($notify) {
                try {
                    $institute = $teacher->institute ?: Institute::find($teacher->institute_id);
                    if ($institute) {
                        \App\Services\InstituteMailService::send(
                            $institute,
                            $staff->email,
                            new StaffAddedMail(
                                $staff->full_name,
                                $staff->email,
                                $staff->employee_id,
                                $staff->role->name ?? 'Teacher',
                                $facultyDept->name,
                                $institute->institute_name,
                                $institute->logo,
                                $plainPassword,
                                route('teacher.login')
                            )
                        );
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send migrated-teacher welcome email: ' . $e->getMessage());
                }
            }
        }

        $this->migrateAttendance($dryRun);

        $this->newLine();
        $this->info("Done. Migrated: {$migrated}, Skipped: {$skipped}.");

        return self::SUCCESS;
    }

    protected function migrateAttendance(bool $dryRun): void
    {
        if (empty($this->idMap)) {
            return;
        }

        $statusMap = [
            'present' => 'Present',
            'absent' => 'Absent',
            'half-day' => 'Half Day',
            'half day' => 'Half Day',
            'leave' => 'Holiday',
        ];

        $attendanceCount = 0;

        \App\Models\TeacherAttendance::whereIn('teacher_id', array_keys($this->idMap))
            ->chunk(200, function ($rows) use (&$attendanceCount, $statusMap, $dryRun) {
                foreach ($rows as $row) {
                    $staffId = $this->idMap[$row->teacher_id] ?? null;
                    if (!$staffId) {
                        continue;
                    }

                    $mappedStatus = $statusMap[strtolower((string) $row->status)] ?? 'Present';

                    if ($dryRun) {
                        $attendanceCount++;
                        continue;
                    }

                    StaffAttendance::updateOrCreate(
                        [
                            'staff_id' => $staffId,
                            'institute_id' => $row->institute_id,
                            'date' => $row->date,
                        ],
                        [
                            'status' => $mappedStatus,
                            'note' => $row->remarks,
                        ]
                    );
                    $attendanceCount++;
                }
            });

        $this->info(($dryRun ? '[dry-run] Would migrate ' : 'Migrated ') . "{$attendanceCount} attendance record(s).");
    }
}
