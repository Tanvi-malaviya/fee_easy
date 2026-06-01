<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBirthdayNotifications extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'birthday:send-notifications';

    /**
     * The console command description.
     */
    protected $description = 'Send automatic push notifications to students and parents on their birthday.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $todayMonthDay = Carbon::today()->format('m-d');

        // Find all students whose birthday (month and day) matches today
        $students = Student::whereNotNull('dob')
            ->get()
            ->filter(function ($student) use ($todayMonthDay) {
                try {
                    return Carbon::parse($student->dob)->format('m-d') === $todayMonthDay;
                } catch (\Exception $e) {
                    return false;
                }
            });

        if ($students->isEmpty()) {
            $this->info('No student birthdays to celebrate today.');
            return;
        }

        $fcm = app(\App\Services\FCMService::class);
        $count = 0;

        foreach ($students as $student) {
            $notifTitle = "Happy Birthday! 🎂";
            $notifBody  = "Happy Birthday, {$student->name}! Wishing you a wonderful day ahead. 🎉";
            $notifData  = [
                'type'       => 'birthday_celebration',
                'student_id' => (string) $student->id,
            ];

            // 1. DB notification for student
            Notification::create([
                'user_type' => 'student',
                'user_id' => $student->id,
                'title' => $notifTitle,
                'message' => $notifBody,
                'type' => 'birthday_celebration',
                'reference_id' => $student->id,
                'is_read' => false,
            ]);

            // 2. FCM push notification for student mobile app
            if (!empty($student->fcm_token)) {
                $fcm->send($student->fcm_token, $notifTitle, $notifBody, $notifData);
            }

            // 3. Notify parent (if exists)
            if ($student->parent) {
                $parentTitle = "Happy Birthday! 🎂";
                $parentBody  = "Wishing {$student->name} a very Happy Birthday! 🎂🎉";
                $parentData  = [
                    'type'       => 'birthday_celebration_parent',
                    'student_id' => (string) $student->id,
                ];

                // DB notification for parent
                Notification::create([
                    'user_type' => 'parent',
                    'user_id' => $student->parent->id,
                    'title' => $parentTitle,
                    'message' => $parentBody,
                    'type'         => 'birthday_celebration',
                    'reference_id' => $student->id,
                    'is_read' => false,
                ]);

                // FCM push notification for parent mobile app
                if (!empty($student->parent->fcm_token)) {
                    $fcm->send($student->parent->fcm_token, $parentTitle, $parentBody, $parentData);
                }
            }

            $count++;
            $this->info("Sent birthday greeting to: {$student->name}");
        }

        $this->info("Successfully processed {$count} birthday notification(s).");
    }
}
