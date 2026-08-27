<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Institute;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Batch;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\StaffDepartment;
use App\Models\StaffRole;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffSalary;
use App\Models\Attendance;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\DailyUpdate;
use App\Models\Resource;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\NoteCategory;
use App\Models\Note;
use App\Models\Notification;
use App\Models\InstituteWebsiteContent;

class DemoAccountSeeder extends Seeder
{
    const DEMO_EMAIL = 'demo@tuoora.com';
    const DEMO_PASSWORD = 'password';
    const DEMO_CODE = '100001';

    public function run(): void
    {
        $this->command->info('========================================');
        $this->command->info('🚀 Setting up comprehensive Demo Account');
        $this->command->info('========================================');

        // ── 1. PLAN & SUBSCRIPTION ──────────────────────────────────────────
        $plan = Plan::firstOrCreate(
            ['name' => 'Pro Enterprise Plan'],
            [
                'price' => 9999.00,
                'duration_days' => 365,
                'status' => true,
            ]
        );

        // ── 2. DEMO INSTITUTE ACCOUNT ───────────────────────────────────────
        $institute = Institute::withTrashed()->where('email', self::DEMO_EMAIL)->first();

        if ($institute) {
            if ($institute->trashed()) {
                $institute->restore();
            }
            $institute->update([
                'name' => 'Apex International Academy',
                'institute_name' => 'Apex International Academy',
                'password' => Hash::make(self::DEMO_PASSWORD),
                'phone' => '9876543210',
                'alternate_email' => 'contact@apexacademy.demo',
                'address' => 'Plot 42, Knowledge Park, Central Avenue',
                'address_line_2' => 'Sector 15, Education Hub',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'country' => 'India',
                'pincode' => '400001',
                'website' => 'https://apexacademy.tuoora.com',
                'youtube' => 'https://youtube.com/@apexacademy',
                'instagram' => 'https://instagram.com/apexacademy',
                'status' => 'active',
                'email_verified_at' => now(),
                'upi_id' => 'apexacademy@okhdfcbank',
                'template_id' => 1,
                'register_source' => 'web',
                'institute_code' => self::DEMO_CODE,
            ]);
        } else {
            $institute = Institute::create([
                'name' => 'Apex International Academy',
                'institute_name' => 'Apex International Academy',
                'email' => self::DEMO_EMAIL,
                'password' => Hash::make(self::DEMO_PASSWORD),
                'phone' => '9876543210',
                'alternate_email' => 'contact@apexacademy.demo',
                'address' => 'Plot 42, Knowledge Park, Central Avenue',
                'address_line_2' => 'Sector 15, Education Hub',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'country' => 'India',
                'pincode' => '400001',
                'website' => 'https://apexacademy.tuoora.com',
                'youtube' => 'https://youtube.com/@apexacademy',
                'instagram' => 'https://instagram.com/apexacademy',
                'status' => 'active',
                'email_verified_at' => now(),
                'upi_id' => 'apexacademy@okhdfcbank',
                'template_id' => 1,
                'register_source' => 'web',
                'institute_code' => self::DEMO_CODE,
            ]);
        }

        $instituteId = $institute->id;
        $this->command->info("✓ Demo Institute Ready (ID: {$instituteId}, Email: " . self::DEMO_EMAIL . ")");

        // Active Subscription through 2035
        Subscription::updateOrCreate(
            ['institute_id' => $instituteId],
            [
                'plan_name' => 'Pro Enterprise Plan',
                'amount' => 9999.00,
                'start_date' => Carbon::now()->subMonths(6)->toDateString(),
                'end_date' => Carbon::now()->addYears(5)->toDateString(),
                'status' => 'active',
            ]
        );

        // ── 3. CLEAN UP PREVIOUS DEMO DATA FOR CLEAN SEEDING ────────────────
        $this->command->info('Cleaning previous demo records for this institute...');
        
        $oldStudentIds = DB::table('students')->where('institute_id', $instituteId)->pluck('id')->toArray();
        $oldBatchIds   = DB::table('batches')->where('institute_id', $instituteId)->pluck('id')->toArray();
        $oldStaffIds   = DB::table('staff')->where('institute_id', $instituteId)->pluck('id')->toArray();
        $oldExamIds    = DB::table('exams')->where('institute_id', $instituteId)->pluck('id')->toArray();

        DB::table('timetables')->where('institute_id', $instituteId)->delete();

        if (!empty($oldExamIds)) {
            DB::table('exam_marks')->whereIn('exam_id', $oldExamIds)->delete();
            DB::table('exams')->whereIn('id', $oldExamIds)->delete();
        }

        if (!empty($oldStudentIds)) {
            $oldFeeIds = DB::table('fees')->whereIn('student_id', $oldStudentIds)->pluck('id')->toArray();
            if (!empty($oldFeeIds)) {
                $oldPaymentIds = DB::table('payments')->whereIn('fee_id', $oldFeeIds)->pluck('id')->toArray();
                if (!empty($oldPaymentIds)) {
                    DB::table('receipts')->whereIn('payment_id', $oldPaymentIds)->delete();
                    DB::table('payments')->whereIn('id', $oldPaymentIds)->delete();
                }
                DB::table('fees')->whereIn('id', $oldFeeIds)->delete();
            }
            DB::table('attendance')->whereIn('student_id', $oldStudentIds)->delete();
            DB::table('chat_messages')->where(function ($q) use ($oldStudentIds, $instituteId) {
                $q->whereIn('sender_id', $oldStudentIds)->orWhereIn('receiver_id', $oldStudentIds);
            })->delete();
            DB::table('students')->whereIn('id', $oldStudentIds)->delete();
        }

        if (!empty($oldBatchIds)) {
            DB::table('homework_submissions')->whereIn('homework_id', function ($q) use ($oldBatchIds) {
                $q->select('id')->from('homeworks')->whereIn('batch_id', $oldBatchIds);
            })->delete();
            DB::table('homeworks')->whereIn('batch_id', $oldBatchIds)->delete();
            DB::table('daily_updates')->whereIn('batch_id', $oldBatchIds)->delete();
            DB::table('resources')->whereIn('batch_id', $oldBatchIds)->delete();
            DB::table('batches')->whereIn('id', $oldBatchIds)->delete();
        }

        if (!empty($oldStaffIds)) {
            if (Schema::hasTable('department_staff')) {
                DB::table('department_staff')->whereIn('staff_id', $oldStaffIds)->delete();
            }
            DB::table('staff_attendances')->whereIn('staff_id', $oldStaffIds)->delete();
            DB::table('staff_salaries')->whereIn('staff_id', $oldStaffIds)->delete();
            DB::table('staff')->whereIn('id', $oldStaffIds)->delete();
        }

        $oldLeadIds = DB::table('leads')->where('institute_id', $instituteId)->pluck('id')->toArray();
        if (!empty($oldLeadIds)) {
            DB::table('lead_notes')->whereIn('lead_id', $oldLeadIds)->delete();
            DB::table('leads')->whereIn('id', $oldLeadIds)->delete();
        }

        $oldExpCatIds = DB::table('expense_categories')->where('institute_id', $instituteId)->pluck('id')->toArray();
        if (!empty($oldExpCatIds)) {
            DB::table('expenses')->where('institute_id', $instituteId)->delete();
            DB::table('expense_categories')->where('institute_id', $instituteId)->delete();
        }

        DB::table('notes')->where('institute_id', $instituteId)->delete();
        DB::table('notifications')->where('user_id', $instituteId)->where('user_type', 'institute')->delete();
        DB::table('institute_website_contents')->where('institute_id', $instituteId)->delete();
        DB::table('parents')->where('email', 'like', '%@demo.tuoora.com')->delete();

        // ── 4. WEBSITE CMS CONTENT ──────────────────────────────────────────
        $this->command->info('Creating Website CMS Content...');
        InstituteWebsiteContent::create([
            'institute_id' => $instituteId,
            'hero_slides' => [
                [
                    'title' => 'Empowering Minds, Shaping Futures',
                    'subtitle' => 'Top-tier coaching for Board Exams, JEE, NEET, and Computer Science with proven results.',
                    'image' => null,
                    'button_text' => 'Explore Courses',
                    'button_link' => '#courses',
                ],
                [
                    'title' => 'State-of-the-Art Labs & Dedicated Faculty',
                    'subtitle' => 'Personalized mentorship, interactive study material, and regular performance evaluations.',
                    'image' => null,
                    'button_text' => 'Book Free Demo',
                    'button_link' => '#contact',
                ],
            ],
            'about_vision' => 'To be the premier educational institution recognized for academic excellence, innovation, and holistic student development.',
            'about_mission' => 'Providing affordable, high-quality, and concept-driven education that equips students with skills and confidence to excel in competitive exams and life.',
            'about_values' => 'Integrity, Academic Rigor, Personalized Attention, Innovation, and Continuous Improvement.',
            'achievements' => [
                ['title' => '100% Board Pass Rate', 'description' => 'Consistently achieved 100% results across 10th and 12th Board examinations.'],
                ['title' => '45+ NEET / JEE Selections', 'description' => 'Over 45 students successfully secured ranks in top medical and engineering colleges in 2025.'],
                ['title' => 'Best Institute Award 2025', 'description' => 'Awarded Excellence in Secondary Education by the Regional Educational Council.'],
                ['title' => '1200+ Alumni Network', 'description' => 'A thriving alumni base pursuing careers across leading tech companies, hospitals, and universities.'],
            ],
            'gallery' => [
                ['title' => 'Advanced Science & Chemistry Lab', 'image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=800&auto=format&fit=crop'],
                ['title' => 'Smart Interactive Classroom', 'image' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&auto=format&fit=crop'],
                ['title' => 'Annual Science & Tech Fair', 'image' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&auto=format&fit=crop'],
                ['title' => 'Library & Quiet Study Space', 'image' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=800&auto=format&fit=crop'],
            ],
            'events' => [
                [
                    'title' => 'National Science Olympiad Prep Workshop',
                    'date' => Carbon::now()->addDays(12)->format('d M, Y'),
                    'time' => '10:00 AM - 01:00 PM',
                    'description' => 'Intensive problem-solving and concept strengthening session for Class 9 & 10 aspirants.',
                ],
                [
                    'title' => 'Career Guidance & College Admissions Seminar',
                    'date' => Carbon::now()->addDays(20)->format('d M, Y'),
                    'time' => '04:00 PM - 06:30 PM',
                    'description' => 'Expert guidance on competitive exam roadmaps, subject selection, and college counseling.',
                ],
            ],
            'facebook' => 'https://facebook.com/apexacademy',
            'twitter' => 'https://twitter.com/apexacademy',
            'linkedin' => 'https://linkedin.com/company/apexacademy',
            'instagram' => 'https://instagram.com/apexacademy',
            'youtube' => 'https://youtube.com/@apexacademy',
        ]);

        // ── 5. STAFF DEPARTMENTS & ROLES ────────────────────────────────────
        $this->command->info('Creating Staff Departments & Roles...');

        $deptNames = ['Mathematics', 'Science & Technology', 'Commerce & Economics', 'Languages & Humanities', 'Administration & Support'];
        $depts = [];
        foreach ($deptNames as $dName) {
            $depts[$dName] = StaffDepartment::firstOrCreate(['name' => $dName]);
        }

        $roleNames = ['Department Head', 'Senior Faculty', 'Associate Lecturer', 'Lab Instructor', 'Academic Counselor', 'Office Manager'];
        $roles = [];
        foreach ($roleNames as $rName) {
            $roles[$rName] = StaffRole::firstOrCreate(['name' => $rName]);
        }

        // 8 Staff Members
        $staffData = [
            ['name' => 'Dr. Rajesh Sharma', 'email' => 'rajesh.sharma@apex.demo', 'phone' => '9820112233', 'dept' => 'Mathematics', 'role' => 'Department Head', 'salary' => 75000],
            ['name' => 'Prof. Sunita Mehta', 'email' => 'sunita.mehta@apex.demo', 'phone' => '9820223344', 'dept' => 'Science & Technology', 'role' => 'Senior Faculty', 'salary' => 65000],
            ['name' => 'Vikram Singhania', 'email' => 'vikram.singhania@apex.demo', 'phone' => '9820334455', 'dept' => 'Science & Technology', 'role' => 'Senior Faculty', 'salary' => 62000],
            ['name' => 'Dr. Priya Desai', 'email' => 'priya.desai@apex.demo', 'phone' => '9820445566', 'dept' => 'Science & Technology', 'role' => 'Associate Lecturer', 'salary' => 55000],
            ['name' => 'Amitabh Sen', 'email' => 'amitabh.sen@apex.demo', 'phone' => '9820556677', 'dept' => 'Commerce & Economics', 'role' => 'Department Head', 'salary' => 68000],
            ['name' => 'Meenakshi Iyer', 'email' => 'meenakshi.iyer@apex.demo', 'phone' => '9820667788', 'dept' => 'Languages & Humanities', 'role' => 'Senior Faculty', 'salary' => 50000],
            ['name' => 'Nikhil Kulkarni', 'email' => 'nikhil.kulkarni@apex.demo', 'phone' => '9820778899', 'dept' => 'Science & Technology', 'role' => 'Lab Instructor', 'salary' => 42000],
            ['name' => 'Pooja Bhatt', 'email' => 'pooja.bhatt@apex.demo', 'phone' => '9820889900', 'dept' => 'Administration & Support', 'role' => 'Office Manager', 'salary' => 45000],
        ];

        $staffModels = [];
        $staffDatePrefix = Carbon::now()->subMonths(10)->format('Ymd');
        foreach ($staffData as $idx => $s) {
            $createdStaffMember = Staff::create([
                'institute_id' => $instituteId,
                'employee_id' => $staffDatePrefix . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                'full_name' => $s['name'],
                'email' => $s['email'],
                'password' => Hash::make(self::DEMO_PASSWORD),
                'phone' => $s['phone'],
                'staff_role_id' => $roles[$s['role']]->id,
                'staff_department_id' => $depts[$s['dept']]->id,
                'employment_type' => 'Salary',
                'base_salary' => $s['salary'],
                'status' => 'active',
                'created_at' => Carbon::now()->subMonths(10),
            ]);
            $staffModels[] = $createdStaffMember;

            if (Schema::hasTable('department_staff')) {
                DB::table('department_staff')->insert([
                    'staff_id' => $createdStaffMember->id,
                    'staff_department_id' => $depts[$s['dept']]->id,
                    'created_at' => Carbon::now()->subMonths(10),
                    'updated_at' => Carbon::now()->subMonths(10),
                ]);
            }
        }

        // Staff Attendance (Past 30 days)
        $staffAttendances = [];
        foreach ($staffModels as $staff) {
            for ($d = 30; $d >= 0; $d--) {
                $date = Carbon::now()->subDays($d);
                if (!$date->isSunday()) {
                    $rand = rand(1, 100);
                    $status = $rand <= 88 ? 'Present' : ($rand <= 95 ? 'Half Day' : 'Absent');
                    $staffAttendances[] = [
                        'staff_id' => $staff->id,
                        'institute_id' => $instituteId,
                        'date' => $date->toDateString(),
                        'status' => $status,
                        'note' => $status === 'Absent' ? 'Personal leave approved' : null,
                        'created_at' => $date->copy()->setTime(9, 0),
                        'updated_at' => $date->copy()->setTime(9, 0),
                    ];
                }
            }
        }
        foreach (array_chunk($staffAttendances, 250) as $chunk) {
            DB::table('staff_attendances')->insert($chunk);
        }

        // Staff Salaries (Past 4 months)
        for ($m = 4; $m >= 1; $m--) {
            $salaryDate = Carbon::now()->subMonths($m)->endOfMonth();
            foreach ($staffModels as $staff) {
                $bonus = rand(0, 1) ? rand(1000, 3000) : 0;
                $deductions = rand(0, 1) ? rand(500, 1500) : 0;
                StaffSalary::create([
                    'staff_id' => $staff->id,
                    'institute_id' => $instituteId,
                    'month' => $salaryDate->month,
                    'year' => $salaryDate->year,
                    'base_salary' => $staff->base_salary,
                    'bonus' => $bonus,
                    'deductions' => $deductions,
                    'net_salary' => $staff->base_salary + $bonus - $deductions,
                    'payment_date' => $salaryDate->toDateString(),
                    'payment_method' => 'Bank Transfer',
                    'notes' => 'Salary transferred via NEFT Ref: NEFT' . rand(10000000, 99999999),
                    'status' => 'Paid',
                    'created_at' => $salaryDate,
                    'updated_at' => $salaryDate,
                ]);
            }
        }

        // ── 6. BATCHES ──────────────────────────────────────────────────────
        $this->command->info('Creating Batches...');

        $batchDefs = [
            [
                'name' => 'Class 10 - Mathematics (Morning)',
                'subject' => 'Mathematics',
                'description' => 'Complete coverage of NCERT & exemplar mathematics with weekly mock tests.',
                'fees' => 3500,
                'start_time' => '08:00:00',
                'end_time' => '09:30:00',
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'classroom' => 'Room 101',
                'staff_id' => $staffModels[0]->id,
            ],
            [
                'name' => 'Class 10 - Science & Physics',
                'subject' => 'Science',
                'description' => 'Physics, Chemistry & Biology foundational concepts with laboratory demonstrations.',
                'fees' => 3500,
                'start_time' => '09:45:00',
                'end_time' => '11:15:00',
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'classroom' => 'Room 102',
                'staff_id' => $staffModels[1]->id,
            ],
            [
                'name' => 'Class 12 - Advanced Chemistry',
                'subject' => 'Chemistry',
                'description' => 'Organic, Inorganic & Physical Chemistry with specialized entrance prep.',
                'fees' => 4500,
                'start_time' => '11:30:00',
                'end_time' => '13:00:00',
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'classroom' => 'Chemistry Lab 2',
                'staff_id' => $staffModels[2]->id,
            ],
            [
                'name' => 'Class 12 - Biology & NEET Prep',
                'subject' => 'Biology',
                'description' => 'Detailed botany & zoology curriculum tailored for NEET & Board excellence.',
                'fees' => 5000,
                'start_time' => '14:00:00',
                'end_time' => '15:30:00',
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'classroom' => 'Room 202',
                'staff_id' => $staffModels[3]->id,
            ],
            [
                'name' => 'Computer Science & Python Coding',
                'subject' => 'Computer Science',
                'description' => 'Python programming, data structures, SQL databases, and project building.',
                'fees' => 4000,
                'start_time' => '16:00:00',
                'end_time' => '17:30:00',
                'days' => ['Monday', 'Wednesday', 'Friday'],
                'classroom' => 'IT Lab 1',
                'staff_id' => $staffModels[6]->id,
            ],
            [
                'name' => 'Class 11 - Accountancy & Commerce',
                'subject' => 'Commerce',
                'description' => 'Financial accounting, ledger management, trial balance & business fundamentals.',
                'fees' => 3800,
                'start_time' => '08:30:00',
                'end_time' => '10:00:00',
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'classroom' => 'Room 203',
                'staff_id' => $staffModels[4]->id,
            ],
            [
                'name' => 'Class 11 - Economics & Business Studies',
                'subject' => 'Economics',
                'description' => 'Microeconomics, statistics, and business case studies with real-world applications.',
                'fees' => 3200,
                'start_time' => '10:15:00',
                'end_time' => '11:45:00',
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'classroom' => 'Room 204',
                'staff_id' => $staffModels[4]->id,
            ],
            [
                'name' => 'Spoken English & Communication Skills',
                'subject' => 'English',
                'description' => 'Fluency development, public speaking, grammar refinement, and personality growth.',
                'fees' => 2500,
                'start_time' => '17:45:00',
                'end_time' => '19:00:00',
                'days' => ['Tuesday', 'Thursday', 'Saturday'],
                'classroom' => 'Language Hall',
                'staff_id' => $staffModels[5]->id,
            ],
        ];

        $batches = [];
        foreach ($batchDefs as $b) {
            $batches[] = Batch::create([
                'institute_id' => $instituteId,
                'name' => $b['name'],
                'subject' => $b['subject'],
                'description' => $b['description'],
                'fees' => $b['fees'],
                'start_time' => $b['start_time'],
                'end_time' => $b['end_time'],
                'days' => $b['days'],
                'classroom' => $b['classroom'],
                'staff_id' => $b['staff_id'],
                'status' => 'active',
            ]);
        }

        // ── 7. STUDENTS & PARENTS (40 in batches + 5 unassigned) ─────────────
        $this->command->info('Creating 45 Students & Parents...');

        $firstNames = [
            'Aarav', 'Ananya', 'Rohan', 'Diya', 'Vihaan', 'Saanvi', 'Kabir', 'Kritika', 'Arjun', 'Myra',
            'Ishaan', 'Shreya', 'Dhruv', 'Riya', 'Aditya', 'Pooja', 'Vivaan', 'Neha', 'Reyansh', 'Tanvi',
            'Atharv', 'Isha', 'Advaith', 'Sanya', 'Shaurya', 'Ankita', 'Nikhil', 'Muskan', 'Vikram', 'Divya',
            'Pranav', 'Preeti', 'Dev', 'Swati', 'Parth', 'Pallavi', 'Karan', 'Komal', 'Yash', 'Sakshi',
            'Harsh', 'Rekha', 'Sumit', 'Sonam', 'Abhishek'
        ];
        $lastNames = [
            'Sharma', 'Patel', 'Verma', 'Gupta', 'Mehta', 'Singh', 'Joshi', 'Shah', 'Yadav', 'Mishra',
            'Dubey', 'Tiwari', 'Pandey', 'Soni', 'Agarwal', 'Bhatia', 'Chauhan', 'Dixit', 'Garg', 'Kumar'
        ];

        $students = [];
        $enrollmentYear = date('Y');

        for ($i = 0; $i < 45; $i++) {
            $fn = $firstNames[$i % count($firstNames)];
            $ln = $lastNames[$i % count($lastNames)];
            $batch = $i < 40 ? $batches[$i % count($batches)] : null;
            $batchFee = $batch ? $batch->fees : 3000;
            $standard = $batch ? (str_contains($batch->name, '12') ? '12' : (str_contains($batch->name, '11') ? '11' : '10')) : '10';

            // Create Parent
            $parentEmail = strtolower('parent.' . $fn . '.' . $ln . ($i + 1) . '@demo.tuoora.com');
            $parent = StudentParent::updateOrCreate(
                ['email' => $parentEmail],
                [
                    'name' => 'Mr. ' . $fn . ' ' . $ln,
                    'phone' => '98' . rand(10000000, 99999999),
                    'password' => Hash::make(self::DEMO_PASSWORD),
                    'relation' => 'Father',
                    'status' => 'active',
                    'city' => 'Mumbai',
                ]
            );

            $enrollmentId = $enrollmentYear . self::DEMO_CODE . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            $student = Student::create([
                'enrollment_id' => $enrollmentId,
                'id_hash' => Str::random(16),
                'name' => $fn . ' ' . $ln,
                'email' => strtolower($fn . '.' . $ln . ($i + 1) . '@student.tuoora.com'),
                'phone' => '97' . rand(10000000, 99999999),
                'password' => Hash::make(self::DEMO_PASSWORD),
                'institute_id' => $instituteId,
                'parent_id' => $parent->id,
                'batch_id' => $batch ? $batch->id : null,
                'standard' => $standard,
                'dob' => Carbon::now()->subYears(15 + ($standard == '12' ? 2 : ($standard == '11' ? 1 : 0)))->subDays(rand(10, 300))->toDateString(),
                'guardian_name' => $parent->name,
                'monthly_fee' => $batchFee,
                'status' => 1,
                'address_line_1' => 'Flat ' . rand(101, 804) . ', ' . ['Gokul Residency', 'Skyline Towers', 'Harmony Heights', 'Sai Enclave'][$i % 4],
                'address_line_2' => ['Andheri West', 'Bandra', 'Borivali', 'Dadar', 'Powai'][$i % 5],
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'country' => 'India',
                'pincode' => '4000' . rand(10, 99),
                'created_at' => Carbon::now()->subMonths(rand(2, 6)),
            ]);

            $students[] = $student;
        }

        // ── 8. STUDENT ATTENDANCE (Past 25 days) ─────────────────────────────
        $this->command->info('Creating Student Attendance records...');

        $studentAttendance = [];
        foreach ($students as $student) {
            if (!$student->batch_id) continue;

            for ($d = 25; $d >= 0; $d--) {
                $date = Carbon::now()->subDays($d);
                if (!$date->isSunday()) {
                    $rand = rand(1, 100);
                    $status = $rand <= 80 ? 'present' : ($rand <= 90 ? 'absent' : ($rand <= 96 ? 'late' : 'leave'));
                    $studentAttendance[] = [
                        'student_id' => $student->id,
                        'batch_id' => $student->batch_id,
                        'date' => $date->toDateString(),
                        'status' => $status,
                        'marked_by' => 'institute',
                        'created_at' => $date->copy()->setTime(8, 30),
                        'updated_at' => $date->copy()->setTime(8, 30),
                    ];
                }
            }
        }
        foreach (array_chunk($studentAttendance, 400) as $chunk) {
            DB::table('attendance')->insert($chunk);
        }

        // ── 9. FEES, PAYMENTS & RECEIPTS (Past 3 months) ────────────────────
        $this->command->info('Creating Fees, Payments & Receipts...');

        $monthsToSeed = [
            Carbon::now()->subMonths(2)->startOfMonth(),
            Carbon::now()->subMonths(1)->startOfMonth(),
            Carbon::now()->startOfMonth(),
        ];

        foreach ($students as $idx => $student) {
            $monthlyFee = $student->monthly_fee ?: 3500;

            foreach ($monthsToSeed as $mIdx => $mDate) {
                // Determine payment status
                // Older months: 90% paid, current month: 70% paid, 20% partial, 10% pending
                $isCurrentMonth = ($mIdx === count($monthsToSeed) - 1);
                $rand = rand(1, 100);

                if (!$isCurrentMonth) {
                    $paidAmount = $rand <= 90 ? $monthlyFee : ($rand <= 97 ? round($monthlyFee * 0.5) : 0);
                } else {
                    $paidAmount = $rand <= 65 ? $monthlyFee : ($rand <= 85 ? round($monthlyFee * 0.5) : 0);
                }

                $status = ($paidAmount >= $monthlyFee) ? 'Paid' : (($paidAmount > 0) ? 'Partial' : 'Pending');
                $feeDate = $mDate->copy()->addDays(rand(1, 10));

                $fee = Fee::create([
                    'student_id' => $student->id,
                    'institute_id' => $instituteId,
                    'total_amount' => $monthlyFee,
                    'paid_amount' => $paidAmount,
                    'status' => $status,
                    'date' => $feeDate->toDateString(),
                    'created_at' => $feeDate,
                    'updated_at' => $feeDate,
                ]);

                if ($paidAmount > 0) {
                    $method = rand(0, 1) ? 'Online' : 'Cash';
                    $payDate = $feeDate->copy()->addDays(rand(0, 5));

                    $payment = Payment::create([
                        'fee_id' => $fee->id,
                        'student_id' => $student->id,
                        'amount' => $paidAmount,
                        'payment_method' => $method,
                        'transaction_id' => 'TXN' . strtoupper(Str::random(10)),
                        'paid_at' => $payDate,
                        'created_at' => $payDate,
                        'updated_at' => $payDate,
                    ]);

                    Receipt::create([
                        'payment_id' => $payment->id,
                        'receipt_number' => 'REC-' . $payDate->format('Ym') . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT),
                        'file_url' => null,
                        'created_at' => $payDate,
                        'updated_at' => $payDate,
                    ]);
                }
            }
        }

        // ── 10. EXAMS & EXAM MARKS ──────────────────────────────────────────
        $this->command->info('Creating Exams & Marks...');

        $examDefs = [
            [
                'batch' => $batches[0],
                'title' => 'Unit Test 1: Real Numbers & Polynomials',
                'subject' => 'Mathematics',
                'exam_date' => Carbon::now()->subDays(18)->toDateString(),
                'start_time' => '08:00:00',
                'end_time' => '09:30:00',
                'total_marks' => 50.00,
                'passing_marks' => 18.00,
                'status' => 'completed',
            ],
            [
                'batch' => $batches[1],
                'title' => 'Mid-Term Physics & Chemistry Assessment',
                'subject' => 'Science',
                'exam_date' => Carbon::now()->subDays(10)->toDateString(),
                'start_time' => '09:45:00',
                'end_time' => '11:45:00',
                'total_marks' => 80.00,
                'passing_marks' => 28.00,
                'status' => 'completed',
            ],
            [
                'batch' => $batches[2],
                'title' => 'Organic Reactions & Mechanism Test',
                'subject' => 'Chemistry',
                'exam_date' => Carbon::now()->subDays(8)->toDateString(),
                'start_time' => '11:30:00',
                'end_time' => '13:00:00',
                'total_marks' => 50.00,
                'passing_marks' => 20.00,
                'status' => 'completed',
            ],
            [
                'batch' => $batches[3],
                'title' => 'NEET Biology Mock Test Series 1',
                'subject' => 'Biology',
                'exam_date' => Carbon::now()->subDays(4)->toDateString(),
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
                'total_marks' => 100.00,
                'passing_marks' => 40.00,
                'status' => 'completed',
            ],
            [
                'batch' => $batches[4],
                'title' => 'Python Programming & Logic Practical',
                'subject' => 'Computer Science',
                'exam_date' => Carbon::now()->addDays(5)->toDateString(),
                'start_time' => '16:00:00',
                'end_time' => '17:30:00',
                'total_marks' => 50.00,
                'passing_marks' => 20.00,
                'status' => 'scheduled',
            ],
            [
                'batch' => $batches[5],
                'title' => 'Financial Accounting Mid-Term Exam',
                'subject' => 'Commerce',
                'exam_date' => Carbon::now()->subDays(6)->toDateString(),
                'start_time' => '08:30:00',
                'end_time' => '10:30:00',
                'total_marks' => 80.00,
                'passing_marks' => 32.00,
                'status' => 'completed',
            ],
            [
                'batch' => $batches[6],
                'title' => 'Microeconomics & Market Structures Test',
                'subject' => 'Economics',
                'exam_date' => Carbon::now()->addDays(3)->toDateString(),
                'start_time' => '10:15:00',
                'end_time' => '11:45:00',
                'total_marks' => 50.00,
                'passing_marks' => 20.00,
                'status' => 'scheduled',
            ],
            [
                'batch' => $batches[7],
                'title' => 'Spoken English & Presentation Evaluation',
                'subject' => 'English',
                'exam_date' => Carbon::now()->subDays(2)->toDateString(),
                'start_time' => '17:45:00',
                'end_time' => '19:00:00',
                'total_marks' => 50.00,
                'passing_marks' => 20.00,
                'status' => 'completed',
            ],
        ];

        foreach ($examDefs as $e) {
            $exam = Exam::create([
                'institute_id' => $instituteId,
                'batch_id' => $e['batch']->id,
                'title' => $e['title'],
                'subject' => $e['subject'],
                'exam_date' => $e['exam_date'],
                'start_time' => $e['start_time'],
                'end_time' => $e['end_time'],
                'total_marks' => $e['total_marks'],
                'passing_marks' => $e['passing_marks'],
                'status' => $e['status'],
                'description' => 'Mandatory test for assessment and progress reports.',
            ]);

            if ($e['status'] === 'completed') {
                $batchStudents = Student::where('batch_id', $e['batch']->id)->get();
                foreach ($batchStudents as $sIdx => $bStudent) {
                    $isAbsent = ($sIdx === 0 && rand(0, 1));
                    $marks = $isAbsent ? null : rand((int)($e['total_marks'] * 0.5), (int)($e['total_marks'] * 0.96));
                    $remarks = $isAbsent ? 'Absent with prior permission' : ($marks > ($e['total_marks'] * 0.8) ? 'Outstanding performance!' : 'Good effort, work on numericals.');

                    ExamMark::create([
                        'exam_id' => $exam->id,
                        'student_id' => $bStudent->id,
                        'marks_obtained' => $marks,
                        'is_absent' => $isAbsent,
                        'remarks' => $remarks,
                    ]);
                }
            }
        }

        // ── 11. HOMEWORKS & ASSIGNMENTS ─────────────────────────────────────
        $this->command->info('Creating Homeworks & Assignments with Submissions...');

        $hwList = [
            ['batch' => $batches[0], 'title' => 'Quadratic Equations Exercise 4.2', 'desc' => 'Solve Q1 to Q10 with step-by-step factorization working.', 'due' => Carbon::now()->addDays(2)],
            ['batch' => $batches[0], 'title' => 'Arithmetic Progression Worksheet', 'desc' => 'Complete the nth-term worksheet distributed in class.', 'due' => Carbon::now()->subDays(3)],
            ['batch' => $batches[1], 'title' => 'Ray Optics: Lens Maker Formula', 'desc' => 'Derive lens formula and solve 5 sample numericals.', 'due' => Carbon::now()->addDays(3)],
            ['batch' => $batches[1], 'title' => 'Electromagnetism Practice Questions', 'desc' => 'Complete Faraday laws numerical assignment.', 'due' => Carbon::now()->subDays(4)],
            ['batch' => $batches[2], 'title' => 'Alkyl Halides Reaction Mechanisms', 'desc' => 'Draw SN1 vs SN2 reaction pathways and comparison table.', 'due' => Carbon::now()->addDays(1)],
            ['batch' => $batches[3], 'title' => 'Cell Division & Mitosis Diagrams', 'desc' => 'Draw neat labeled diagrams of all phases of mitosis.', 'due' => Carbon::now()->subDays(5)],
            ['batch' => $batches[4], 'title' => 'Python Dictionary & List Comprehensions', 'desc' => 'Implement 4 coding exercises in Google Colab / Jupyter.', 'due' => Carbon::now()->addDays(4)],
            ['batch' => $batches[4], 'title' => 'Object Oriented Class System Project', 'desc' => 'Create a Student Management class model with methods.', 'due' => Carbon::now()->subDays(2)],
            ['batch' => $batches[5], 'title' => 'Bank Reconciliation Statement Problems', 'desc' => 'Solve illustration problems 7 to 12 from textbook.', 'due' => Carbon::now()->subDays(2)],
            ['batch' => $batches[6], 'title' => 'Elasticity of Demand Calculation Sheet', 'desc' => 'Solve numerical questions on price and income elasticity.', 'due' => Carbon::now()->subDays(3)],
            ['batch' => $batches[7], 'title' => 'Prepared Speech on AI in Education', 'desc' => 'Write a 300-word persuasive speech for upcoming debate session.', 'due' => Carbon::now()->subDays(1)],
        ];

        foreach ($hwList as $hw) {
            $createdHw = Homework::create([
                'institute_id' => $instituteId,
                'batch_id' => $hw['batch']->id,
                'title' => $hw['title'],
                'description' => $hw['desc'],
                'due_date' => $hw['due']->toDateString(),
                'created_at' => $hw['due']->copy()->subDays(4),
            ]);

            if ($hw['due'] < Carbon::now()) {
                $bStudents = Student::where('batch_id', $hw['batch']->id)->get();
                foreach ($bStudents as $sIdx => $st) {
                    $isSubmitted = ($sIdx !== 0 || rand(0, 1));
                    if ($isSubmitted) {
                        $subDate = $hw['due']->copy()->subDays(rand(0, 2));
                        HomeworkSubmission::create([
                            'homework_id' => $createdHw->id,
                            'student_id' => $st->id,
                            'submission_date' => $subDate,
                            'file_path' => 'homework_submissions/sample_assignment_' . $st->id . '.pdf',
                            'notes' => 'Submitted assignment solutions for review.',
                            'score' => rand(8, 10),
                            'created_at' => $subDate,
                        ]);
                    }
                }
            }
        }

        // ── 12. DAILY UPDATES / LECTURE LOGS ────────────────────────────────
        $this->command->info('Creating Daily Class Updates...');

        $updateTopics = [
            ['batch' => $batches[0], 'topic' => 'Trigonometric Identities & Proofs', 'desc' => 'Covered fundamental identities sin²θ + cos²θ = 1 and 1 + tan²θ = sec²θ. Assigned homework exercises.'],
            ['batch' => $batches[1], 'topic' => 'Electromagnetic Induction & Faraday Laws', 'desc' => 'Demonstrated induction with coil experiment. Solved numericals on magnetic flux change.'],
            ['batch' => $batches[2], 'topic' => 'Chemical Kinetics - Rate of Reaction', 'desc' => 'Explained zero order vs first order integrated rate laws with graphs.'],
            ['batch' => $batches[3], 'topic' => 'Mendelian Genetics & Dihybrid Cross', 'desc' => 'Discussed law of independent assortment with Punnett square examples.'],
            ['batch' => $batches[4], 'topic' => 'Object Oriented Programming in Python', 'desc' => 'Classes, Objects, __init__ constructor, and inheritance with practical code.'],
            ['batch' => $batches[5], 'topic' => 'Depreciation - Straight Line vs WDV Method', 'desc' => 'Comparative calculation of asset valuation over 5 year lifespan.'],
            ['batch' => $batches[6], 'topic' => 'National Income & Circular Flow of Money', 'desc' => 'Two-sector and three-sector macroeconomic circular flow models.'],
            ['batch' => $batches[7], 'topic' => 'Effective Body Language & Public Speaking', 'desc' => 'Interactive speech workshop with individual feedback.'],
        ];

        foreach ($updateTopics as $uIdx => $up) {
            for ($d = 0; $d < 3; $d++) {
                $uDate = Carbon::now()->subDays($uIdx * 2 + $d);
                DailyUpdate::create([
                    'institute_id' => $instituteId,
                    'batch_id' => $up['batch']->id,
                    'topic' => $up['topic'] . ($d > 0 ? ' (Session ' . ($d + 1) . ')' : ''),
                    'description' => $up['desc'],
                    'date' => $uDate->toDateString(),
                    'created_at' => $uDate->copy()->setTime(15, 30),
                ]);
            }
        }

        // ── 13. STUDY RESOURCES & MATERIALS ─────────────────────────────────
        $this->command->info('Creating Study Resources...');

        $resources = [
            ['batch' => $batches[0], 'title' => 'Class 10 Math Formula Handbook & Quick Reference', 'type' => 'document', 'ext' => 'pdf', 'size' => '2.4 MB'],
            ['batch' => $batches[0], 'title' => 'Previous 10 Years Solved Board Papers (Mathematics)', 'type' => 'document', 'ext' => 'pdf', 'size' => '8.1 MB'],
            ['batch' => $batches[1], 'title' => 'Physics Electricity & Magnetism Concept Map', 'type' => 'image', 'ext' => 'jpg', 'size' => '1.2 MB'],
            ['batch' => $batches[2], 'title' => 'Organic Chemistry Reagents & Conversions Chart', 'type' => 'document', 'ext' => 'pdf', 'size' => '3.5 MB'],
            ['batch' => $batches[3], 'title' => 'Human Physiology NEET High-Yield Notes', 'type' => 'document', 'ext' => 'pdf', 'size' => '4.8 MB'],
            ['batch' => $batches[4], 'title' => 'Python Cheat Sheet & Common Algorithm Snippets', 'type' => 'document', 'ext' => 'pdf', 'size' => '1.1 MB'],
            ['batch' => $batches[5], 'title' => 'Financial Accounting Formats & Golden Rules Guide', 'type' => 'document', 'ext' => 'pdf', 'size' => '1.8 MB'],
            ['batch' => $batches[6], 'title' => 'Macroeconomics Key Graphs & Definition Booklet', 'type' => 'document', 'ext' => 'pdf', 'size' => '2.2 MB'],
            ['batch' => $batches[7], 'title' => 'Idioms, Phrases & Vocabulary Mastery Guide', 'type' => 'document', 'ext' => 'pdf', 'size' => '1.5 MB'],
        ];

        foreach ($resources as $res) {
            Resource::create([
                'institute_id' => $instituteId,
                'batch_id' => $res['batch']->id,
                'title' => $res['title'],
                'description' => 'Comprehensive study guide and practice material for students.',
                'file_path' => 'resources/' . Str::slug($res['title']) . '.' . $res['ext'],
                'file_type' => $res['type'],
                'file_size' => $res['size'],
                'created_at' => Carbon::now()->subDays(rand(5, 30)),
            ]);
        }

        // ── 14. INQUIRIES & LEADS PIPELINE ──────────────────────────────────
        $this->command->info('Creating 20 Leads & Inquiries...');

        $leadNames = [
            ['Tanmay', 'Bhosale'], ['Sneha', 'Kadam'], ['Kunal', 'Deshmukh'], ['Akash', 'Patil'], ['Meera', 'Chavan'],
            ['Aditi', 'Rane'], ['Sameer', 'Khan'], ['Harshita', 'Gawade'], ['Naveen', 'Nair'], ['Zoya', 'Shaikh'],
            ['Gaurav', 'Shinde'], ['Rashmi', 'Sawant'], ['Prateek', 'More'], ['Bhavna', 'Thakur'], ['Tejas', 'Jadhav'],
            ['Anuj', 'Trivedi'], ['Deepika', 'Kulkarni'], ['Varun', 'Naik'], ['Simran', 'Kaur'], ['Manish', 'Pawar']
        ];
        $leadCourses = ['Class 10 Board Prep', 'Class 12 NEET Biology', 'Class 12 Advanced Chemistry', 'Computer Science Python', 'Class 11 Commerce'];
        $leadSources = ['Google Search', 'Walk-in Inquiry', 'Friend Referral', 'Social Media / Instagram', 'Flyer / Hoarding'];
        $leadStages = ['New', 'Contacted', 'Converted', 'Lost'];

        foreach ($leadNames as $lIdx => [$fn, $ln]) {
            $course = $leadCourses[$lIdx % count($leadCourses)];
            $status = $leadStages[$lIdx % count($leadStages)];
            $leadDate = Carbon::now()->subDays(rand(1, 45));

            $lead = Lead::create([
                'institute_id' => $instituteId,
                'full_name' => $fn . ' ' . $ln,
                'phone' => '96' . rand(10000000, 99999999),
                'email' => strtolower($fn . '.' . $ln . '@lead.demo'),
                'address' => rand(10, 500) . ', MG Road, Mumbai',
                'course_selection' => $course,
                'reference' => $leadSources[$lIdx % count($leadSources)],
                'notes' => 'Inquired for ' . $course . '. Interested in weekend/evening batches.',
                'status' => $status,
                'created_at' => $leadDate,
                'updated_at' => $leadDate,
            ]);

            LeadNote::create([
                'lead_id' => $lead->id,
                'institute_id' => $instituteId,
                'title' => $status === 'Converted' ? 'Admission Confirmed' : ($status === 'Contacted' ? 'Brochure Sent on WhatsApp' : 'Initial Inquiry Follow-up'),
                'note' => 'Counselor called the student/parent. ' . ($status === 'Converted' ? 'Confirmed admission!' : ($status === 'Contacted' ? 'Sent brochure on WhatsApp.' : 'Awaiting confirmation.')),
                'created_at' => $leadDate->copy()->addHours(2),
            ]);
        }

        // ── 15. EXPENSES TRACKER (Past 4 months) ────────────────────────────
        $this->command->info('Creating Categorized Expenses...');

        $expCategories = [
            'Facility Rent' => ['Monthly Building Lease', 'Classroom 2 Maintenance Deposit'],
            'Electricity & Utilities' => ['Monthly Commercial Electricity Bill', 'Water Utility Bill', 'High-Speed Broadband Internet'],
            'Office & Stationery' => ['Exam Answer Sheet Printing', 'Office Printer Cartridges & Paper', 'Student ID Cards & Lanyards', 'Whiteboard Markers & Dusters'],
            'Marketing & Promotion' => ['Social Media Campaign Ads', 'Local Newspaper Insert Flyers', 'Hoarding Banner Printing'],
            'Lab & Teaching Equipment' => ['Chemistry Lab Glassware & Reagents', 'Physics Optical Benches & Mirrors', 'Computer Lab UPS Replacement'],
            'Repairs & Maintenance' => ['AC Servicing & Cleaning', 'Water Purifier Filter Replacement', 'Electrical Fittings & LED Lights'],
        ];

        $expCatModels = [];
        foreach (array_keys($expCategories) as $catName) {
            $expCatModels[$catName] = ExpenseCategory::create([
                'institute_id' => $instituteId,
                'name' => $catName,
            ]);
        }

        for ($m = 3; $m >= 0; $m--) {
            $monthDate = Carbon::now()->subMonths($m)->startOfMonth();
            foreach ($expCategories as $catName => $items) {
                foreach ($items as $itemTitle) {
                    $expDate = $monthDate->copy()->addDays(rand(1, 26));
                    $amount = match($catName) {
                        'Facility Rent' => 45000,
                        'Electricity & Utilities' => rand(6000, 12000),
                        'Office & Stationery' => rand(1500, 4500),
                        'Marketing & Promotion' => rand(3000, 8000),
                        'Lab & Teaching Equipment' => rand(2500, 9000),
                        'Repairs & Maintenance' => rand(1200, 5000),
                        default => rand(1000, 5000)
                    };

                    Expense::create([
                        'institute_id' => $instituteId,
                        'expense_category_id' => $expCatModels[$catName]->id,
                        'amount' => $amount,
                        'date' => $expDate->toDateString(),
                        'description' => $itemTitle,
                        'payment_method' => ['Online Transfer', 'UPI', 'Cheque', 'Cash'][rand(0, 3)],
                        'created_at' => $expDate,
                        'updated_at' => $expDate,
                    ]);
                }
            }
        }

        // ── 16. NOTES & WORKSPACES ──────────────────────────────────────────
        $this->command->info('Creating Notes & Workspaces...');

        $noteCategories = [
            'Academic Planning' => '#4ECDC4',
            'Administrative' => '#45B7D1',
            'Finance & Fees' => '#FFA07A',
            'Events & Activities' => '#FF6B6B',
        ];

        $noteCatModels = [];
        foreach ($noteCategories as $name => $color) {
            $noteCatModels[$name] = NoteCategory::create([
                'name' => $name,
                'color' => $color,
            ]);
        }

        $noteItems = [
            ['cat' => 'Academic Planning', 'title' => 'Annual Academic Calendar & Term Exam Schedules', 'content' => "Plan for academic year 2026-27:\n1. Term 1 exams in September\n2. Diwali holiday break: Oct 28 - Nov 5\n3. Pre-Board mock series in December & January\n4. Doubt-clearing crash course starting February."],
            ['cat' => 'Finance & Fees', 'title' => 'Fee Collection Protocol & Grace Period Guidelines', 'content' => "Monthly tuition fees are due on the 5th of each month. Automated SMS/WhatsApp reminders sent on the 1st and 7th. Grace period allowed until the 10th without late fees."],
            ['cat' => 'Administrative', 'title' => 'Staff Monthly Meeting & Faculty Review Agenda', 'content' => "Agenda for upcoming faculty meeting:\n- Review syllabus completion per batch\n- Student attendance tracking & parent follow-ups\n- Science lab safety protocols and inventory replenishment."],
            ['cat' => 'Events & Activities', 'title' => 'Annual Science & Tech Fair Organization Checklist', 'content' => "1. Reserve Auditorium & Display Halls\n2. Invite guest judges from Tech Universities\n3. Arrange certificates, trophies & refreshment vouchers\n4. Send invitation circulars to parents."],
        ];

        foreach ($noteItems as $n) {
            Note::create([
                'institute_id' => $instituteId,
                'category_id' => $noteCatModels[$n['cat']]->id,
                'category' => $n['cat'],
                'title' => $n['title'],
                'slug' => Str::slug($n['title']) . '-' . Str::random(5),
                'content' => $n['content'],
                'is_bookmarked' => true,
                'created_at' => Carbon::now()->subDays(rand(2, 30)),
            ]);
        }

        // ── 17. NOTIFICATIONS & BROADCASTS ──────────────────────────────────
        $this->command->info('Creating Notifications...');

        $notifs = [
            ['title' => 'Upcoming Mid-Term Examination Schedule Published', 'msg' => 'The complete timetable for Mid-Term Exams has been published. Please review your batch exam dates.', 'type' => 'announcement'],
            ['title' => 'Holiday Notice: Independence Day Celebration', 'msg' => 'Institute will host flag hoisting ceremony at 8:30 AM on August 15. Regular classes resume the following day.', 'type' => 'general'],
            ['title' => 'Fee Payment Reminder for Current Month', 'msg' => 'Gentle reminder to clear any outstanding tuition dues by the 10th of this month to avoid late fee penalties.', 'type' => 'fee_reminder'],
            ['title' => 'Parent-Teacher Meeting (PTM) Scheduled', 'msg' => 'PTM for Class 10 and 12 batches is scheduled for this Saturday from 10 AM to 2 PM.', 'type' => 'announcement'],
        ];

        foreach ($notifs as $idx => $notif) {
            Notification::create([
                'user_id' => $instituteId,
                'user_type' => 'institute',
                'title' => $notif['title'],
                'message' => $notif['msg'],
                'type' => $notif['type'],
                'is_read' => ($idx > 1),
                'created_at' => Carbon::now()->subDays($idx * 3 + 1),
            ]);
        }

        // ── 18. CHAT MESSAGES ───────────────────────────────────────────────
        $this->command->info('Creating Chat Conversations...');

        $sampleChatStudents = array_slice($students, 0, 8);
        $conversations = [
            [
                ['from' => 'student', 'text' => 'Hello Sir, I have a doubt regarding question 5 in the trigonometry exercise.'],
                ['from' => 'institute', 'text' => 'Hello Aarav! Question 5 uses the tan(A+B) identity. Check the formula handbook uploaded in resources, or stop by 15 mins before tomorrow’s class.'],
                ['from' => 'student', 'text' => 'Understood Sir, thank you! I will come early tomorrow.'],
            ],
            [
                ['from' => 'institute', 'text' => 'Dear Ananya, congratulations on scoring the highest marks (48/50) in the Math Unit Test! Keep up the excellent effort.'],
                ['from' => 'student', 'text' => 'Thank you so much Sir! Really appreciate your guidance.'],
            ],
            [
                ['from' => 'student', 'text' => 'Sir, could you please share the recording or notes for yesterday’s Chemistry class? I was unwell.'],
                ['from' => 'institute', 'text' => 'Sure Rohan. The concept notes have been uploaded to the Class 12 Chemistry resources tab. Get well soon!'],
            ],
        ];

        foreach ($sampleChatStudents as $cIdx => $cStudent) {
            $convo = $conversations[$cIdx % count($conversations)];
            $baseTime = Carbon::now()->subDays(rand(1, 10))->setTime(11, 0);

            foreach ($convo as $mIdx => $msg) {
                $isFromStudent = ($msg['from'] === 'student');
                $msgTime = $baseTime->copy()->addMinutes($mIdx * 15);

                DB::table('chat_messages')->insert([
                    'sender_id' => $isFromStudent ? $cStudent->id : $instituteId,
                    'sender_type' => $isFromStudent ? 'App\\Models\\Student' : 'App\\Models\\Institute',
                    'receiver_id' => $isFromStudent ? $instituteId : $cStudent->id,
                    'receiver_type' => $isFromStudent ? 'App\\Models\\Institute' : 'App\\Models\\Student',
                    'message' => $msg['text'],
                    'type' => 'text',
                    'attachment' => null,
                    'read_at' => $msgTime->copy()->addMinutes(5),
                    'received_at' => $msgTime->copy()->addSeconds(2),
                    'deleted_by_sender' => false,
                    'deleted_by_receiver' => false,
                    'created_at' => $msgTime,
                    'updated_at' => $msgTime,
                ]);
            }
        }

        // ── 19. TIMETABLE & DAILY CLASS SCHEDULE ───────────────────────────
        $this->command->info('Creating Timetable & Daily Schedule...');

        foreach ($batches as $bIdx => $batch) {
            $assignedStaff = $batch->staff_id ? Staff::find($batch->staff_id) : (!empty($staffModels) ? $staffModels[$bIdx % count($staffModels)] : null);
            $batchDays = !empty($batch->days) ? $batch->days : ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $startTime = $batch->getRawOriginal('start_time') ?: '09:00:00';
            $endTime = $batch->getRawOriginal('end_time') ?: '10:30:00';

            foreach ($batchDays as $day) {
                \App\Models\Timetable::updateOrCreate(
                    [
                        'institute_id' => $instituteId,
                        'batch_id' => $batch->id,
                        'day_of_week' => strtolower($day),
                        'start_time' => $startTime,
                    ],
                    [
                        'staff_id' => $assignedStaff ? $assignedStaff->id : null,
                        'subject' => $batch->subject ?: 'General Session',
                        'end_time' => $endTime,
                        'room_no' => $batch->classroom ?: 'Room ' . (101 + $bIdx),
                        'description' => 'Regular lecture for ' . $batch->name,
                        'status' => 'active',
                    ]
                );
            }
        }

        $this->command->info('========================================');
        $this->command->info('🎉 DEMO ACCOUNT READY FOR SHOWCASE!');
        $this->command->info('========================================');
        $this->command->info('📧 Login Email    : ' . self::DEMO_EMAIL);
        $this->command->info('🔑 Password       : ' . self::DEMO_PASSWORD);
        $this->command->info('🏫 Institute Code : ' . self::DEMO_CODE);
        $this->command->info('🌐 Public Website : /' . self::DEMO_CODE . '/apex-international-academy');
        $this->command->info('========================================');
    }
}
