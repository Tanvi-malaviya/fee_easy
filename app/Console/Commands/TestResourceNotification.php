<?php

namespace App\Console\Commands;

use App\Models\Institute;
use App\Services\FCMService;
use Illuminate\Console\Command;

class TestResourceNotification extends Command
{
    protected $signature = 'fcm:test-resource {institute_email : Institute email to test with}';
    protected $description = 'Test: Send a fake resource push notification to institute FCM token';

    public function handle(FCMService $fcm)
    {
        $email     = $this->argument('institute_email');
        $institute = Institute::where('email', $email)->first();

        if (!$institute) {
            $this->error("Institute with email [{$email}] not found.");
            return 1;
        }

        if (empty($institute->fcm_token)) {
            $this->error("Institute found but fcm_token is empty. Please login from mobile app first.");
            return 1;
        }

        $this->info("Found institute: {$institute->institute_name}");
        $this->info("FCM Token (first 30 chars): " . substr($institute->fcm_token, 0, 30) . "...");
        $this->info("Sending test push notification...");

        $result = $fcm->send(
            $institute->fcm_token,
            '📄 New Resource: Batch A',
            'Physics Chapter 5 Notes has been uploaded!',
            ['type' => 'resource', 'batch_id' => '6', 'resource_id' => '1']
        );

        if ($result) {
            $this->info("✅ SUCCESS! Push notification sent successfully.");
        } else {
            $this->error("❌ FAILED. Check storage/logs/laravel.log for details.");
        }

        return 0;
    }
}
