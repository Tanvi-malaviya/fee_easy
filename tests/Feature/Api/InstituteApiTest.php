<?php

namespace Tests\Feature\Api;

use App\Models\Batch;
use App\Models\Fee;
use App\Models\Institute;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InstituteApiTest extends TestCase
{
    use RefreshDatabase;

    private function createInstitute(): Institute
    {
        $institute = Institute::create([
            'name' => 'Test Institute',
            'email' => 'institute@example.com',
            'phone' => '9999999999',
            'password' => 'password',
            'institute_name' => 'Test Institute Academy',
        ]);

        Sanctum::actingAs($institute, ['*']);

        return $institute;
    }

    public function test_institute_can_upload_logo_and_view_profile(): void
    {
        Storage::fake('public');

        $institute = $this->createInstitute();

        $file = UploadedFile::fake()->image('logo.png');

        $response = $this->postJson('/api/v1/institute/logo/upload', [
            'logo' => $file,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['status', 'message', 'data' => ['logo']]);

        $logoPath = $response->json('data.logo');
        Storage::disk('public')->assertExists($logoPath);

        $profileResponse = $this->getJson('/api/v1/institute/profile');

        $profileResponse->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.email', $institute->email);
    }

    public function test_daily_updates_and_homeworks_can_be_created_and_listed(): void
    {
        $institute = $this->createInstitute();

        $batch = Batch::create([
            'institute_id' => $institute->id,
            'name' => 'Math Batch',
            'subject' => 'Mathematics',
            'start_time' => '08:00',
            'end_time' => '10:00',
        ]);

        $dailyResponse = $this->postJson('/api/v1/institute/daily-updates', [
            'batch_id' => $batch->id,
            'topic' => 'Algebra Review',
            'description' => 'Covered linear equations and sample problems.',
            'date' => now()->toDateString(),
        ]);

        $dailyResponse->assertCreated()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.topic', 'Algebra Review');

        $homeworkResponse = $this->postJson('/api/v1/institute/homeworks', [
            'batch_id' => $batch->id,
            'title' => 'Chapter 1 Exercises',
            'description' => 'Complete problems 1-10 from chapter 1.',
            'due_date' => now()->addDays(3)->toDateString(),
        ]);

        $homeworkResponse->assertCreated()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.title', 'Chapter 1 Exercises');

        $listDailyResponse = $this->getJson('/api/v1/institute/daily-updates');
        $listDailyResponse->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.topic', 'Algebra Review');

        $listHomeworkResponse = $this->getJson('/api/v1/institute/homeworks');
        $listHomeworkResponse->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Chapter 1 Exercises');
    }

    public function test_notifications_and_whatsapp_settings_endpoints_work(): void
    {
        $this->createInstitute();

        $sendResponse = $this->postJson('/api/v1/institute/notifications/send', [
            'title' => 'Notice',
            'message' => 'The course schedule has changed.',
            'type' => 'announcement',
            'target' => 'students',
        ]);

        $sendResponse->assertCreated()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.title', 'Notice');

        $getNotifications = $this->getJson('/api/v1/institute/notifications');
        $getNotifications->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.message', 'The course schedule has changed.');

        $whatsappResponse = $this->postJson('/api/v1/institute/whatsapp-settings', [
            'phone_number' => '9999999999',
            'access_token' => 'token-abc',
            'phone_number_id' => '12345',
            'business_account_id' => '67890',
        ]);

        $whatsappResponse->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.is_active', true);

        $updatedResponse = $this->putJson('/api/v1/institute/whatsapp-settings', [
            'phone_number' => '8888888888',
            'access_token' => 'token-def',
        ]);

        $updatedResponse->assertOk()
            ->assertJsonPath('data.phone_number', '8888888888');

        $showResponse = $this->getJson('/api/v1/institute/whatsapp-settings');
        $showResponse->assertOk()
            ->assertJsonPath('data.phone_number', '8888888888');
    }

    public function test_reports_and_subscription_renewal_endpoints(): void
    {
        $institute = $this->createInstitute();

        $student = Student::create([
            'name' => 'Student One',
            'email' => 'student@example.com',
            'phone' => '9000000000',
            'institute_id' => $institute->id,
        ]);

        Fee::create([
            'student_id' => $student->id,
            'institute_id' => $institute->id,
            'total_amount' => 1000.00,
            'paid_amount' => 400.00,
            'due_amount' => 600.00,
            'status' => 'partial',
            'month' => 4,
            'year' => 2026,
        ]);

        $subscription = Subscription::create([
            'institute_id' => $institute->id,
            'plan_name' => 'Starter',
            'amount' => 500.00,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(20),
            'status' => 'active',
        ]);

        SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'amount' => 500.00,
            'payment_gateway' => 'stripe',
            'transaction_id' => 'txn_123',
            'paid_at' => now()->subDays(9),
        ]);

        $dashboardResponse = $this->getJson('/api/v1/institute/reports/dashboard');

        $dashboardResponse->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.total_revenue', 500.00)
            ->assertJsonPath('data.total_fees', 1000.00)
            ->assertJsonPath('data.total_due_fees', 600.00);

        $incomeResponse = $this->getJson('/api/v1/institute/reports/income');
        $incomeResponse->assertOk()
            ->assertJsonPath('data.summary.total_amount', 500.00)
            ->assertJsonCount(1, 'data.payments');

        $feesResponse = $this->getJson('/api/v1/institute/reports/fees');
        $feesResponse->assertOk()
            ->assertJsonPath('data.summary.total_amount', 1000.00)
            ->assertJsonPath('data.summary.paid_amount', 400.00)
            ->assertJsonPath('data.summary.due_amount', 600.00);

        $renewResponse = $this->postJson('/api/v1/institute/subscription/renew', [
            'amount' => 700.00,
            'days' => 30,
            'payment_gateway' => 'razorpay',
            'payment_source' => 'manual',
            'transaction_id' => 'txn_renew_1',
        ]);

        $renewResponse->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('subscription_payments', [
            'transaction_id' => 'txn_renew_1',
            'amount' => 700.00,
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'institute_id' => $institute->id,
            'status' => 'active',
            'amount' => 700.00,
        ]);
    }
}
