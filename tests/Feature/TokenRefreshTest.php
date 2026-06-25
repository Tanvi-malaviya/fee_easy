<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class TokenRefreshTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_returns_access_token_with_1hr_and_refresh_token_with_24hr_expiry()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'token',
                    'access_token',
                    'refresh_token',
                    'user'
                ]
            ]);

        $data = $response->json('data');

        // Verify tokens in database
        $accessToken = PersonalAccessToken::findToken($data['access_token']);
        $refreshToken = PersonalAccessToken::findToken($data['refresh_token']);

        $this->assertNotNull($accessToken);
        $this->assertNotNull($refreshToken);

        // Access token should expire in ~1 hour (3600 seconds)
        $accessExpiresDiff = $accessToken->expires_at->diffInSeconds(now());
        $this->assertGreaterThanOrEqual(3590, $accessExpiresDiff);
        $this->assertLessThanOrEqual(3610, $accessExpiresDiff);

        // Refresh token should expire in ~24 hours (1440 minutes)
        $refreshExpiresDiff = $refreshToken->expires_at->diffInMinutes(now());
        $this->assertGreaterThanOrEqual(1438, $refreshExpiresDiff);
        $this->assertLessThanOrEqual(1442, $refreshExpiresDiff);
    }

    /** @test */
    public function access_tokens_can_access_protected_routes()
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $accessToken = $user->createToken('access_token', ['access-api'], now()->addMinute())->plainTextToken;

        // Try to access profile with access token (should pass)
        $this->withHeader('Authorization', 'Bearer ' . $accessToken)
            ->getJson('/api/v1/auth/profile')
            ->assertStatus(200);
    }

    /** @test */
    public function refresh_tokens_cannot_access_protected_routes()
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $refreshToken = $user->createToken('refresh_token', ['refresh-token'], now()->addHours(24))->plainTextToken;

        // Try to access profile with refresh token (should fail)
        $this->withHeader('Authorization', 'Bearer ' . $refreshToken)
            ->getJson('/api/v1/auth/profile')
            ->assertStatus(401);
    }

    /** @test */
    public function valid_refresh_token_can_be_exchanged_for_new_tokens()
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);
        $refreshToken = $user->createToken('refresh_token', ['refresh-token'], now()->addHours(24))->plainTextToken;

        // Perform token refresh request
        $response = $this->postJson('/api/v1/auth/refresh', [
            'refresh_token' => $refreshToken,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'access_token',
                    'refresh_token',
                ]
            ]);

        $data = $response->json('data');

        // Confirm the old refresh token was revoked
        $this->assertNull(PersonalAccessToken::findToken($refreshToken));

        // Confirm new tokens exist
        $this->assertNotNull(PersonalAccessToken::findToken($data['access_token']));
        $this->assertNotNull(PersonalAccessToken::findToken($data['refresh_token']));
    }
}
