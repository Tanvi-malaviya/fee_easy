<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Institute;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\StudentPasswordSentMail;

class StudentPasswordManagementWebTest extends TestCase
{
    use RefreshDatabase;

    protected $institute;
    protected $student;
    protected $otherInstitute;
    protected $otherStudent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->institute = Institute::create([
            'name' => 'Adani Owner',
            'email' => 'adani@test.com',
            'phone' => '9876543210',
            'password' => bcrypt('password123'),
            'institute_name' => 'Adani Academy',
            'status' => 'active',
            'address' => '123 Main St',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'country' => 'India',
            'pincode' => '380001',
            'email_verified_at' => now(),
        ]);

        // Create an active subscription for institute
        \App\Models\Subscription::create([
            'institute_id' => $this->institute->id,
            'plan_name' => 'Premium Plan',
            'amount' => 5000,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(360),
            'status' => 'active'
        ]);

        $this->student = Student::create([
            'name' => 'Vidhi',
            'email' => 'vidhisathwara77@gmail.com',
            'phone' => '9910256145',
            'password' => bcrypt('OldPassword123#'),
            'institute_id' => $this->institute->id,
            'status' => 1,
        ]);

        $this->otherInstitute = Institute::create([
            'name' => 'Other Owner',
            'email' => 'other@test.com',
            'phone' => '9876543211',
            'password' => bcrypt('password123'),
            'institute_name' => 'Other Academy',
            'status' => 'active',
            'address' => '456 Other St',
            'city' => 'Surat',
            'state' => 'Gujarat',
            'country' => 'India',
            'pincode' => '395007',
            'email_verified_at' => now(),
        ]);

        // Create an active subscription for otherInstitute
        \App\Models\Subscription::create([
            'institute_id' => $this->otherInstitute->id,
            'plan_name' => 'Premium Plan',
            'amount' => 5000,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(360),
            'status' => 'active'
        ]);

        $this->otherStudent = Student::create([
            'name' => 'Other Student',
            'email' => 'otherstudent@gmail.com',
            'phone' => '9910256146',
            'password' => bcrypt('OldPassword123#'),
            'institute_id' => $this->otherInstitute->id,
            'status' => 1,
        ]);
    }

    /** @test */
    public function institute_can_generate_and_send_student_password_email()
    {
        Mail::fake();

        $response = $this->actingAs($this->institute, 'institute')
            ->post(route('institute.students.send_password', $this->student->id));

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Password has been generated and sent to student email successfully!'
        ]);

        // Verify that the password was changed in DB
        $this->student->refresh();
        $this->assertFalse(Hash::check('OldPassword123#', $this->student->password));

        // Verify mail was sent
        Mail::assertSent(StudentPasswordSentMail::class, function ($mail) {
            return $mail->hasTo('vidhisathwara77@gmail.com') &&
                   $mail->studentName === 'Vidhi' &&
                   $mail->studentEmail === 'vidhisathwara77@gmail.com' &&
                   strlen($mail->password) >= 8;
        });
    }

    /** @test */
    public function institute_cannot_send_password_email_for_other_institute_student()
    {
        Mail::fake();

        $response = $this->actingAs($this->institute, 'institute')
            ->post(route('institute.students.send_password', $this->otherStudent->id));

        $response->assertStatus(403);
        Mail::assertNothingSent();
    }

    /** @test */
    public function institute_can_directly_reset_student_password_with_valid_criteria()
    {
        $response = $this->actingAs($this->institute, 'institute')
            ->post(route('institute.students.reset_password_direct', $this->student->id), [
                'password' => 'NewPass987#',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Student password has been reset successfully!'
        ]);

        // Verify password is changed and correct
        $this->student->refresh();
        $this->assertTrue(Hash::check('NewPass987#', $this->student->password));
    }

    /** @test */
    public function institute_cannot_reset_student_password_with_invalid_criteria()
    {
        // Case 1: Too short
        $response = $this->actingAs($this->institute, 'institute')
            ->post(route('institute.students.reset_password_direct', $this->student->id), [
                'password' => 'Short1#',
            ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);

        // Case 2: No uppercase
        $response = $this->actingAs($this->institute, 'institute')
            ->post(route('institute.students.reset_password_direct', $this->student->id), [
                'password' => 'nouppercase123#',
            ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);

        // Case 3: No special char
        $response = $this->actingAs($this->institute, 'institute')
            ->post(route('institute.students.reset_password_direct', $this->student->id), [
                'password' => 'NoSpecialChar123',
            ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }
}
