<?php

namespace Tests\Feature;

use App\Models\Institute;
use App\Models\DeviceSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class DeviceSessionCleanupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function profile_api_prunes_expired_device_sessions()
    {
        // Create institute
        $institute = Institute::create([
            'name' => 'Test Academy',
            'email' => 'test@academy.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Test Academy',
            'address' => '123 Street',
            'city' => 'Surat',
            'state' => 'Gujarat',
            'pincode' => '395007',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create active token and session
        $activeToken = $institute->createToken('access_token', ['access-api'], now()->addHour());
        $activeSession = DeviceSession::create([
            'institute_id' => $institute->id,
            'token_id' => $activeToken->accessToken->id,
            'device' => 'iPhone',
            'os' => 'iOS',
            'last_login' => now(),
            'last_open' => now(),
        ]);

        // Create expired token and session
        $expiredToken = $institute->createToken('access_token', ['access-api'], now()->subHours(2));
        $expiredSession = DeviceSession::create([
            'institute_id' => $institute->id,
            'token_id' => $expiredToken->accessToken->id,
            'device' => 'Android Phone',
            'os' => 'Android',
            'last_login' => now()->subHours(2),
            'last_open' => now()->subHours(2),
        ]);

        // Sanity check: both sessions exist in the DB initially
        $this->assertEquals(2, DeviceSession::count());

        // Call profile API
        $response = $this->withHeader('Authorization', 'Bearer ' . $activeToken->plainTextToken)
            ->getJson('/api/v1/institute/profile');

        $response->assertStatus(200);

        // Verify that only the active session remains
        $this->assertEquals(1, DeviceSession::count());
        $this->assertNull(DeviceSession::find($expiredSession->id));
        $this->assertNotNull(DeviceSession::find($activeSession->id));

        // Verify the profile response active_sessions only contains the active session
        $activeSessionsResponse = $response->json('data.active_sessions');
        $this->assertCount(1, $activeSessionsResponse);
        $this->assertEquals('iPhone', $activeSessionsResponse[0]['device']);
    }

    /** @test */
    public function web_profile_page_prunes_expired_device_sessions()
    {
        // Create institute
        $institute = Institute::create([
            'name' => 'Test Academy',
            'email' => 'test@academy.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Test Academy',
            'address' => '123 Street',
            'city' => 'Surat',
            'state' => 'Gujarat',
            'pincode' => '395007',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create active session
        $activeToken = $institute->createToken('access_token', ['access-api'], now()->addHour());
        $activeSession = DeviceSession::create([
            'institute_id' => $institute->id,
            'token_id' => $activeToken->accessToken->id,
            'device' => 'Chrome on Windows',
            'os' => 'Windows',
            'last_login' => now(),
            'last_open' => now(),
        ]);

        // Create expired session
        $expiredToken = $institute->createToken('access_token', ['access-api'], now()->subHours(2));
        $expiredSession = DeviceSession::create([
            'institute_id' => $institute->id,
            'token_id' => $expiredToken->accessToken->id,
            'device' => 'Safari on macOS',
            'os' => 'macOS',
            'last_login' => now()->subHours(2),
            'last_open' => now()->subHours(2),
        ]);

        // Sanity check: both sessions exist in the DB initially
        $this->assertEquals(2, DeviceSession::count());

        // Authenticate institute via web guard
        $this->actingAs($institute, 'institute');

        // Request the web profile page
        $response = $this->get('/institute/profile');

        $response->assertStatus(200);

        // Verify that the expired session has been pruned
        $this->assertEquals(1, DeviceSession::count());
        $this->assertNull(DeviceSession::find($expiredSession->id));
        $this->assertNotNull(DeviceSession::find($activeSession->id));
    }
}
