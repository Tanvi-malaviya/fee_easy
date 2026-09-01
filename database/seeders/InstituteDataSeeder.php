<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InstituteDataSeeder extends Seeder
{
    const INSTITUTE_ID   = 2;
    const INSTITUTE_CODE = '416761';

    public function run(): void
    {
        $instituteId   = self::INSTITUTE_ID;
        $instituteCode = self::INSTITUTE_CODE;

        // Unique 6-char suffix per run — prevents duplicate email/employee_id on re-runs
        $runId = strtolower(Str::random(6));

        $this->command->info('Starting InstituteDataSeeder for Institute ID ' . $instituteId);

        // ── 1. BATCHES (10) ──────────────────────────────────────────────────
        $this->command->info('Creating 10 batches...');

        $batchData = [
            ['name' => 'Batch A', 'subject' => 'Mathematics',      'fees' => 3000, 'start_time' => '08:00:00', 'end_time' => '10:00:00'],
            ['name' => 'Batch B', 'subject' => 'Physics',           'fees' => 3500, 'start_time' => '10:00:00', 'end_time' => '12:00:00'],
            ['name' => 'Batch C', 'subject' => 'Chemistry',         'fees' => 3500, 'start_time' => '12:00:00', 'end_time' => '14:00:00'],
            ['name' => 'Batch D', 'subject' => 'Biology',           'fees' => 2500, 'start_time' => '14:00:00', 'end_time' => '16:00:00'],
            ['name' => 'Batch E', 'subject' => 'English',           'fees' => 2000, 'start_time' => '08:00:00', 'end_time' => '09:30:00'],
            ['name' => 'Batch F', 'subject' => 'Computer Science',  'fees' => 4000, 'start_time' => '09:30:00', 'end_time' => '11:30:00'],
            ['name' => 'Batch G', 'subject' => 'Economics',         'fees' => 2500, 'start_time' => '11:30:00', 'end_time' => '13:00:00'],
            ['name' => 'Batch H', 'subject' => 'History',           'fees' => 2000, 'start_time' => '13:00:00', 'end_time' => '14:30:00'],
            ['name' => 'Batch I', 'subject' => 'Geography',         'fees' => 2000, 'start_time' => '15:00:00', 'end_time' => '16:30:00'],
            ['name' => 'Batch J', 'subject' => 'Hindi',             'fees' => 1500, 'start_time' => '16:30:00', 'end_time' => '18:00:00'],
        ];

        $batchIds = [];
        foreach ($batchData as $idx => $batch) {
            $batchIds[] = DB::table('batches')->insertGetId([
                'institute_id' => $instituteId,
                'name'         => $batch['name'],
                'subject'      => $batch['subject'],
                'description'  => $batch['subject'] . ' foundation & advanced course',
                'fees'         => $batch['fees'],
                'start_time'   => $batch['start_time'],
                'end_time'     => $batch['end_time'],
                'days'         => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
                'classroom'    => 'Room ' . ($idx + 101),
                'status'       => 'active',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // ── 2. STUDENTS (100) ─────────────────────────────────────────────────
        // 80 students assigned to batches (8 per batch), 20 without batch
        $this->command->info('Creating 100 students...');

        $firstNames = [
            'Aarav', 'Arjun', 'Vivaan', 'Aditya', 'Vihaan', 'Reyansh', 'Ayaan', 'Darsh', 'Arnav', 'Ishaan',
            'Shaurya', 'Atharv', 'Advaith', 'Dhruv', 'Kabir', 'Ananya', 'Diya', 'Priya', 'Myra', 'Aanya',
            'Kritika', 'Saanvi', 'Shreya', 'Riya', 'Pooja', 'Neha', 'Sanya', 'Isha', 'Tanvi', 'Rohan',
            'Rahul', 'Siddharth', 'Kunal', 'Nikhil', 'Aakash', 'Abhinav', 'Vikram', 'Pranav', 'Dev', 'Parth',
            'Karan', 'Naman', 'Yash', 'Harsh', 'Abhishek', 'Sumit', 'Gaurav', 'Deepak', 'Manoj', 'Sakshi',
            'Muskan', 'Komal', 'Preeti', 'Swati', 'Ankita', 'Divya', 'Pallavi', 'Sonam', 'Rekha', 'Aaditya',
            'Abhay', 'Achyut', 'Adarsh', 'Adish', 'Advik', 'Ahaan', 'Ajay', 'Akash', 'Akshat', 'Akul',
            'Alok', 'Amogh', 'Amrit', 'Anant', 'Ansh', 'Anshul', 'Anurag', 'Arpit', 'Ashish', 'Aviral',
            'Ayush', 'Bhuvan', 'Chirag', 'Daksh', 'Devansh', 'Devesh', 'Dhruval', 'Divik', 'Ekansh', 'Hemant',
            'Himanshu', 'Ishan', 'Jagat', 'Jatin', 'Jay', 'Jeevan', 'Kalyan', 'Kartik', 'Krish', 'Lakshya',
        ];
        $lastNames = [
            'Sharma', 'Verma', 'Patel', 'Gupta', 'Singh', 'Kumar', 'Mehta', 'Joshi', 'Shah', 'Yadav',
            'Mishra', 'Dubey', 'Tiwari', 'Pandey', 'Soni', 'Agarwal', 'Bhatia', 'Chauhan', 'Dixit', 'Garg',
        ];

        $studentRows = []; // track ['id', 'batch_id', 'batch_fees']
        $year        = date('Y');
        $prefix      = $year . $instituteCode;

        // Find the next enrollment sequence for this institute
        $lastEnrollment = DB::table('students')
            ->where('institute_id', $instituteId)
            ->where('enrollment_id', 'like', $prefix . '%')
            ->orderByRaw('LENGTH(enrollment_id) DESC')
            ->orderBy('enrollment_id', 'desc')
            ->value('enrollment_id');

        $nextSeq = 1;
        if ($lastEnrollment) {
            $seq = substr($lastEnrollment, strlen($prefix));
            if (is_numeric($seq)) {
                $nextSeq = intval($seq) + 1;
            }
        }

        // 80 batch students
        foreach ($batchIds as $bIdx => $batchId) {
            $batchFees = $batchData[$bIdx]['fees'];
            for ($s = 0; $s < 8; $s++) {
                $nameIdx   = ($bIdx * 8 + $s) % count($firstNames);
                $firstName = $firstNames[$nameIdx];
                $lastName  = $lastNames[($bIdx * 8 + $s) % count($lastNames)];
                $email     = strtolower($firstName . '.' . $lastName . $nextSeq . '.' . $runId . '@example.com');

                $studentId = DB::table('students')->insertGetId([
                    'enrollment_id' => $prefix . str_pad($nextSeq, 5, '0', STR_PAD_LEFT),
                    'id_hash'       => Str::random(16),
                    'name'          => $firstName . ' ' . $lastName,
                    'email'         => $email,
                    'phone'         => '9' . rand(100000000, 999999999),
                    'password'      => bcrypt('password'),
                    'institute_id'  => $instituteId,
                    'batch_id'      => $batchId,
                    'monthly_fee'   => $batchFees,
                    'status'        => 'active',
                    'created_at'    => now()->subMonths(rand(1, 5)),
                    'updated_at'    => now(),
                ]);

                $studentRows[] = ['id' => $studentId, 'batch_id' => $batchId, 'batch_fees' => $batchFees];
                $nextSeq++;
            }
        }

        // 20 unassigned students
        for ($s = 0; $s < 20; $s++) {
            $nameIdx   = (80 + $s) % count($firstNames);
            $firstName = $firstNames[$nameIdx];
            $lastName  = $lastNames[$s % count($lastNames)];
            $email     = strtolower($firstName . '.' . $lastName . $nextSeq . '.' . $runId . '@example.com');

            $studentId = DB::table('students')->insertGetId([
                'enrollment_id' => $prefix . str_pad($nextSeq, 5, '0', STR_PAD_LEFT),
                'id_hash'       => Str::random(16),
                'name'          => $firstName . ' ' . $lastName,
                'email'         => $email,
                'phone'         => '9' . rand(100000000, 999999999),
                'password'      => bcrypt('password'),
                'institute_id'  => $instituteId,
                'batch_id'      => null,
                'monthly_fee'   => [1500, 2000, 2500, 3000][$s % 4],
                'status'        => 'active',
                'created_at'    => now()->subMonths(rand(1, 5)),
                'updated_at'    => now(),
            ]);

            $studentRows[] = ['id' => $studentId, 'batch_id' => null, 'batch_fees' => [1500, 2000, 2500, 3000][$s % 4]];
            $nextSeq++;
        }

        // ── 3. ATTENDANCE – May 2025 (batch students only) ───────────────────
        $this->command->info('Creating May 2025 attendance...');

        $mayStart         = Carbon::create(2025, 5, 1);
        $mayEnd           = Carbon::create(2025, 5, 31);
        $attendanceChunks = [];

        foreach ($studentRows as $student) {
            if (!$student['batch_id']) {
                continue;
            }

            $date = $mayStart->copy();
            while ($date <= $mayEnd) {
                if (!$date->isWeekend()) {
                    $r      = rand(1, 100);
                    $status = $r <= 75 ? 'Present' : ($r <= 92 ? 'Absent' : 'Leave');

                    $attendanceChunks[] = [
                        'student_id' => $student['id'],
                        'batch_id'   => $student['batch_id'],
                        'date'       => $date->format('Y-m-d'),
                        'status'     => $status,
                        'marked_by'  => null,
                        'created_at' => $date->copy()->setTime(9, 0),
                        'updated_at' => $date->copy()->setTime(9, 0),
                    ];
                }
                $date->addDay();
            }
        }

        foreach (array_chunk($attendanceChunks, 500) as $chunk) {
            DB::table('attendance')->insert($chunk);
        }

        // ── 4. FEES & PAYMENTS (80 transactions – one per batch student) ──────
        $this->command->info('Creating 80 fee transactions...');

        $batchStudents = array_filter($studentRows, fn($s) => $s['batch_id'] !== null);

        foreach ($batchStudents as $student) {
            $totalAmount = $student['batch_fees'];

            // Payment never exceeds total_amount
            $paidAmount = rand(0, 1) === 1
                ? $totalAmount                              // fully paid
                : (int) round($totalAmount * (rand(30, 90) / 100)); // partial

            $status   = $paidAmount >= $totalAmount ? 'Paid' : ($paidAmount > 0 ? 'Partial' : 'Pending');
            $feeDate  = Carbon::create(2025, 5, rand(1, 25));

            $feeId = DB::table('fees')->insertGetId([
                'student_id'   => $student['id'],
                'institute_id' => $instituteId,
                'total_amount' => $totalAmount,
                'paid_amount'  => $paidAmount,
                'status'       => $status,
                'date'         => $feeDate->format('Y-m-d'),
                'created_at'   => $feeDate,
                'updated_at'   => $feeDate,
            ]);

            if ($paidAmount > 0) {
                DB::table('payments')->insert([
                    'fee_id'         => $feeId,
                    'student_id'     => $student['id'],
                    'amount'         => $paidAmount,
                    'payment_method' => rand(0, 1) ? 'Cash' : 'Online',
                    'transaction_id' => 'TXN' . strtoupper(Str::random(10)),
                    'paid_at'        => $feeDate,
                    'created_at'     => $feeDate,
                    'updated_at'     => $feeDate,
                ]);
            }
        }

        // ── 5. HOMEWORKS (10 per batch = 100 total) ──────────────────────────
        $this->command->info('Creating 100 homeworks (10 per batch)...');

        $hwTopics = [
            'Chapter 1 Exercises', 'Chapter 2 Problems', 'Mid-term Revision', 'Practice Set A',
            'Practice Set B', 'Chapter 5 Questions', 'Revision Worksheet', 'Chapter 7 Assignment',
            'Unit Test Prep', 'Final Revision Set',
        ];

        foreach ($batchIds as $bIdx => $batchId) {
            foreach ($hwTopics as $hIdx => $topic) {
                DB::table('homeworks')->insert([
                    'batch_id'     => $batchId,
                    'institute_id' => $instituteId,
                    'title'        => $batchData[$bIdx]['subject'] . ' – ' . $topic,
                    'description'  => 'Complete all questions carefully. Show your working.',
                    'due_date'     => Carbon::create(2025, 5, rand(5, 28))->format('Y-m-d'),
                    'attachment'   => null,
                    'created_at'   => Carbon::create(2025, 5, rand(1, 10)),
                    'updated_at'   => now(),
                ]);
            }
        }

        // ── 6. RESOURCES (10 per batch = 100 total) ──────────────────────────
        $this->command->info('Creating 100 resources (10 per batch)...');

        $resourceTitles = [
            'Chapter Notes', 'Formula Sheet', 'Practice Problems', 'Solved Examples',
            'Reference Material', 'Video Lecture Notes', 'Previous Year Paper', 'Quick Revision Guide',
            'Concept Map', 'Summary Sheet',
        ];
        $fileTypes = ['document', 'document', 'document', 'image', 'video'];

        foreach ($batchIds as $bIdx => $batchId) {
            foreach ($resourceTitles as $rIdx => $title) {
                $fileType = $fileTypes[$rIdx % count($fileTypes)];
                $ext      = ['document' => 'pdf', 'image' => 'jpg', 'video' => 'mp4'][$fileType];

                DB::table('resources')->insert([
                    'institute_id' => $instituteId,
                    'batch_id'     => $batchId,
                    'title'        => $batchData[$bIdx]['subject'] . ' – ' . $title,
                    'description'  => $title . ' for ' . $batchData[$bIdx]['subject'],
                    'file_path'    => 'resources/batch_' . $batchId . '_resource_' . ($rIdx + 1) . '.' . $ext,
                    'file_type'    => $fileType,
                    'file_size'    => rand(50, 5000) . 'KB',
                    'created_at'   => Carbon::create(2025, 5, rand(1, 15)),
                    'updated_at'   => now(),
                ]);
            }
        }

        // ── 7. CHATS (~50 students, 3–8 messages each) ───────────────────────
        $this->command->info('Creating ~50 chat conversations...');

        $chatLines = [
            'Hello, how are you doing?',
            'Please submit your homework by tomorrow.',
            'Next class is rescheduled to Friday.',
            'Your fee payment is pending. Please clear it.',
            'Great work on the last test!',
            'Please bring your textbooks to class.',
            'The exam is next week. Be prepared.',
            'Congratulations on your excellent results!',
            'I have a doubt regarding yesterday\'s topic.',
            'When is the next assignment due?',
            'Thank you for sharing the notes!',
            'I missed today\'s class. Can I get the notes?',
            'Can you share the study material for Chapter 5?',
            'Please check the updated schedule.',
            'Good job! Keep it up.',
            'Your attendance is below 75%. Please attend regularly.',
            'The batch timing has been updated.',
            'Please complete the practice set before class.',
            'Test results have been published.',
            'See you in class tomorrow!',
        ];

        $chatStudents = array_slice($studentRows, 0, 50);

        foreach ($chatStudents as $student) {
            $msgCount = rand(3, 8);
            $baseTime = now()->subDays(rand(1, 30));

            for ($m = 0; $m < $msgCount; $m++) {
                $instToStudent = rand(0, 1);
                $msgTime       = $baseTime->copy()->addMinutes($m * rand(5, 60));

                DB::table('chat_messages')->insert([
                    'sender_id'          => $instToStudent ? $instituteId : $student['id'],
                    'sender_type'        => $instToStudent ? 'App\\Models\\Institute' : 'App\\Models\\Student',
                    'receiver_id'        => $instToStudent ? $student['id'] : $instituteId,
                    'receiver_type'      => $instToStudent ? 'App\\Models\\Student' : 'App\\Models\\Institute',
                    'message'            => $chatLines[array_rand($chatLines)],
                    'type'               => 'text',
                    'attachment'         => null,
                    'read_at'            => rand(0, 1) ? $msgTime->copy()->addMinutes(rand(2, 120)) : null,
                    'received_at'        => $msgTime->copy()->addSeconds(rand(1, 10)),
                    'deleted_by_sender'   => false,
                    'deleted_by_receiver' => false,
                    'created_at'         => $msgTime,
                    'updated_at'         => $msgTime,
                ]);
            }
        }

        // ── 8. NOTES (30) ────────────────────────────────────────────────────
        $this->command->info('Creating 30 notes...');

        $catColors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8'];
        $catNames  = ['Academic', 'Finance', 'Events', 'Reminders', 'General'];
        $noteCatIds = [];

        foreach ($catNames as $i => $catName) {
            $noteCatIds[] = DB::table('note_categories')->insertGetId([
                'name'       => $catName,
                'color'      => $catColors[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $noteTitles = [
            'May Fee Collection Summary', 'Batch A Performance Review', 'Annual Day Planning',
            'Student Progress Tracker', 'Parent Meeting Agenda', 'June Exam Schedule',
            'Staff Meeting Minutes – May', 'Q1 Budget Overview', 'New Batch Announcement',
            'Holiday List 2025', 'Academic Calendar 2025–26', 'Sports Day Planning',
            'Library Books Inventory', 'Lab Equipment Checklist', 'Teacher Training Plan',
            'Admission Process Guide', 'Scholarship Details', 'Infrastructure Upgrade Notes',
            'Safety & Emergency Guidelines', 'Emergency Contact List', 'Transport Schedule',
            'IT Room Booking Policy', 'Guest Lecture – June Plan', 'Alumni Meet Notes',
            'Cultural Fest Ideas', 'Science Exhibition Prep', 'Math Olympiad Training',
            'English Speaking Club', 'Career Guidance Session Notes', 'Feedback Summary – May',
        ];

        foreach ($noteTitles as $i => $title) {
            DB::table('notes')->insert([
                'institute_id'  => $instituteId,
                'user_id'       => null,
                'notable_id'    => null,
                'notable_type'  => null,
                'category_id'   => $noteCatIds[$i % count($noteCatIds)],
                'category'      => $catNames[$i % count($catNames)],
                'title'         => $title,
                'slug'          => Str::slug($title) . '-' . Str::random(5),
                'content'       => 'Detailed notes for: ' . $title . '. ' . Str::random(80),
                'cover_image'   => null,
                'is_bookmarked' => $i % 5 === 0,
                'is_archived'   => $i % 10 === 9,
                'created_at'    => now()->subDays(rand(1, 60)),
                'updated_at'    => now(),
            ]);
        }

        // ── 9. STAFF (15 teachers) ───────────────────────────────────────────
        $this->command->info('Creating 15 staff members...');

        // Department & Role
        $deptId = DB::table('staff_departments')->insertGetId([
            'name'       => 'Teaching Faculty',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roleId = DB::table('staff_roles')->insertGetId([
            'name'         => 'Teacher',
            'institute_id' => $instituteId,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $staffNames = [
            ['Priya', 'Sharma'], ['Rahul', 'Verma'], ['Sunita', 'Patel'], ['Anil', 'Gupta'], ['Meena', 'Singh'],
            ['Vijay', 'Kumar'], ['Rekha', 'Joshi'], ['Suresh', 'Shah'], ['Kavita', 'Yadav'], ['Mohan', 'Mishra'],
            ['Lata', 'Dubey'], ['Rajesh', 'Tiwari'], ['Usha', 'Pandey'], ['Dinesh', 'Mehta'], ['Geeta', 'Bhatia'],
        ];

        $staffIds = [];
        foreach ($staffNames as $idx => [$fn, $ln]) {
            $empId = 'INST' . $instituteId . 'EMP' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT) . $runId;

            $staffIds[] = DB::table('staff')->insertGetId([
                'employee_id'         => $empId,
                'full_name'           => $fn . ' ' . $ln,
                'email'               => strtolower($fn . '.' . $ln . '.' . $runId . '@staff.example.com'),
                'phone'               => '9' . rand(100000000, 999999999),
                'staff_role_id'       => $roleId,
                'staff_department_id' => $deptId,
                'employment_type'     => 'Salary',
                'base_salary'         => rand(25000, 55000),
                'status'              => 'active',
                'institute_id'        => $instituteId,
                'created_at'          => now()->subMonths(rand(3, 12)),
                'updated_at'          => now(),
            ]);
        }

        // Staff Attendance – April & May 2025
        $this->command->info('Creating staff attendance for April & May 2025...');

        $staffAttendanceRows = [];
        $staffAttendanceMonths = [
            Carbon::create(2025, 4, 1),
            Carbon::create(2025, 5, 1),
        ];

        foreach ($staffIds as $staffId) {
            foreach ($staffAttendanceMonths as $monthStart) {
                $monthEnd = $monthStart->copy()->endOfMonth();
                $day      = $monthStart->copy();

                while ($day <= $monthEnd) {
                    if (!$day->isWeekend()) {
                        $r      = rand(1, 100);
                        $status = $r <= 80 ? 'Present' : ($r <= 93 ? 'Absent' : 'Half Day');

                        $staffAttendanceRows[] = [
                            'staff_id'     => $staffId,
                            'institute_id' => $instituteId,
                            'date'         => $day->format('Y-m-d'),
                            'status'       => $status,
                            'note'         => null,
                            'created_at'   => $day->copy()->setTime(9, 30),
                            'updated_at'   => $day->copy()->setTime(9, 30),
                        ];
                    }
                    $day->addDay();
                }
            }
        }

        foreach (array_chunk($staffAttendanceRows, 500) as $chunk) {
            DB::table('staff_attendances')->insert($chunk);
        }

        // Staff Salary – 6 months (Dec 2024 – May 2025)
        $this->command->info('Creating staff salaries for 6 months...');

        $salaryPeriods = [
            ['month' => 12, 'year' => 2024, 'date' => '2024-12-31'],
            ['month' => 1,  'year' => 2025, 'date' => '2025-01-31'],
            ['month' => 2,  'year' => 2025, 'date' => '2025-02-28'],
            ['month' => 3,  'year' => 2025, 'date' => '2025-03-31'],
            ['month' => 4,  'year' => 2025, 'date' => '2025-04-30'],
            ['month' => 5,  'year' => 2025, 'date' => '2025-05-31'],
        ];

        foreach ($staffIds as $staffId) {
            $baseSalary = DB::table('staff')->where('id', $staffId)->value('base_salary');

            foreach ($salaryPeriods as $period) {
                $bonus      = rand(0, 1) ? rand(1000, 5000) : 0;
                $deductions = rand(0, 1) ? rand(500, 2000) : 0;
                $netSalary  = $baseSalary + $bonus - $deductions;

                DB::table('staff_salaries')->insert([
                    'staff_id'       => $staffId,
                    'institute_id'   => $instituteId,
                    'month'          => $period['month'],
                    'year'           => $period['year'],
                    'base_salary'    => $baseSalary,
                    'bonus'          => $bonus,
                    'deductions'     => $deductions,
                    'net_salary'     => $netSalary,
                    'payment_date'   => $period['date'],
                    'payment_method' => rand(0, 1) ? 'Bank Transfer' : 'Cash',
                    'notes'          => null,
                    'status'         => 'Paid',
                    'created_at'     => $period['date'],
                    'updated_at'     => $period['date'],
                ]);
            }
        }

        // ── 10. LEADS (50) ────────────────────────────────────────────────────
        $this->command->info('Creating 50 leads...');

        $leadStatuses  = ['New', 'Contacted', 'Converted', 'Lost'];
        $courses       = ['Mathematics', 'Physics', 'Chemistry', 'Biology', 'English', 'Computer Science', 'Economics'];
        $references    = ['Walk-in', 'Friend Referral', 'Social Media', 'Website', 'Flyer', 'Parent Referral', 'Google'];
        $streets       = ['MG Road', 'Station Road', 'Park Street', 'Civil Lines', 'Gandhi Nagar', 'Nehru Marg', 'Lal Bagh'];

        for ($l = 0; $l < 50; $l++) {
            $fn = $firstNames[$l % count($firstNames)];
            $ln = $lastNames[$l % count($lastNames)];

            DB::table('leads')->insert([
                'institute_id'     => $instituteId,
                'full_name'        => $fn . ' ' . $ln,
                'phone'            => '9' . rand(100000000, 999999999),
                'email'            => strtolower($fn . $l . '.' . $runId . '@lead.example.com'),
                'address'          => rand(1, 999) . ' ' . $streets[$l % count($streets)],
                'course_selection' => $courses[$l % count($courses)],
                'reference'        => $references[$l % count($references)],
                'referer'          => null,
                'notes'            => 'Interested in ' . $courses[$l % count($courses)] . ' coaching.',
                'status'           => $leadStatuses[$l % count($leadStatuses)],
                'created_at'       => now()->subDays(rand(0, 150)),
                'updated_at'       => now(),
            ]);
        }

        // ── 11. EXPENSES (20/month × 6 months = 120 total) ───────────────────
        $this->command->info('Creating 120 expenses (20/month × 6 months)...');

        $expCatNames = ['Rent', 'Utilities', 'Stationery', 'Maintenance', 'Marketing', 'Equipment', 'Miscellaneous'];
        $expCatIds   = [];

        foreach ($expCatNames as $catName) {
            $expCatIds[] = DB::table('expense_categories')->insertGetId([
                'institute_id' => $instituteId,
                'name'         => $catName,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        $expenseMonths = [
            Carbon::create(2024, 12, 1),
            Carbon::create(2025, 1, 1),
            Carbon::create(2025, 2, 1),
            Carbon::create(2025, 3, 1),
            Carbon::create(2025, 4, 1),
            Carbon::create(2025, 5, 1),
        ];

        $expenseDescriptions = [
            'Monthly rent payment', 'Electricity bill', 'Water charges', 'Internet & broadband bill',
            'Office stationery', 'Printing & photocopying', 'AC servicing', 'Software license renewal',
            'Cleaning supplies', 'Security guard payment', 'Teaching aids & materials', 'Lab consumables',
            'Sports equipment repair', 'Library book purchase', 'Furniture repair', 'Generator fuel',
            'Social media advertising', 'Flyer & banner printing', 'Website hosting renewal', 'Miscellaneous expenses',
        ];
        $paymentMethods = ['Cash', 'Online', 'Bank Transfer', 'Cheque'];

        foreach ($expenseMonths as $month) {
            for ($e = 0; $e < 20; $e++) {
                $expDate = $month->copy()->addDays(rand(0, 26));

                DB::table('expenses')->insert([
                    'institute_id'        => $instituteId,
                    'expense_category_id' => $expCatIds[$e % count($expCatIds)],
                    'amount'              => rand(500, 15000),
                    'date'                => $expDate->format('Y-m-d'),
                    'description'         => $expenseDescriptions[$e],
                    'receipt_image'       => null,
                    'payment_method'      => $paymentMethods[$e % count($paymentMethods)],
                    'created_at'          => $expDate,
                    'updated_at'          => $expDate,
                ]);
            }
        }

        $this->command->info('✓ InstituteDataSeeder completed successfully!');
        $this->command->table(
            ['Entity', 'Count'],
            [
                ['Batches',               10],
                ['Students (in batches)', 80],
                ['Students (unassigned)', 20],
                ['Attendance records',    count($attendanceChunks)],
                ['Fee records',           80],
                ['Payments',              '≤ 80 (only paid/partial)'],
                ['Homeworks',             100],
                ['Resources',             100],
                ['Chat messages',         '~200-250'],
                ['Notes',                 30],
                ['Staff',                 15],
                ['Staff attendance rows', count($staffAttendanceRows)],
                ['Staff salary records',  90],
                ['Leads',                 50],
                ['Expense categories',    7],
                ['Expenses',              120],
            ]
        );
    }
}
