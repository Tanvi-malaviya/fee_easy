<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Institute;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstituteStatusManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createInstitute($status)
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
            'status' => $status,
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
     * Test active institute can access dashboard.
     */
    public function test_active_institute_can_access_dashboard()
    {
        $institute = $this->createInstitute('active');

        $response = $this->actingAs($institute, 'institute')
                         ->get(route('institute.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Students'); // Ensure we see dashboard modules
        $response->assertDontSee('Institute Account Blocked');
    }

    /**
     * Test blocked institute is redirected to dashboard when accessing other pages.
     */
    public function test_blocked_institute_redirects_to_dashboard()
    {
        $institute = $this->createInstitute('blocked');

        $response = $this->actingAs($institute, 'institute')
                         ->get(route('institute.students.index'));

        $response->assertRedirect(route('institute.dashboard'));
    }

    /**
     * Test blocked institute dashboard shows blocked message.
     */
    public function test_blocked_institute_shows_blocked_message_on_dashboard()
    {
        $institute = $this->createInstitute('blocked');

        $response = $this->actingAs($institute, 'institute')
                         ->get(route('institute.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Institute Account Blocked');
        $response->assertDontSee('Students'); // Ensure module links are hidden
    }

    /**
     * Test API requests receive 403 when institute is blocked.
     */
    public function test_blocked_institute_api_requests_receive_403()
    {
        $institute = $this->createInstitute('blocked');

        $response = $this->actingAs($institute, 'sanctum')
                         ->getJson('/api/v1/institute/students');

        $response->assertStatus(403);
        $response->assertJson([
            'status' => 'blocked',
            'message' => 'Your institute account is currently marked as blocked. Please contact the administrator or support to activate your account.'
        ]);
    }

    /**
     * Test Admin status validation allows only active and blocked.
     */
    public function test_admin_status_validation()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@feeeasy.com',
            'password' => bcrypt('password'),
        ]);

        $institute = $this->createInstitute('active');

        // 1. Update status to blocked (Should pass)
        $response = $this->actingAs($admin)
                         ->post(route('institutes.status', $institute), [
                             'status' => 'blocked'
                         ]);

        $response->assertRedirect();
        $this->assertEquals('blocked', $institute->fresh()->status);

        // 2. Update status to inactive (Should fail validation)
        $response = $this->actingAs($admin)
                         ->post(route('institutes.status', $institute), [
                             'status' => 'inactive'
                         ]);

        $response->assertSessionHasErrors('status');

        // 3. Update status to suspended (Should fail validation)
        $response = $this->actingAs($admin)
                         ->post(route('institutes.status', $institute), [
                             'status' => 'suspended'
                         ]);

        $response->assertSessionHasErrors('status');
    }
}
