<?php

namespace Tests\Feature;

use App\Models\Institute;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;
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

    public function test_forgot_password_sends_otp_successfully(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/student/forgot-password', [
            'email' => 'student@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Reset password OTP has been sent successfully to your email.',
            ]);

        $this->student->refresh();
        $this->assertNotNull($this->student->otp);
        $this->assertNotNull($this->student->otp_expires_at);

        Mail::assertSent(ForgotPasswordMail::class, function ($mail) {
            return $mail->hasTo('student@example.com') && !empty($mail->otp);
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

    public function test_reset_password_succeeds_with_correct_otp(): void
    {
        // 1. Generate OTP
        $this->student->update([
            'otp' => '123456',
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // 2. Reset Password
        $response = $this->postJson('/api/v1/student/reset-password', [
            'email' => 'student@example.com',
            'otp' => '123456',
            'password' => 'NewPassword@123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Password reset successfully.',
            ]);

        $this->student->refresh();
        $this->assertNull($this->student->otp);
        $this->assertNull($this->student->otp_expires_at);

        // 3. Try to log in with new password
        $loginResponse = $this->postJson('/api/v1/student/login', [
            'email' => 'student@example.com',
            'password' => 'NewPassword@123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Logged in successfully',
            ]);
    }

    public function test_reset_password_fails_with_incorrect_otp(): void
    {
        $this->student->update([
            'otp' => '123456',
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/v1/student/reset-password', [
            'email' => 'student@example.com',
            'otp' => '654321',
            'password' => 'NewPassword@123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid OTP.',
            ]);
    }

    public function test_reset_password_fails_with_expired_otp(): void
    {
        $this->student->update([
            'otp' => '123456',
            'otp_expires_at' => Carbon::now()->subMinutes(1),
        ]);

        $response = $this->postJson('/api/v1/student/reset-password', [
            'email' => 'student@example.com',
            'otp' => '123456',
            'password' => 'NewPassword@123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => 'OTP has expired.',
            ]);
    }

    public function test_reset_password_fails_with_weak_password(): void
    {
        $this->student->update([
            'otp' => '123456',
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Weak password: no capital letter, no special character, too short
        $response = $this->postJson('/api/v1/student/reset-password', [
            'email' => 'student@example.com',
            'otp' => '123456',
            'password' => 'pass12',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
