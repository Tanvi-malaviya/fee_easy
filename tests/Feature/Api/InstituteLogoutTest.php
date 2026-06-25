<?php

namespace Tests\Feature\Api;

use App\Models\Institute;
use App\Models\DeviceSession;
use App\Models\Subscription;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstituteLogoutTest extends TestCase
{
    use RefreshDatabase;

    private function createInstitute(): Institute
    {
        $institute = Institute::create([
            'name' => 'Test Institute',
            'email' => 'institute@example.com',
            'phone' => '9999999999',
            'password' => \Hash::make('password'),
            'institute_name' => 'Test Institute Academy',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        Subscription::create([
            'institute_id' => $institute->id,
            'plan_name' => 'Premium',
            'amount' => 1000,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(30),
            'status' => 'active',
        ]);

        return $institute;
    }

    public function test_institute_logout_via_api_terminates_device_session_by_token(): void
    {
        $institute = $this->createInstitute();

        // Create Sanctum Token
        $tokenResult = $institute->createToken('access_token', ['access-api']);
        $token = $tokenResult->plainTextToken;
        $tokenId = $tokenResult->accessToken->id;

        // Create a Device Session linked to that token
        $session = DeviceSession::create([
            'institute_id' => $institute->id,
            'token_id' => $tokenId,
            'device' => 'Android Device',
            'os' => 'Android 13',
            'last_login' => now(),
            'last_open' => now(),
        ]);

        $this->assertDatabaseHas('device_sessions', [
            'id' => $session->id,
            'deleted_at' => null,
        ]);

        // Call the institute logout API
        $response = $this->postJson('/api/v1/institute/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        // Due to database cascade delete onDelete('cascade') on token_id, 
        // deleting the personal access token deletes the device session physically.
        $this->assertDatabaseMissing('device_sessions', [
            'id' => $session->id,
        ]);

        // Verify token was deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenId,
        ]);
    }

    public function test_institute_logout_via_api_terminates_device_session_using_fallback_when_token_unmatched(): void
    {
        $institute = $this->createInstitute();

        // Create Sanctum Token for the request authentication
        $tokenResult = $institute->createToken('access_token', ['access-api']);
        $token = $tokenResult->plainTextToken;
        $tokenId = $tokenResult->accessToken->id;

        // Create a Device Session with null token_id (simulating a fallback match scenario like web/session)
        // It has matching request headers (device / OS / FCM token)
        $session = DeviceSession::create([
            'institute_id' => $institute->id,
            'token_id' => null,
            'device' => 'iPhone',
            'os' => 'iOS',
            'last_login' => now(),
            'last_open' => now(),
        ]);

        $this->assertDatabaseHas('device_sessions', [
            'id' => $session->id,
            'deleted_at' => null,
        ]);

        // Call the institute logout API sending the device headers
        $response = $this->postJson('/api/v1/institute/logout', [], [
            'Authorization' => 'Bearer ' . $token,
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        // Verify the device session was terminated (soft deleted since token_id is null and it does not cascade delete)
        $this->assertSoftDeleted($session);

        // Verify request token was also deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenId,
        ]);
    }

    public function test_institute_logout_via_general_api_terminates_device_session_by_token(): void
    {
        $institute = $this->createInstitute();

        // Create Sanctum Token
        $tokenResult = $institute->createToken('access_token', ['access-api']);
        $token = $tokenResult->plainTextToken;
        $tokenId = $tokenResult->accessToken->id;

        // Create a Device Session linked to that token
        $session = DeviceSession::create([
            'institute_id' => $institute->id,
            'token_id' => $tokenId,
            'device' => 'Android Device',
            'os' => 'Android 13',
            'last_login' => now(),
            'last_open' => now(),
        ]);

        $this->assertDatabaseHas('device_sessions', [
            'id' => $session->id,
            'deleted_at' => null,
        ]);

        // Call the general API logout
        $response = $this->postJson('/api/v1/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        // Due to database cascade delete onDelete('cascade') on token_id, 
        // deleting the personal access token deletes the device session physically.
        $this->assertDatabaseMissing('device_sessions', [
            'id' => $session->id,
        ]);

        // Verify token was deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenId,
        ]);
    }

    public function test_loading_profile_via_api_prunes_expired_device_sessions(): void
    {
        $institute = $this->createInstitute();

        // Create Sanctum Token that is already expired
        $tokenResult = $institute->createToken('access_token', ['access-api']);
        $token = $tokenResult->plainTextToken;
        $tokenId = $tokenResult->accessToken->id;

        // Set expires_at in the DB explicitly to the past
        \DB::table('personal_access_tokens')
            ->where('id', $tokenId)
            ->update(['expires_at' => now()->subMinutes(5)]);

        // Create a Device Session linked to that expired token
        $session = DeviceSession::create([
            'institute_id' => $institute->id,
            'token_id' => $tokenId,
            'device' => 'Android Device',
            'os' => 'Android 13',
            'last_login' => now(),
            'last_open' => now(),
        ]);

        $this->assertDatabaseHas('device_sessions', [
            'id' => $session->id,
            'deleted_at' => null,
        ]);

        // Create a valid token to authenticate the profile request
        $validTokenResult = $institute->createToken('valid_access_token', ['access-api']);
        $validToken = $validTokenResult->plainTextToken;

        // Call the profile endpoint
        $response = $this->getJson('/api/v1/institute/profile', [
            'Authorization' => 'Bearer ' . $validToken,
        ]);

        $response->assertOk();

        // The session associated with the expired token should be deleted/pruned
        $this->assertDatabaseMissing('device_sessions', [
            'id' => $session->id,
        ]);
    }
}
