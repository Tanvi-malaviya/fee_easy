<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Institute;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\DailyUpdate;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstituteDailyUpdateTest extends TestCase
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

    public function test_creating_holiday_update_saves_date_and_sends_notification()
    {
        $institute = $this->createInstitute();

        // Create parent
        $parent = StudentParent::create([
            'name' => 'Parent Name',
            'email' => 'parent@example.com',
            'phone' => '9999999999',
            'password' => bcrypt('password123'),
        ]);

        // Create student
        $student = Student::create([
            'institute_id' => $institute->id,
            'parent_id' => $parent->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '9888888888',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        // Mock FCM Service
        $fcmMock = $this->createMock(\App\Services\FCMService::class);
        $this->app->instance(\App\Services\FCMService::class, $fcmMock);

        // Make API request to create Holiday Daily Update
        $response = $this->actingAs($institute, 'sanctum')
            ->postJson('/api/v1/institute/daily-updates', [
                'category' => 'Holiday',
                'target_type' => 'all',
                'topic' => 'Diwali Vacation',
                'description' => 'School will remain closed for Diwali.',
                'date' => '2026-11-10',
            ]);

        $response->assertStatus(201);

        // Assert database persistence of update with date
        $this->assertDatabaseHas('daily_updates', [
            'institute_id' => $institute->id,
            'category' => 'Holiday',
            'topic' => 'Diwali Vacation',
            'description' => 'School will remain closed for Diwali.',
            'date' => '2026-11-10',
        ]);

        // Assert student notification was created
        $this->assertDatabaseHas('notifications', [
            'user_type' => 'student',
            'user_id' => $student->id,
            'title' => 'Daily Update · Holiday',
            'type' => 'daily_update',
        ]);

        // Assert parents are NOT notified for daily updates
        $this->assertDatabaseMissing('notifications', [
            'user_type' => 'parent',
            'user_id' => $parent->id,
            'type' => 'daily_update',
        ]);

        // Fetch notifications and assert correct content format
        $studentNotif = Notification::where('user_type', 'student')->first();
        $this->assertNotNull($studentNotif);
        $this->assertStringContainsString('Holiday Announcement on 10-Nov-2026', $studentNotif->message);
    }
}
