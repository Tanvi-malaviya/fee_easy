<?php

namespace App\Console\Commands;

use App\Services\FCMService;
use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Institute;
use App\Models\StudentParent;
use App\Models\User;
use App\Models\Staff;

class TestFCMNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:test {email : The email of the student, parent, or institute} {title=Test Notification} {body=This is a test push notification from backend}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test push notification to a registered user via FCM v1 API';

    /**
     * Execute the console command.
     */
    public function handle(FCMService $fcmService)
    {
        $email = $this->argument('email');
        $title = $this->argument('title');
        $body = $this->argument('body');

        $this->info("Looking for user with email: {$email}");

        // Check across models
        $user = Student::where('email', $email)->first()
            ?? Institute::where('email', $email)->first()
            ?? StudentParent::where('email', $email)->first()
            ?? User::where('email', $email)->first()
            ?? Staff::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        if (empty($user->fcm_token)) {
            $this->error("User found (" . get_class($user) . "), but fcm_token is not registered.");
            return 1;
        }

        $this->info("Found user (" . get_class($user) . "). Sending push notification to token: " . substr($user->fcm_token, 0, 15) . "...");

        $success = $fcmService->sendToUser($user, $title, $body, ['test_key' => 'verified']);

        if ($success) {
            $this->info("Push notification successfully sent via Firebase Cloud Messaging!");
            return 0;
        } else {
            $this->error("Failed to send push notification. Please check laravel.log for details.");
            return 1;
        }
    }
}
