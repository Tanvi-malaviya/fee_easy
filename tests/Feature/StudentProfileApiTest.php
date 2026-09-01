<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Institute;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentProfileApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Freeze time to a controlled date: Friday, June 19, 2026
        Carbon::setTestNow(Carbon::create(2026, 6, 19, 12, 0, 0));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_mid_month_enrollment_does_not_penalize_attendance_score(): void
    {
        // 1. Create Institute
        $institute = Institute::create([
            'name' => 'Test Owner',
            'email' => 'testinst@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Alpha Academy',
            'institute_code' => 'ALP',
            'status' => 1,
        ]);

        // 2. Create Batch (Mon, Wed, Fri)
        $batch = Batch::create([
            'institute_id' => $institute->id,
            'name' => 'Math Batch',
            'subject' => 'Math',
            'days' => ['Monday', 'Wednesday', 'Friday'],
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ]);

        // 3. Create Student enrolled on Monday, June 15, 2026
        $student = Student::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_id' => $institute->id,
            'batch_id' => $batch->id,
            'status' => 1,
        ]);
        $student->created_at = Carbon::create(2026, 6, 15, 9, 0, 0);
        $student->save();

        // Prior to June 15, 2026:
        // June 1 (Mon), June 3 (Wed), June 5 (Fri), June 8 (Mon), June 10 (Wed), June 12 (Fri)
        // These past batch days should NOT count as absents because student was enrolled on June 15.
        
        // Let's mark student present on June 15 (Mon) and June 17 (Wed)
        Attendance::create([
            'student_id' => $student->id,
            'batch_id' => $batch->id,
            'date' => '2026-06-15',
            'status' => 'present',
        ]);

        Attendance::create([
            'student_id' => $student->id,
            'batch_id' => $batch->id,
            'date' => '2026-06-17',
            'status' => 'present',
        ]);

        // For June 19 (Fri) - today - attendance is not marked yet. It should not count as absent in StudentProfileController or StudentAttendanceController.
        
        // Assert: 100% attendance rate since they were present for all class days they were enrolled in
        $response = $this->actingAs($student, 'sanctum')
            ->getJson('/api/v1/student/profile');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals(100, $data['stats']['attendance_pct']);
    }

    public function test_leave_status_affects_attendance_score(): void
    {
        // 1. Create Institute
        $institute = Institute::create([
            'name' => 'Test Owner',
            'email' => 'testinst@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Alpha Academy',
            'institute_code' => 'ALP',
            'status' => 1,
        ]);

        // 2. Create Batch (Mon, Wed, Fri)
        $batch = Batch::create([
            'institute_id' => $institute->id,
            'name' => 'Math Batch',
            'subject' => 'Math',
            'days' => ['Monday', 'Wednesday', 'Friday'],
        ]);

        // 3. Create Student enrolled at start of month
        $student = Student::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_id' => $institute->id,
            'batch_id' => $batch->id,
            'status' => 1,
        ]);
        $student->created_at = Carbon::create(2026, 6, 1, 9, 0, 0);
        $student->save();

        // Mark 1 present and 1 leave
        Attendance::create([
            'student_id' => $student->id,
            'batch_id' => $batch->id,
            'date' => '2026-06-15',
            'status' => 'present',
        ]);

        Attendance::create([
            'student_id' => $student->id,
            'batch_id' => $batch->id,
            'date' => '2026-06-17',
            'status' => 'leave',
        ]);

        // Others in June (before today 19th) will be marked virtual absent.
        // Total active days for Mon, Wed, Fri from June 1 to June 19:
        // June 1 (Mon), June 3 (Wed), June 5 (Fri), June 8 (Mon), June 10 (Wed), June 12 (Fri), June 15 (Mon), June 17 (Wed)
        // Today is June 19 (Fri) - today is excluded from virtual absent calculation (since currentDate->isPast() && !$currentDate->isToday())
        // So total class days in past = 8 days.
        // 1 present, 1 leave, 6 virtual absent.
        // Denominator = 8. Numerator = 1 (present).
        // Attendance pct = round((1 / 8) * 100) = 13%
        
        $response = $this->actingAs($student, 'sanctum')
            ->getJson('/api/v1/student/profile');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals(13, $data['stats']['attendance_pct']);
    }

    public function test_performance_score_is_calculated_and_returned_in_stats(): void
    {
        // 1. Create Institute
        $institute = Institute::create([
            'name' => 'Test Owner',
            'email' => 'testinst@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Alpha Academy',
            'institute_code' => 'ALP',
            'status' => 1,
        ]);

        // 2. Create Batch
        $batch = Batch::create([
            'institute_id' => $institute->id,
            'name' => 'Math Batch',
            'subject' => 'Math',
            'days' => ['Monday'],
        ]);

        // 3. Create Student
        $student = Student::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_id' => $institute->id,
            'batch_id' => $batch->id,
            'status' => 1,
        ]);

        // 4. Create Homeworks and Submissions
        $homework1 = Homework::create([
            'institute_id' => $institute->id,
            'batch_id' => $batch->id,
            'title' => 'HW 1',
            'due_date' => '2026-06-10',
        ]);

        $homework2 = Homework::create([
            'institute_id' => $institute->id,
            'batch_id' => $batch->id,
            'title' => 'HW 2',
            'due_date' => '2026-06-12',
        ]);

        // Scores are out of 10: 8 and 9. Average is 8.5. Since it's <= 10, it scales to 85.
        HomeworkSubmission::create([
            'homework_id' => $homework1->id,
            'student_id' => $student->id,
            'status' => 'Graded',
            'score' => 8.0,
        ]);

        HomeworkSubmission::create([
            'homework_id' => $homework2->id,
            'student_id' => $student->id,
            'status' => 'Graded',
            'score' => 9.0,
        ]);

        $response = $this->actingAs($student, 'sanctum')
            ->getJson('/api/v1/student/profile');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals(85, $data['stats']['performance_score']);
        $this->assertIsInt($data['stats']['performance_score']);
    }

    public function test_student_can_change_password_with_valid_criteria(): void
    {
        $institute = Institute::create([
            'name' => 'Test Owner',
            'email' => 'testinst@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Alpha Academy',
            'status' => 1,
        ]);

        $student = Student::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('OldPassword123#'),
            'institute_id' => $institute->id,
            'status' => 1,
        ]);

        $response = $this->actingAs($student, 'sanctum')
            ->postJson('/api/v1/student/profile/change-password', [
                'current_password' => 'OldPassword123#',
                'new_password' => 'NewPassword987$',
                'new_password_confirmation' => 'NewPassword987$',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Password changed successfully.'
        ]);

        $student->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('NewPassword987$', $student->password));
    }

    public function test_student_cannot_change_password_with_incorrect_current_password(): void
    {
        $institute = Institute::create([
            'name' => 'Test Owner',
            'email' => 'testinst@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Alpha Academy',
            'status' => 1,
        ]);

        $student = Student::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('OldPassword123#'),
            'institute_id' => $institute->id,
            'status' => 1,
        ]);

        $response = $this->actingAs($student, 'sanctum')
            ->postJson('/api/v1/student/profile/change-password', [
                'current_password' => 'WrongPassword123#',
                'new_password' => 'NewPassword987$',
                'new_password_confirmation' => 'NewPassword987$',
            ]);

        $response->assertStatus(400);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Current password does not match.'
        ]);
    }

    public function test_student_cannot_change_password_with_same_password(): void
    {
        $institute = Institute::create([
            'name' => 'Test Owner',
            'email' => 'testinst@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Alpha Academy',
            'status' => 1,
        ]);

        $student = Student::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('OldPassword123#'),
            'institute_id' => $institute->id,
            'status' => 1,
        ]);

        $response = $this->actingAs($student, 'sanctum')
            ->postJson('/api/v1/student/profile/change-password', [
                'current_password' => 'OldPassword123#',
                'new_password' => 'OldPassword123#',
                'new_password_confirmation' => 'OldPassword123#',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'message' => 'New password cannot be the same as current password.'
        ]);
    }

    public function test_student_cannot_change_password_with_invalid_criteria(): void
    {
        $institute = Institute::create([
            'name' => 'Test Owner',
            'email' => 'testinst@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Alpha Academy',
            'status' => 1,
        ]);

        $student = Student::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('OldPassword123#'),
            'institute_id' => $institute->id,
            'status' => 1,
        ]);

        // Case 1: Too short
        $response = $this->actingAs($student, 'sanctum')
            ->postJson('/api/v1/student/profile/change-password', [
                'current_password' => 'OldPassword123#',
                'new_password' => 'Short1#',
                'new_password_confirmation' => 'Short1#',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['new_password']);

        // Case 2: No uppercase
        $response = $this->actingAs($student, 'sanctum')
            ->postJson('/api/v1/student/profile/change-password', [
                'current_password' => 'OldPassword123#',
                'new_password' => 'nouppercase123#',
                'new_password_confirmation' => 'nouppercase123#',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['new_password']);

        // Case 3: No special char
        $response = $this->actingAs($student, 'sanctum')
            ->postJson('/api/v1/student/profile/change-password', [
                'current_password' => 'OldPassword123#',
                'new_password' => 'NoSpecialChar123',
                'new_password_confirmation' => 'NoSpecialChar123',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['new_password']);
    }
}
