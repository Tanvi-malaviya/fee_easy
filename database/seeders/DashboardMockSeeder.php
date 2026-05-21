<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\DailyUpdate;
use App\Models\Fee;
use App\Models\Homework;
use App\Models\Notification;
use App\Models\Resource;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Carbon;

class DashboardMockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::updateOrCreate(
            ['email' => 'tanvimalaviya2004@gmail.com'],
            [
                'id' => 1,
                'name' => 'sanjana',
                'phone' => '9910256145',
                'password' => bcrypt('n2gmoeTAeT'),
                'institute_id' => 1,
                'batch_id' => 1,
                'standard' => '10',
                'dob' => '2026-05-04',
                'guardian_name' => 'pradipbhai',
                'monthly_fee' => 600.00,
                'status' => 1,
                'id_hash' => 's2M4sQitOKZOYsiPS8TlGU8c0hShqNiH',
                'address_line_1' => '48 sakaar society',
                'address_line_2' => 'nikol naroda road',
                'city' => 'Ahmedabad',
                'state' => 'gujarat',
                'country' => 'india',
                'pincode' => '122221',
            ]
        );

        $instituteId = $student->institute_id;
        $batchId = $student->batch_id;

        // 1. Seed Teacher
        Teacher::firstOrCreate(
            ['institute_id' => $instituteId, 'subject' => 'Science'],
            [
                'name' => 'Mr. Verma',
                'phone' => '9876543210',
                'email' => 'verma@test.com',
                'designation' => 'Senior Science Teacher',
                'salary' => 50000,
                'join_date' => Carbon::now()->subYears(2)->toDateString(),
                'status' => 'active',
            ]
        );

        // 2. Seed Attendance for this week
        $startOfWeek = Carbon::now()->startOfWeek();
        
        // Monday Attendance: Present
        Attendance::firstOrCreate(
            ['student_id' => $student->id, 'date' => $startOfWeek->copy()->toDateString()],
            [
                'batch_id' => $batchId,
                'status' => 'present',
                'marked_by' => 'institute',
            ]
        );

        // Tuesday Attendance: Late (or absent/leave)
        Attendance::firstOrCreate(
            ['student_id' => $student->id, 'date' => $startOfWeek->copy()->addDays(1)->toDateString()],
            [
                'batch_id' => $batchId,
                'status' => 'late',
                'marked_by' => 'institute',
            ]
        );

        // Wednesday Attendance (Today): Present (checked in)
        Attendance::firstOrCreate(
            ['student_id' => $student->id, 'date' => Carbon::today()->toDateString()],
            [
                'batch_id' => $batchId,
                'status' => 'present',
                'marked_by' => 'institute',
                'created_at' => Carbon::today()->setTime(8, 0, 0),
            ]
        );

        // 3. Seed Fees
        Fee::firstOrCreate(
            ['student_id' => $student->id, 'date' => '2026-05-25'],
            [
                'institute_id' => $instituteId,
                'total_amount' => 5000,
                'paid_amount' => 500,
                'status' => 'partial',
            ]
        );

        // 4. Seed Homework / Assignments
        if ($batchId) {
            // Due Today
            Homework::firstOrCreate(
                ['batch_id' => $batchId, 'title' => 'Trigonometry — Ch. 8 exercises'],
                [
                    'institute_id' => $instituteId,
                    'description' => 'Solve questions 1 to 10 from exercises of chapter 8.',
                    'due_date' => Carbon::today()->toDateString(),
                ]
            );

            // Due Tomorrow
            Homework::firstOrCreate(
                ['batch_id' => $batchId, 'title' => 'Light: Reflection notes'],
                [
                    'institute_id' => $instituteId,
                    'description' => 'Review reflection laws and write short summary notes.',
                    'due_date' => Carbon::tomorrow()->toDateString(),
                ]
            );
        }

        // 5. Seed Resources / Study Material
        if ($batchId) {
            Resource::firstOrCreate(
                ['batch_id' => $batchId, 'title' => 'Trigonometry — quick reference'],
                [
                    'institute_id' => $instituteId,
                    'description' => 'Quick formulas list for trigonometry.',
                    'file_path' => 'resources/trig_ref.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 1258291, // ~1.2 MB
                ]
            );

            Resource::firstOrCreate(
                ['batch_id' => $batchId, 'title' => 'Reflection — concept video'],
                [
                    'institute_id' => $instituteId,
                    'description' => 'Concept video on reflection of light.',
                    'file_path' => 'resources/reflection.mp4',
                    'file_type' => 'mp4',
                    'file_size' => 52428800, // ~50 MB
                ]
            );
        }

        // 6. Seed Announcement / Notification
        Notification::firstOrCreate(
            ['user_id' => $student->id, 'user_type' => 'student', 'title' => 'Science Day exhibition'],
            [
                'message' => 'Saturday 24 May, 10 AM. Set up your project by 9:30. Parents welcome.',
                'type' => 'announcement',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(2),
            ]
        );

        // 7. Seed Daily Update
        if ($batchId) {
            DailyUpdate::firstOrCreate(
                ['batch_id' => $batchId, 'date' => Carbon::today()->toDateString()],
                [
                    'institute_id' => $instituteId,
                    'topic' => 'Identities & Formulas',
                    'description' => 'Today we covered identities sin^2 + cos^2 = 1 and complementary angle formulas.',
                    'recipient' => 'both',
                    'target_type' => 'batch',
                    'created_at' => Carbon::now()->subHours(4),
                ]
            );
        }

        $this->command->info("Dashboard mock data seeded successfully for student Tanvi Malaviya!");
    }
}
