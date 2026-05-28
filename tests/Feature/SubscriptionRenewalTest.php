<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Institute;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\User;
use App\Models\SubscriptionRenewal;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SubscriptionRenewalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Standard setup for testing
        Storage::fake('public');
    }

    /** @test */
    public function expired_institute_sees_expired_banner_and_can_submit_manual_renewal()
    {
        // 1. Create a plan
        $plan = Plan::create([
            'name' => 'Gold Plan',
            'price' => 1000,
            'duration_days' => 365,
            'trial_days' => 14,
            'status' => true
        ]);

        // 2. Create an institute
        $institute = Institute::create([
            'name' => 'Alpha Academy',
            'email' => 'alpha@test.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Alpha Academy',
            'address' => '456 Lane',
            'city' => 'Surat',
            'state' => 'Gujarat',
            'pincode' => '395007',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 3. Create an expired subscription
        Subscription::create([
            'institute_id' => $institute->id,
            'plan_name' => $plan->name,
            'amount' => $plan->price,
            'start_date' => now()->subDays(370),
            'end_date' => now()->subDays(5),
            'status' => 'expired'
        ]);

        $this->assertFalse($institute->hasActiveSubscription());

        // 4. Authenticate as the institute and visit the dashboard
        $response = $this->actingAs($institute, 'institute')
            ->get(route('institute.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Your Subscription Has Expired!');
        $response->assertSee('Renew Subscription Now');

        // 5. Submit renewal payment proof via AJAX
        $screenshot = UploadedFile::fake()->create('payment_proof.png', 100, 'image/png');

        $renewalResponse = $this->actingAs($institute, 'institute')
            ->post(route('institute.subscription.renew'), [
                'transaction_id' => 'TXN123456789',
                'screenshot' => $screenshot,
                'message' => 'Please verify my manual offline payment.'
            ], ['X-Requested-With' => 'XMLHttpRequest']);

        $renewalResponse->assertStatus(200);
        $renewalResponse->assertJson([
            'status' => 'success',
            'message' => 'Your subscription renewal request has been submitted successfully. We will review and activate it shortly!'
        ]);

        // 6. Assert renewal is saved in the database
        $this->assertDatabaseHas('subscription_renewals', [
            'institute_id' => $institute->id,
            'transaction_id' => 'TXN123456789',
            'message' => 'Please verify my manual offline payment.',
            'status' => 'pending'
        ]);

        // 7. Get the saved renewal and assert screenshot file exists
        $renewal = SubscriptionRenewal::first();
        $this->assertNotNull($renewal->screenshot);
        Storage::disk('public')->assertExists($renewal->screenshot);

        // 8. Re-visit the dashboard and assert it now shows the pending banner
        $dashboardResponse = $this->actingAs($institute, 'institute')
            ->get(route('institute.dashboard'));

        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Renewal Request Pending Review');
        $dashboardResponse->assertSee('We have received your payment proof and transaction reference.');
    }

    /** @test */
    public function admin_can_approve_offline_subscription_renewal()
    {
        // 1. Setup plan & institute
        $plan = Plan::create([
            'name' => 'Premium Plan',
            'price' => 5000,
            'duration_days' => 365,
            'trial_days' => 14,
            'status' => true
        ]);

        $institute = Institute::create([
            'name' => 'Beta Academy',
            'email' => 'beta@test.com',
            'phone' => '0987654321',
            'password' => bcrypt('password123'),
            'institute_name' => 'Beta Academy',
            'address' => '123 Cross St',
            'city' => 'Surat',
            'state' => 'Gujarat',
            'pincode' => '395007',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 2. Setup renewal request
        $renewal = SubscriptionRenewal::create([
            'institute_id' => $institute->id,
            'transaction_id' => 'TXN999888777',
            'screenshot' => 'proofs/test_proof.png',
            'message' => 'Manual payment confirmation',
            'status' => 'pending'
        ]);

        // 3. Setup Admin User
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@feeeasy.com',
            'password' => bcrypt('password'),
        ]);

        // 4. Admin accesses index page and sees pending requests
        $response = $this->actingAs($admin)
            ->get(route('subscriptions.index'));

        $response->assertStatus(200);
        $response->assertSee('Manual Offline Renewal Verification');
        $response->assertSee('Beta Academy');
        $response->assertSee('TXN999888777');

        // 5. Admin approves renewal
        $approveResponse = $this->actingAs($admin)
            ->patch(route('subscriptions.renewals.approve', $renewal), [
                'plan_id' => $plan->id
            ]);

        $approveResponse->assertRedirect();
        
        // 6. Verify database updates
        $this->assertDatabaseHas('subscription_renewals', [
            'id' => $renewal->id,
            'status' => 'approved'
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'institute_id' => $institute->id,
            'plan_name' => $plan->name,
            'amount' => $plan->price,
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('subscription_payments', [
            'amount' => $plan->price,
            'payment_source' => 'offline_renewal'
        ]);
    }

    /** @test */
    public function admin_can_reject_offline_subscription_renewal()
    {
        // 1. Setup plan & institute
        $institute = Institute::create([
            'name' => 'Gamma School',
            'email' => 'gamma@test.com',
            'phone' => '1122334455',
            'password' => bcrypt('password123'),
            'institute_name' => 'Gamma School',
            'address' => '789 High Rd',
            'city' => 'Surat',
            'state' => 'Gujarat',
            'pincode' => '395007',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 2. Setup renewal request
        $renewal = SubscriptionRenewal::create([
            'institute_id' => $institute->id,
            'transaction_id' => 'TXN777666555',
            'screenshot' => 'proofs/test_proof2.png',
            'message' => 'Manual payment confirmation',
            'status' => 'pending'
        ]);

        // 3. Setup Admin User
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@feeeasy.com',
            'password' => bcrypt('password'),
        ]);

        // 4. Admin rejects renewal
        $rejectResponse = $this->actingAs($admin)
            ->patch(route('subscriptions.renewals.reject', $renewal));

        $rejectResponse->assertRedirect();
        
        // 5. Verify database updates
        $this->assertDatabaseHas('subscription_renewals', [
            'id' => $renewal->id,
            'status' => 'rejected'
        ]);
    }
}
