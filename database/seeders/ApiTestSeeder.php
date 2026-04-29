<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Plan;
use App\Models\Institute;
use App\Models\Batch;
use App\Models\StudentParent;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Attendance;
use App\Models\DailyUpdate;
use App\Models\Homework;
use App\Models\Notification;
use App\Models\InstituteWhatsappSetting;

class ApiTestSeeder extends Seeder
{
    public function run()
    {
        // 1. Plan
        $plan = Plan::firstOrCreate(
            ['name' => 'Premium API Test Plan'],
            [
                'price' => 500,
                'duration_days' => 365,
                'trial_days' => 14,
                'status' => true
            ]
        );

        // 2. Institute
        $institute = Institute::firstOrCreate(
            ['email' => 'institute@test.com'],
            [
                'name' => 'Test Institute Admin',
                'phone' => '1111111111',
                'password' => Hash::make('password123'),
                'institute_name' => 'Aakash Academy API Test',
                'address' => '123 Test Street',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380001',
                'status' => 'active'
            ]
        );

        // 3. Subscription
        $subscription = Subscription::firstOrCreate(
            ['institute_id' => $institute->id],
            [
                'plan_name' => $plan->name,
                'amount' => $plan->price,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(365)->toDateString(),
                'status' => 'active'
            ]
        );

        // 4. Batch
        $batch = Batch::firstOrCreate(
            ['institute_id' => $institute->id, 'name' => 'Class X - Sci'],
            [
                'subject' => 'Science',
                'start_time' => '10:00:00',
                'end_time' => '11:00:00'
            ]
        );

        // 5. Parent
        $parent = StudentParent::firstOrCreate(
            ['email' => 'parent@test.com'],
            [
                'name' => 'Test Parent',
                'phone' => '2222222222',
                'password' => Hash::make('password123'),
                'relation' => 'Father',
                'status' => 'active'
            ]
        );

        // 6. Student
        $student = Student::firstOrCreate(
            ['email' => 'student@test.com'],
            [
                'name' => 'Test Student',
                'phone' => '3333333333',
                'password' => Hash::make('password123'),
                'institute_id' => $institute->id,
                'parent_id' => $parent->id,
                'batch_id' => $batch->id,
                'standard' => '10th',
                'status' => 'active'
            ]
        );

        // 7. Fee
        $fee = Fee::firstOrCreate(
            ['student_id' => $student->id, 'date' => now()->toDateString()],
            [
                'institute_id' => $institute->id,
                'total_amount' => 1000,
                'paid_amount' => 500,
                'status' => 'partial'
            ]
        );

        // 8. Payment
        $payment = Payment::firstOrCreate(
            ['fee_id' => $fee->id],
            [
                'student_id' => $student->id,
                'amount' => 500,
                'payment_method' => 'cash',
                'transaction_id' => 'TXN' . time(),
                'paid_at' => now()
            ]
        );

        // 9. Receipt
        $receipt = Receipt::firstOrCreate(
            ['payment_id' => $payment->id],
            [
                'receipt_number' => 'REC-API-001',
                'file_url' => '/receipts/test-rec.pdf'
            ]
        );

        // 10. Attendance
        $attendance = Attendance::firstOrCreate(
            ['student_id' => $student->id, 'date' => now()->toDateString()],
            [
                'batch_id' => $batch->id,
                'status' => 'present',
                'marked_by' => 'institute'
            ]
        );

        // 11. Daily Update
        $dailyUpdate = DailyUpdate::firstOrCreate(
            ['batch_id' => $batch->id, 'date' => now()->toDateString()],
            [
                'institute_id' => $institute->id,
                'topic' => 'Gravity Basics',
                'description' => 'Studied Newton laws of gravitation.'
            ]
        );

        // 12. Homework
        $homework = Homework::firstOrCreate(
            ['batch_id' => $batch->id, 'due_date' => now()->addDays(2)->toDateString()],
            [
                'institute_id' => $institute->id,
                'title' => 'Physics Ch-1 Exercises',
                'description' => 'Complete problems 1 to 10.'
            ]
        );

        // 13. Notification
        $notification = Notification::firstOrCreate(
            ['user_id' => $student->id, 'user_type' => 'student'],
            [
                'title' => 'Welcome to Fee Easy',
                'message' => 'API Test Notification',
                'type' => 'system',
                'is_read' => false
            ]
        );

        // 14. Whatsapp Setting
        $wa = InstituteWhatsappSetting::firstOrCreate(
            ['institute_id' => $institute->id],
            [
                'phone_number' => '1234567890',
                'access_token' => 'TEST_TOKEN_API',
                'phone_number_id' => 'PID123',
                'business_account_id' => 'BID123',
                'is_active' => true
            ]
        );

        $this->command->info('API Test Data Seeded Successfully!');
    }
}
