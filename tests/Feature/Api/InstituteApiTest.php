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
            'email_verified_at' => now(),
        ]);

        Subscription::create([
            'institute_id' => $institute->id,
            'plan_name' => 'Premium',
            'amount' => 1000,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(30),
            'status' => 'active',
        ]);

        Sanctum::actingAs($institute, ['*']);

        return $institute;
    }

    public function test_institute_can_upload_logo_and_view_profile(): void
    {
        Storage::fake('public');

        $institute = $this->createInstitute();

        $file = UploadedFile::fake()->create('logo.png', 100);

        $response = $this->postJson('/api/v1/institute/logo/upload', [
            'logo' => $file,
            'institute_name' => $institute->institute_name,
            'institute_code' => $institute->institute_code,
            'name' => $institute->name,
            'phone' => $institute->phone,
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

        $initialDailyCount = \App\Models\DailyUpdate::where('institute_id', $institute->id)->count();
        $initialHomeworkCount = \App\Models\Homework::where('institute_id', $institute->id)->count();

        $dailyResponse = $this->postJson('/api/v1/institute/daily-updates', [
            'batch_id' => $batch->id,
            'topic' => 'Algebra Review',
            'description' => 'Covered linear equations and sample problems.',
            'date' => now()->toDateString(),
            'category' => 'Academic',
            'recipient' => 'students',
            'target_type' => 'batch',
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
            ->assertJsonCount($initialDailyCount + 1, 'data')
            ->assertJsonPath('data.0.topic', 'Algebra Review');

        $listHomeworkResponse = $this->getJson('/api/v1/institute/homeworks');
        $listHomeworkResponse->assertOk()
            ->assertJsonCount($initialHomeworkCount + 1, 'data.data')
            ->assertJsonPath('data.data.0.title', 'Chapter 1 Exercises');
    }

    public function test_notifications_and_whatsapp_settings_endpoints_work(): void
    {
        $institute = $this->createInstitute();

        $initialNotificationCount = \App\Models\Notification::where('user_type', 'institute')->where('user_id', $institute->id)->count();

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
            ->assertJsonCount($initialNotificationCount + 1, 'data')
            ->assertJsonPath('data.0.message', 'The course schedule has changed.');

        $whatsappResponse = $this->postJson('/api/v1/institute/whatsapp-settings', [
            'phone_number' => '9999999999',
            'access_token' => 'token-abc-long-access-token-more-than-20-chars',
            'phone_number_id' => '12345',
            'business_account_id' => '67890',
        ]);

        $whatsappResponse->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.is_active', true);

        $updatedResponse = $this->putJson('/api/v1/institute/whatsapp-settings', [
            'phone_number' => '8888888888',
            'access_token' => 'token-def-long-access-token-more-than-20-chars',
            'phone_number_id' => '12345',
            'business_account_id' => '67890',
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
            'date' => now()->toDateString(),
        ]);

        $dashboardResponse = $this->getJson('/api/v1/institute/reports/dashboard');

        $dashboardResponse->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.total_fees', '1000.00')
            ->assertJsonPath('data.total_paid_fees', '400.00');

        $feesResponse = $this->getJson('/api/v1/institute/reports/fee');
        $feesResponse->assertOk()
            ->assertJsonPath('status', 'success');

        Storage::fake('public');
        $file = UploadedFile::fake()->create('proof.png', 100);

        $renewResponse = $this->postJson('/api/v1/institute/subscription/renew', [
            'transaction_id' => 'txn_renew_1',
            'screenshot' => $file,
            'message' => 'Please approve mobile renewal',
        ]);

        $renewResponse->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('subscription_renewals', [
            'institute_id' => $institute->id,
            'transaction_id' => 'txn_renew_1',
            'status' => 'pending',
        ]);
    }
}
