<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Institute;
use App\Models\Staff;
use App\Models\StaffDepartment;
use App\Models\StaffRole;
use App\Mail\StaffAddedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaffCreationMailTest extends TestCase
{
    use RefreshDatabase;

    private function createInstitute()
    {
        $institute = Institute::create([
            'name' => 'Test Academy',
            'email' => 'test@academy.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Test Academy',
            'address' => '456 Lane',
            'city' => 'Surat',
            'state' => 'Gujarat',
            'country' => 'India',
            'pincode' => '395007',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        \App\Models\Subscription::create([
            'institute_id' => $institute->id,
            'plan_name' => 'Free Plan',
            'amount' => 0,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'active',
        ]);

        return $institute;
    }

    /**
     * Test web staff creation triggers mail.
     */
    public function test_web_staff_creation_sends_email()
    {
        Mail::fake();

        $institute = $this->createInstitute();
        $dept = StaffDepartment::create(['name' => 'IT Dept']);
        $role = StaffRole::create(['name' => 'Developer', 'institute_id' => $institute->id]);

        $response = $this->actingAs($institute, 'institute')
                         ->post(route('institute.staff.store'), [
                             'full_name' => 'Staff Member',
                             'email' => 'staff@member.com',
                             'phone' => '9876543210',
                             'staff_role_id' => $role->id,
                             'staff_department_id' => $dept->id,
                             'employment_type' => 'Salary',
                             'base_salary' => 50000,
                         ]);

        $response->assertRedirect();
        
        // Assert that the email was sent to staff member
        Mail::assertSent(StaffAddedMail::class, function ($mail) {
            return $mail->hasTo('staff@member.com') &&
                   $mail->staffName === 'Staff Member' &&
                   $mail->roleName === 'Developer' &&
                   $mail->departmentName === 'IT Dept';
        });
    }

    /**
     * Test API staff creation triggers mail.
     */
    public function test_api_staff_creation_sends_email()
    {
        Mail::fake();

        $institute = $this->createInstitute();
        $dept = StaffDepartment::create(['name' => 'Finance Dept']);
        $role = StaffRole::create(['name' => 'Accountant', 'institute_id' => $institute->id]);

        $response = $this->actingAs($institute, 'sanctum')
                         ->postJson('/api/v1/institute/staff', [
                             'full_name' => 'Finance Member',
                             'email' => 'finance@member.com',
                             'phone' => '9876543210',
                             'staff_role_id' => $role->id,
                             'staff_department_id' => $dept->id,
                             'employment_type' => 'Salary',
                             'base_salary' => 60000,
                         ]);

        $response->assertStatus(201);
        
        // Assert that the email was sent to staff member
        Mail::assertSent(StaffAddedMail::class, function ($mail) {
            return $mail->hasTo('finance@member.com') &&
                   $mail->staffName === 'Finance Member' &&
                   $mail->roleName === 'Accountant' &&
                   $mail->departmentName === 'Finance Dept';
        });
    }
}
