<?php

namespace Tests\Feature;

use App\Models\Institute;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentPasswordSentMail;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StudentForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    private $institute;
    private $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->institute = Institute::create([
            'name' => 'Owner',
            'email' => 'inst@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Tuoora Academy',
            'status' => 'active',
        ]);

        $this->student = Student::create([
            'name' => 'Student Test',
            'email' => 'student@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('oldpassword'),
            'institute_id' => $this->institute->id,
            'status' => 'active',
        ]);
    }

    public function test_forgot_password_resets_password_and_emails_successfully(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/student/forgot-password', [
            'email' => 'student@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Your password has been reset successfully and the new password has been sent to your email.',
            ]);

        $this->student->refresh();
        $this->assertFalse(\Illuminate\Support\Facades\Hash::check('oldpassword', $this->student->password));

        Mail::assertSent(StudentPasswordSentMail::class, function ($mail) {
            return $mail->hasTo('student@example.com') 
                && !empty($mail->password)
                && $mail->instituteName === 'Tuoora Academy';
        });
    }

    public function test_forgot_password_validation_fails_for_non_existent_email(): void
    {
        $response = $this->postJson('/api/v1/student/forgot-password', [
            'email' => 'wrongstudent@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
